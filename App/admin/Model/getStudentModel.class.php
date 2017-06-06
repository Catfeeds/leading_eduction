<?php
namespace App\admin\Model;

class getStudentModel extends infoModel
{
    private $user          = array();
    private $centerArr     = array('mobile','name','picUrl');
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
             $this->user = $_SESSION['user'];
         }
    }
    
    public function getArr($arr)
    {
        return $this->{$arr};
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
            if(($this->user['user_expTime'] + 60 * 60 * 6) > time()){
                $res = true;
            }
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
        $data   = array(); 
        @$stuId = $_LS['accNumber'];
        @$param = $_LS['param'];
        if($stuId && $param){
            if($this->verifyUser($stuId)){
                $data['info']   = $this->getStuInfo_byParam($stuId,$param);
                $data['status'] = 0;
                $data['msg']    = 'success';
            }else{
                $data['status'] = 2;
                $data['msg']    = '与登录者信息不符';
            }
        }else{
            $data['status'] = 1;
            $data['msg']    = '参数信息不全';
        }
        return $data;
    }
    
    /**
     * 根据不同的字段获得学生信息
     * @param string $stuId
     * @param string $param
     * @return array
     */
    public function getStuInfo_byParam($stuId,$param)
    {
        $data           = array();
        $where['stuId'] = $stuId;
        switch($param){
            case      'base'://基础数据
                $data = $this->getStuBase($stuId);
                break;
            case    'course'://课程信息
                $data = $this->getStuCourse($where);//没有完成,还需改善
                break;
            case   'project'://简历、作品或项目信息
                $data = $this->getStuProject($where);//完成
                break;
            case   'concern'://关注信息
                $data = $this->getStuConcern($stuId,$param);//完成
                break;
            case 'concerned'://被关注信息
                $data = $this->getStuConcern($stuId,$param);//完成
                break;
            case 'recommend': //推荐信息
                $data = $this->getStuRecommend($stuId);//完成
                break;
            case      'work':
                $data = $this->getStuWork($where);
                break;
            case 'education':
                $data = $this->getStuEducation($where);
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
    public function getStuBase($stuId)
    {
        $arr   = $this->baseArr; 
        $table = array('leading_student','leading_student_info');
        $where = " f.stuId = s.stuId AND s.stuId = {$stuId} ";
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
        $where = " f.stuId = s.stuId AND f.stuId = {$stuId} ";
        $data  = parent::fetchOne_byArrJoin($table,$arr,$where);
        return $data;
    }
    /**
     * 获得学生的课程信息
     * @param unknown $where
     */
    public function getStuCourse($where)
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
                $where_2['courseId']      = $courseId;
                $data["{$courseId}"]    = parent::fetchAll_byArr($table_2,$arr_2,$where_2);
            }
        }
        return $data;
    }
    
    /**
     * 获得学生项目信息
     * @param unknown $where
     * return array
     */
    public function getStuProject($where)
    {
        $data  = array();
        
        //获取学生参与的所有项目
        $arr   = $this->stuProjectArr;
        $table = 'student_project';
        $res   = parent::fetchAll_byArr($table,$arr,$where);
        
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
    public function getStuConcern($accNumber,$type)
    {
        $data               = array();
        $arr                = array("{$type}","conTime");
        $select             = trim(implode(' ',array_diff($this->concernArr,$arr)));
        $where["{$select}"] = $accNumber;
        $table              = 'concern';
        $res                = parent::fetchAll_byArr($table,$arr,$where);
        
        //获得企业详细信息
        if(count($res) > 0){
            $table_2 = 'leading_company';
            $arr_2   = $this->companyArr;
            foreach ($res as $val){
                $compId            = $val["{$type}"];
                $where_2['compId'] = $compId;
                $data[]            = parent::fetchOne_byArr($table_2,$arr_2,$where_2);
            }
        }
        return $data;
    }
    
    /**
     * 获得所推荐人的基本信息
     * @param string $accNumber 学号
     * @return array
     */
    public function getStuRecommend($accNumber)
    {
        $data                 = array();
        $arr                  = $this->recommendArr;
        $table                = 'recommend';
        $where['recommendId'] = $accNumber;
        $res                  = parent::fetchAll_byArr($table,$arr,$where);
        if(count($res) > 0){
            foreach ($res as $val){
                $data[] = array_merge($val,$this->getStuCenter($val['stuId']));
            }
        }
        return $data;
    }
    
    /**
     * 获得学生工作经验
     * @param array $where
     * @return array
     */
    public function getStuWork($where)
    {
        $data  = array();
        $arr   = $this->workArr;
        $table = 'student_work';
        $data  = parent::fetchAll_byArr($table,$arr,$where);
        return $data;
    }
    
    /**
     * 获得学员教育经验
     * @param array $where
     * @return array
     */
    public function getStuEducation($where)
    {
        $data  = array();
        $arr   = $this->educationArr;
        $table = 'student_education';
        $data  = parent::fetchAll_byArr($table,$arr,$where);
        return $data;
    }
}