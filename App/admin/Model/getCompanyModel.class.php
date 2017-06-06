<?php
namespace App\admin\Model;

class getCompanyModel extends infoModel
{
    private $user = array();
    
    //表名
    private $studentTab     = 'leading_student';
    private $studentInfoTab = 'leading_student_info';
    private $companyTab     = 'leading_company';
    private $companyInfoTab = 'leading_company_info';
    private $jobTab         = 'leading_job';
    
    //信息数组
    private $centerArr      = array('compId','compName','picUrl','unionCode','tel');
    private $baseArr        = array('id','compId','compName','picUrl','mobile','email','tel','address','legalPerson','description','licenseUrl');
    public function __construct()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
             $this->user = $_SESSION['user'];
         }
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
        if ($accNumber && $param) {
            if ($this->verifyUser($accNumber)) {
                $data = $this->getCompanyInfo_byParam($accNumber,$param);
                $data = parent::formatResponse($data);
            } else {
                $data['status'] = 3;
                $data['msg']    = '与登录信息不符';
            }
        } else {
            $data['status'] = 2;
            $data['msg']    = '参数不集全';
        }
        return $data;
    }
    
    public function getCompanyInfo_byParam($accNumber,$param)
    {
        $data = array();
        switch ($param) {
            case    'center':
                $data = $this->getCompCenter_byCompId($accNumber);          //获得企业核心信息
                break;
            case 'recruited':
                $data = $this->getCompRecruited_byCompId($accNumber);       //获得已有的招聘信息
                break;
            case   'concern':
                $data = $this->getCompConcern_byCompId($accNumber,$param);  //获得企业关注的学生信息
                break;
            case 'concerned':
                $data = $this->getCompConcern_byCompId($accNumber,$param);  //获得关注企业的学生信息
                break;
            case      'base':
            default:
                $data = $this->getCompBase_byCompId($accNumber);            //获得企业基本信息
                break;
        }
        return $data;
    }
    
    /**
     * 通过企业号获得企业核心信息
     * @param unknown $accNumber
     */
    public function getCompCenter_byCompId($accNumber)
    {
        $table           = array($this->companyTab,$this->companyInfoTab);
        $arr             = $this->centerArr;
        $where['compId'] = $accNumber;
        return parent::fetchOne_byArrJoin($table,$arr,$where);
    }
    
    /**
     * 通过企业号获得企业基本信息
     * @param unknown $accNumber
     */
    public function getCompBase_byCompId($accNumber)
    {
        $table           = array($this->companyTab,$this->companyInfoTab);
        $arr             = $this->baseArr;
        $where['compId'] = $accNumber;
        return parent::fetchOne_byArrJoin($table,$arr,$where);
    }
    
    /**
     * 获得企业关注|被关注的学员核心信息
     * @param unknown $accNumber
     * @param unknown $param
     * @return multitype:NULL
     */
    public function getCompConcern_byCompId($accNumber,$param)
    {
        $data = array();
        $obj = new getStudentModel();
        $res = $obj->getConcernInfo_byType($accNumber,$param);           //获得关注|被关注学号
        if(count($res) > 0){
            $obj = new getStudentModel();
            foreach ($res as $val) {
                $data[] = $obj->getStuCenter($val["{$param}"]);
            }
        }
        return $data;
    }
    
}