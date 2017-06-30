<?php
namespace App\admin\Model;

class getStudentModel extends infoModel
{
    const CPAGESIZE        = 15; 
    const PAGESIZE         = 8;
    const USEREXPTIME     = 21600;         //登陆有效期，单位s
    private $user          = array();
    private $centerArr     = array('mobile','name','picUrl','stuId');
    private $baseArr       = array('name','mobile','email','sex','age','bloodType','provinceId','homeAddress','description');
    private $courseArr     = array('courseId');
    private $secCourseArr  = array('id','stageId','content');
    private $projectArr    = array("projectId","projectName","description","status","startTime","endTime","picUrl","url","people","type");
    private $concernArr    = array("concerned","concern","conTime");
    private $concernedArr  = array();
    private $recommendArr  = array('id','stuId','dateinto');
    private $workArr       = array('id','compName','jobName','salary','dateWork','workOut','description');
    private $stuProjectArr = array("id","projectId","stuId","assess","stuDescription","professional");
    private $companyArr    = array('compId','compName','email');
    private $educationArr   = array('id','stuId','major','eduSchool','dateinto','dateout','highest');
    
    
    public function __construct()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
            if (($_SESSION['user']['user_expTime'] + self::USEREXPTIME) > time()){
                $this->user = $_SESSION['user'];
            }
         }
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
     * 验证登陆者信息
     * @param string $accNumber 学号
     * @return boolean
     */
    public function verifyUser($accNumber)
    {
        $res = false;
        if(isset($this->user) && isset($this->user['stuId']) && $this->user['stuId'] == $accNumber){
            $res = true;
        }
        return $res;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    /**
     * 获得学生相关信息
     */
    public function getStuInfo()
    {
        global $_LS;
        $data       = array(); 
        @$stuId     = $_LS['accNumber'];
        @$param     = $_LS['param'];
        @$page      = intval($_LS['page'])?intval($_LS['page']):1;
        @$pageSize  = intval($_LS['pageSize'])?intval($_LS['pageSize']):self::PAGESIZE;
        if($stuId && $param){
            if($this->verifyUser($stuId)){
                $data['info']   = $this->getStuInfo_byParam($stuId,$param,$page,$pageSize);
                $data           = parent::formatResponse($data['info']);
            }else{
                $data['status'] = 3;
            }
        }else{
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 根据不同的字段获得学生信息
     * @param string $stuId
     * @param string $param
     * @return array
     */
    public function getStuInfo_byParam($stuId,$param,$page,$pageSize)
    {
        $data           = array();
        $where['stuId'] = $stuId;
        switch($param){
            case      'base'://基础数据
                $data = $this->getStuBase_byStuId($stuId);
                break;
            case    'course'://课程信息
                $data = $this->getStuCourse($where,$page);//没有完成,还需改善
                break;
            case   'project'://简历、作品或项目信息
                $data = $this->getStuPro_byStuId($stuId);//完成
                break;
            case   'concern'://关注信息
                $data = $this->getStuConcern($stuId,$param,$page,$pageSize);//完成
                break;
            case 'concerned'://被关注信息
                $data = $this->getStuConcern($stuId,$param,$page,$pageSize);//完成
                break;
            case 'recommend': //推荐信息
                $data = $this->getStuRecommend($stuId,$page);//完成
                break;
            case      'work':
                $data = $this->getStuWork_byStuId($stuId);
                break;
            case 'education':
                $data = $this->getStuEdu_byStuId($stuId);
                break;
            case    'center': //核心数据
            default         :
                $data = $this->getStuCenter($stuId);//完成
                break;
        }
        return $data;
    }
    /**
     * 获得学生基本信息
     * @param  $stuId
     */
    public function getStuBase_byStuId($stuId)
    {
        $arr   = $this->baseArr; 
        $table = array('leading_student','leading_student_info');
        $where = " AND f.stuId = s.stuId AND s.stuId = {$stuId} ";
        $data  = parent::fetchOne_byArrJoin($table,$arr,$where);
        if(isset($data['provinceId']) && !empty($data['provinceId'])){
            $res = verifyModel::province($data['provinceId']);
            if(count($res) > 0){//如果存在信息
                $data['province'] = $res['province'];
            }
        }
        return $data;
    }
    
    /**
     * 获得核心信息
     * @param string $stuId
     * @return array
     */
    public function getStuCenter($stuId)
    {
        $data  = array();
        $arr   = $this->centerArr;
        $table = array('leading_student','leading_student_info');
        $where = " AND f.stuId = s.stuId AND f.stuId = '{$stuId}' ";
        $data  = parent::fetchOne_byArrJoin($table,$arr,$where);
        return $data;
    }
    /**
     * 获得学生的课程信息
     * @param unknown $where
     */
    public function getStuCourse($where,$page)
    {
        $data  = array();
        $res   = array();
        $arr   = $this->courseArr;
        $table = 'student_course';
        $res   = parent::fetchAll_byArr($table,$arr,$where);
        if(count($res) > 0){
            
            //获取课程内容
            $arr_2             = $this->secCourseArr;
            $table_2           = 'course_content';
            foreach ($res as $val){
                $courseId               = $val['courseId'];
                $where_2['courseId']    = $courseId;
                $data["{$courseId}"]    = page($table_2,$arr_2,$where_2,$page,self::CPAGESIZE);
            }
        }
        return $data;
    }
    
    /**
     * 获得学生项目信息
     * @param unknown $where
     * return array
     */
    public function getStuPro_byStuId($stuId)
    {
        $data  = array();
        
        //获取学生参与的所有项目
        $arr            = $this->stuProjectArr;
        $table          = 'student_project';
        $where['stuId'] = $stuId;
        $res            = parent::fetchAll_byArr($table,$arr,$where);
        
        //获得每个项目的详细信息
        $count = count($res);
        if($count > 0){
            $table_2 = "project";
            $arr_2   = $this->projectArr;
            for($i = 0;$i<$count;$i++){
                $where_2['projectId'] = $res[$i]['projectId'];
                $resp                 = parent::fetchOne_byArr($table_2,$arr_2,$where_2);
                $res[$i]              = array_merge($res[$i],$resp);
            }
            $data = $res;
        }
        return $data;
    }
    
    /**
     * 获得关注学生的企业或被关注
     * @param string $stuId
     * @param string $type
     * @return array
     */
    public function getStuConcern($accNumber,$type,$page,$pageSize)
    {
        $data               = array();
        $res  = $this->getConcernInfo_byType($accNumber,$type,$page,$pageSize);                 //获得关注|被关注账号信息
        //获得企业详细信息
        $count = count($res);
        if ( $count > 0) {
            $obj = new getCompanyModel();                                       //实例化企业模型
            for ($i = 0;$i < $count-1;$i++) {                                   //$count-1 去掉pages项
                $data[] = $obj->getCompCenter_byCompId(array('compId' => $res[$i]["{$type}"]));     //获得企业详细信息
            }
            $data['pages'] = $res['pages'];                                     //填充页码信息
        }
        return $data;
    }
    
    public function getConcernInfo_byType($accNumber,$type,$page,$pageSize)
    {
        $arr                = array("{$type}","conTime");
        $select             = trim(implode(' ',array_diff($this->concernArr,$arr)));
        $where["{$select}"] = $accNumber;
        $table              = 'concern';
        return page($table,$arr,$where,$page,$pageSize);
    }
    
    /**
     * 获得所推荐人的基本信息
     * @param string $accNumber 学号
     * @return array
     */
    public function getStuRecommend($accNumber,$page)
    {
        $data                 = array();
        $arr                  = $this->recommendArr;
        $table                = 'recommend';
        $where['recommendId'] = $accNumber;
        $res                  = page($table,$arr,$where,$page);                              //获得被推荐者账号
        $count = count($res);
        if($count > 0){
            for ($i = 0;$i < $count-1;$i++) {
                $data[] = array_merge($res[$i],$this->getStuCenter($res[$i]['stuId']));      //获得被推荐者的详细信息
            }
            $data['pages'] = $res['pages'];                                                  //填充页码信息
        }
        return $data;
    }
    
    /**
     * 通过学号获得学生工作经验
     * @param array $where
     * @return array
     */
    public function getStuWork_byStuId($stuId)
    {
        $data           = array();
        $arr            = $this->workArr;
        $table          = 'student_work';
        $where['stuId'] = $stuId;
        $data           = parent::fetchAll_byArr($table,$arr,$where);
        return $data;
    }
    
    /**
     * 获得学员教育经验
     * @param array $where
     * @return array
     */
    public function getStuEdu_byStuId($stuId)
    {
        $data           = array();
        $arr            = $this->educationArr;
        $table          = 'student_education';
        $where['stuId'] = $stuId;
        $data           = parent::fetchAll_byArr($table,$arr,$where);
        return $data;
    }
    
    /**
     * 获得学员简历信息
     * @return array
     */
    public function getStuResume()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if (isset($accNumber) && $this->verifyUser($accNumber)) {       //验证账号信息
            $data = $this->getStuResume_byStuId($accNumber);            //通过学号获得学员简历信息
            $data = parent::formatResponse($data);                      //格式化结果集
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 通过学号获得学员简历信息
     * @param string $accNumber 学号
     * @return array
     */
    public function getStuResume_byStuId($accNumber)
    {
        $data = array();
        $data['base']      = $this->getStuBase_byStuId($accNumber);     //获得学员基本信息
        $data['education'] = $this->getStuEdu_byStuId($accNumber);      //获得学员所有的教育经历
        $data['work']      = $this->getStuWork_byStuId($accNumber);     //获得学员所有的工作经验
        $data['project']   = $this->getStuPro_byStuId($accNumber);
        return $data;
    }
    
    /**
     * 获得该学生在三十天内投递简历的记录
     * @return multitype:
     */
    public function getStuResumeLog()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$page      = intval($_LS['page'])?intval($_LS['page']):1;                      //当前页
        @$pageSize  = intval($_LS['pageSize'])?intval($_LS['pageSize']):8;              //页容量
        @$interval  = intval($_LS['interval'])?intval($_LS['interval']):30;             //多少天内
        if ($accNumber) {
            if ($this->verifyUser($accNumber)) {
                $arr   = array('compId','jobId','jobName','r_status','resumeTime');
                $time  = time() - $interval * 24 * 60 * 60;
                $where = array("accNumber" => $accNumber,'where2' => " AND s.`jobId` = f.`jobId` AND resumeTime > $time ORDER BY resumeTime DESC ");    //30天之内
                $table = array('leading_job','leading_resume_log');
                $resp  = page($table,$arr,$where,$page,$pageSize);                      //获得投递信息
                $count = count($resp);
                if ($count > 0) {                                                                   //如果存在投递信息
                    for ($i = 0;$i < $count-1;$i++) {
                        $data['info'][$i] = array_merge($resp[$i],$this->getCompName_byId($resp[$i]['compId']));//合并企业名
                    }
                    $data['info']['pages'] = count($resp['pages']) > 0 ?$resp['pages']:array();     //合并页码信息
                    $data = parent::formatResponse($data['info']);                                  //格式化结果集
                } else {
                    $data['status'] = 4;
                    $data['msg']    = '没有投递的记录';
                }
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    //通过企业号获得企业名
    public function getCompName_byId($compId)
    {
        return parent::fetchOne_byArr('leading_company',array('compName'),array('compId' => $compId));
    }
    
}