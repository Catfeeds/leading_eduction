<?php
namespace App\admin\Model;

class getCompanyModel extends infoModel
{
    const PAGESIZE      =  8;
    const USEREXPTIME   = 21600;        //单位秒
    private $user = array();
    
    //表名
    private $studentTab     = 'leading_student';
    private $studentInfoTab = 'leading_student_info';
    private $companyTab     = 'leading_company';
    private $companyInfoTab = 'leading_company_info';
    private $jobTab         = 'leading_job';
    private $resumeLogTab   = 'leading_resume_log';
    
    //信息数组
    private $centerArr      = array('compId','compName','picUrl','unionCode','tel');
    private $baseArr        = array('id','compId','compName','picUrl','mobile','email','tel','address','legalPerson','description','licenseUrl');
    private $jobArr         = array('compId','jobId','jobName','jobDate','people','eduBacId','status','duty','demand','treatment','workAddress');
    private $jobStuInfoArr  = array('stuId','name','sex','age','mobile','email');
    
    
    public function __construct()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
            if (($_SESSION['user']['user_expTime'] + self::USEREXPTIME) > time()) {
                $this->user = $_SESSION['user'];
            }
         }
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function getArr($arr)
    {
        return $this->{$arr};
    }
    
    public function getTable($table)
    {
        return $this->{$table};
    }
    
    
    /**
     * string $accNumber 企业号 
     * @param unknown $accNumber
     */
    public function verifyUser($accNumber)
    {
        $res = false;
        if(isset($this->user) && isset($this->user['compId']) && $this->user['compId'] == $accNumber){
            if(($this->user['user_expTime'] + 60 * 60 * 6) > time()){
                $res = true;
            }
        }
        return $res;
    }
    /**
     * 获得企业信息
     * @return array
     */
    public function getCompanyInfo()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        @$param     = $_LS['param'];
        @$page      = intval($_LS['page'])?intval($_LS['page']):1;
        @$pageSize  = intval($_LS['pageSize'])?intval($_LS['pageSize']):self::PAGESIZE;
        if ($accNumber && $param) {
            if ($this->verifyUser($accNumber)) {
                $data['info'] = $this->getCompanyInfo_byParam($accNumber,$param,$page,$pageSize);       //获得信息
                $data         = parent::formatResponse($data['info']);
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 通过param参数获得公司信息
     * @param string $accNumber 企业号
     * @param string $param 信息标示符
     * @return array
     */
    public function getCompanyInfo_byParam($accNumber,$param,$page,$pageSize)
    {
        $data            = array();
        $where['compId'] = $accNumber;
        switch ($param) {
            case    'center':
                $data = $this->getCompCenter_byCompId($where);                              //获得企业核心信息
                break;
            case 'recruited':
                $data = $this->getCompRecruited_byCompId($where,$page,$pageSize);           //获得已有的招聘信息
                break;
            case   'concern':
                $data = $this->getCompConcern_byCompId($accNumber,$param,$page,$pageSize);  //获得企业关注的学生信息
                break;
            case 'concerned':
                $data = $this->getCompConcern_byCompId($accNumber,$param,$page,$pageSize);  //获得关注企业的学生信息
                break;
            case      'base':
            default:
                $data = $this->getCompBase_byCompId($where);                //获得企业基本信息
                break;
        }
        return $data;
    }
    
    /**
     * 通过企业号获得企业核心信息
     * @param array $where 
     */
    public function getCompCenter_byCompId($where)
    {
        $table = array($this->companyTab,$this->companyInfoTab);
        return parent::fetchOne_byArrJoin($table,$this->centerArr,$where);  //获得企业信息
    }
    
    /**
     * 通过企业号获得企业基本信息
     * @param array $where 
     */
    public function getCompBase_byCompId($where)
    {
        $table = array($this->companyTab,$this->companyInfoTab);
        return parent::fetchOne_byArrJoin($table,$this->baseArr,$where);
    }
    
    /**
     * 获得企业关注|被关注的学员核心信息
     * @param string  $accNumber
     * @param string $param
     * @return array
     */
    public function getCompConcern_byCompId($accNumber,$param,$page,$pageSize)
    {
        $data = array();
        $obj  = new getStudentModel();
        $res  = $obj->getConcernInfo_byType($accNumber,$param,$page,$pageSize);                             //获得关注|被关注学号
        $count = count($res);
        if($count > 0){
            $obj = new getStudentModel();
            for($i = 0;$i < $count-1;$i++) {
                $data[] = $obj->getStuCenter($res[$i]["{$param}"]);                         //获得学生核心信息，通过学号
            }
            $data['pages'] = $res['pages'];                                                 //填充页码信息
        }
        return $data;
    }
    
    /**
     * 获得企业已有的招聘信息
     * @param array $where
     * @retrun array
     */
    public function getCompRecruited_byCompId($where,$page,$pageSize)
    {
        return page($this->jobTab,$this->jobArr,$where,$page,$pageSize);
    }
    /**
     * 查看企业招聘职位下的所有投递简历的个人信息
     * @return array
     */
    public function getStuResume()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$page      = intval($_LS['page'])?intval($_LS['page']):1;
        @$pageSize  = intval($_LS['pageSize'])?intval($_LS['pageSize']):self::PAGESIZE;
        if (isset($accNumber)) {                        
            if ($this->verifyUser($accNumber)) {
                $res   = $this->getCompJobs_byJobId($accNumber,$page,$pageSize);                                    //获得公司下所有的招聘岗位
                $count = count($res);
                if ($count > 0) {
                    for ($i = 0;$i<$count-1;$i++) {
                        $data['info'][$i]["{$res[$i]['jobName']}"] = $this->getStuInfo_byJobId($res[$i]['jobId']);  //获得某个职位下的所有投递简历信息
                        $data = parent::formatResponse($data['info']);
                    }
                    $data['info']['pages'] = $res['pages'];
                } else {
                    $data['status'] = 61;
                    //$data['msg']    = '还没有职位信息，请到管理职位页面添加职位';
                }
            } else  {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 通过企业好获得该公司招聘职位的所有职位名和职位id
     * @param string $accNumber 企业号
     * return array 多维数组
     */
    public function getCompJobs_byJobId($accNumber,$page,$pageSize) 
    {
        $table = $this->jobTab;
        $arr   = array('jobId','jobName');
        $where = array("compId"=>$accNumber);
        //return parent::fetchAll_byArr($table,$arr,$where,$page,$pageSize);
        return page($table,$arr,$where,$page,$pageSize);
    }
    
    /**
     * 通过职位id获得相关学生信息
     * @param string $jobId 职位id
     * return array 若没有信息返回空数组
     */
    public function getStuInfo_byJobId($jobId)
    {
        $data  = array();
        $res   = $this->getAllAcc_byJobId($jobId);                                                      //通过职位号获得所有投递简历的账号
        $count = count($res);
        if ($count > 0) {
            for ($i = 0;$i<$count;$i++) {                                                               //默认投递的账号是学号，
                $resumeLog = array_diff($res[$i],array('accNumber' => $res[$i]['accNumber']));          //投递简历的时间和状态
                $data[$i]  = array_merge($resumeLog,$this->getStuInfo_byStuId($res[$i]['accNumber']));  //通过学号获得学生信息，并与投递简历信息合并
            }
        }
        return $data;
    }
    
    /**
     * 通过职位号获得所有投递简历的账号
     * @param int $jobId 职位号
     * return array
     */
    public function getAllAcc_byJobId ($jobId)
    {
        $arr   = array('accNumber','resumeTime','r_status','l_id');
        $where = array('jobId'=>$jobId,'where2' => ' ORDER BY resumeTime ');
        return parent::fetchAll_byArr($this->resumeLogTab,$arr,$where);
    }
    
    /**
     * 通过学号获得简历中简约学生信息
     * @param string $stuId 学号
     * return array
     */
    public function getStuInfo_byStuId ($stuId)
    {
        $table = array($this->studentTab,$this->studentInfoTab);
        $where = array('stuId' => $stuId);
        $arr   = $this->jobStuInfoArr;
        return parent::fetchOne_byArrJoin($table,$arr,$where);
    }
    
    /**
     * 查看投递简历的简历信息
     * @return array
     */
    public function getResumeInfo()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$jobId     = $_LS['jobId'];
        @$stuId     = $_LS['stuId'];
        @$r_status  = $_LS['r_status'];
        if ($accNumber && $stuId && $jobId) {
            if ($this->verifyUser($accNumber)) {
                if ($this->verifyComAndStuId($accNumber,$stuId)) {                  //默认是学号，该学号投递了该公司
                    $data['info'] = $this->getStuResumeInfo_byStuId($stuId);        //通过学号获得该学员简历信息
                    $data         = parent::formatResponse($data['info']);
                    if ($data['status'] == 0) {
                        $r_status = empty($r_status)?2:$r_status;
                        $this->modifyRStatus($jobId,$stuId,$r_status);              //更改简历查看状态
                    }
                } else {
                    $data['status'] = 62;
                    //$data['msg']    = '该学员没有投递贵公司简历的记录';
                }
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 通过学号获得该学员简历信息
     * @param string $stuId 学号
     * return array
     */
    public function getStuResumeInfo_byStuId($stuId)
    {
        $obj = new getStudentModel();
        return $obj->getStuResume_byStuId($stuId);
    }
    
    /**
     * 验证该学号是否投递了该公司
     * @param string $accNumber 企业号
     * @param string $stuId 学号
     * return boolean
     */
    public function verifyComAndStuId($accNumber, $stuId)
    {
        $res   = false;
        $table = array($this->jobTab, $this->resumeLogTab);
        $where = array('compId' => $accNumber, 'accNumber' => $stuId);
        $arr   = array('jobId');
        $resp  = parent::fetchOne_byArrJoin($table,$arr,$where);
        if (count($resp) > 0) {
            $res = true;
        }
        return $res;
    }
    
    /**
     * 修改简历查看状态
     * @param id $jobId 职位id
     * @param string $accNumber 修改账号 
     * @param int $r_status 修改成该状态
     */
    public function modifyRStatus($jobId,$accNumber,$r_status)
    {
        $res      = 0;
        $resp     = false;
        $r_status = empty($r_status)?2:$r_status;
        $where    = array('jobId' => $jobId,'accNumber' => $accNumber);
        $arr      = array('r_status' => $r_status);
        $res      = parent::update($this->resumeLogTab,$arr,$where);
        if ($res > 0) {
            $resp = true;
        }
        return $resp;
    }
    
}