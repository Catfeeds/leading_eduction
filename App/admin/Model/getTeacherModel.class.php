<?php
namespace App\admin\Model;

class getTeacherModel extends infoModel
{
    private $user = array();
    
    //表
    private $teacherTab            = 'leading_teacher';
    private $teacherInfoTab        = 'leading_teacher_info';
    private $teacherClassTab       = 'leading_class_teacher';
    private $classTab              = 'leading_class';
    private $teacherCourseTab      = 'teaching_course';
    private $courseContentTab      = 'course_content';
    private $teacherStudentTab     = 'leading_student';
    private $teacherStudentInfoTab = 'leading_student_info';
    //信息数组
    private $baseArr           = array('status','name','picUrl','title','description','mobile','email');
    private $secCourseArr      = array('id','stageId','content');
    private $teacherStudentArr = array('stuId','name','sex','age');
    private $recommendArr      = array('id','stuId','dateinto');
    
    public function __construct()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
             $this->user = $_SESSION['user'];
         }
    }
    
    /**
     * 验证账号信息
     * @param string $accNumber 教师号
     * @return boolean
     */
    public function verifyUser($accNumber)
    {
        $res = false;
        if(isset($this->user) && isset($this->user['teacherId']) && $this->user['teacherId'] == $accNumber){
            if(($this->user['user_expTime'] + 60 * 60 * 6) > time()){
                $res = true;
            }
        }
        return $res;
    }
    
    /**
     * 获得教师信息
     */
    public function getTeacherInfo()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        @$param     = $_LS['param'];
        if (isset($accNumber) && isset($param)) {                           //如果存在传参
            if ($this->verifyUser($accNumber)) {                            //如果符合登陆信息
                $data['info']   = $this->getInfo_byParam($accNumber,$param);//根据param获得信息
                $data['status'] = 0;
                $data['msg']    = 'success';
            } else{
                $data['status'] = 3;
                $data['msg']    = '与登录信息不相符';
            }
        } else {
            $data['status'] = 2;
            $data['msg']    = '传参不全';
        }
        return $data;
    }
    
    /**
     * 由param获得教师下的信息
     * @param stirng $accNumber 教师号
     * @param string $param 信息标签
     * @return array
     */
    public function getInfo_byParam($accNumber,$param)
    {
        $data               = array();
        $where['teacherId'] = $accNumber;
        switch ($param) {
            case    'course':
                $data = $this->getTeacherCourse($where);
                break;
            case   'student':
                $data = $this->getTeacherStudent($where);
                break;
            case 'recommend':
                $data = $this->getTeacherRecommend($accNumber);
                break;
            case      'base':
            default         :
                $data = $this->getTeacherBase($accNumber);
                break;
        }
        return $data;
    }
    
    /**
     * 获得教师的基本信息
     * @param string $accNumber 教师号
     * @return array
     */
    public function getTeacherBase($accNumber)
    {
        $data = array();
        $arr  = $this->baseArr;
        $table = array($this->teacherTab,$this->teacherInfoTab);
        $where = " f.teacherId = s.teacherId AND f.teacherId = '{$accNumber}' ";
        $data  = parent::fetchOne_byArrJoin($table,$arr,$where);
        return $data;
    }
    
    /**
     * 获得教师下的所有课程信息
     * @param unknown $accNumber
     * @return multitype:NULL
     */
    public function getTeacherCourse($where)
    {
        $data = array();
        //获得教师下所有教的课程
        $arr                = array('courseId');
        $table              = $this->teacherCourseTab;
        $res                = parent::fetchAll_byArr($table,$arr,$where);
        
        //获得课程详细内容信息
        $count   = count(array_unique($res));
        $table_2 = $this->courseContentTab;
        $arr_2   = $this->secCourseArr;
        if($count > 0){
            for($i = 0;$i<$count;$i++){
                $where_2['courseId']            = $res[$i]['courseId'];
                $data["{$res[$i]['courseId']}"] = parent::fetchAll_byArr($table_2,$arr_2,$where_2); 
            }
        }
        return $data;
    }
    
    /**
     * 获得教师下的所有学生信息
     * @param array $where
     * @return array
     */
    public function getTeacherStudent($where)
    {
        $data = array();
        
        //获得教师下的所有班级
        $arr             = array('classId','className');
        $table           = array($this->teacherClassTab,$this->classTab );
        $where['where2'] = " AND s.`classId` = f.`classId` ";
        $res             = parent::fetchAll_byArrJoin($table,$arr,$where);
        
        //获得班级下的所有学生信息
        $count = count(array_unique($res));
        if($count > 0){
            
            for($i = 0;$i < $count;$i++){
                $resp = $this->getStuInfo_byClassId($res[$i]['classId']);
                $data["{$res[$i]['classId']}"] = array_merge($res[$i],$resp);
            }
        }
        return $data;
    }
    /**
     * 获得一个班级下的所有学生信息
     * @param int $classId 班级号
     * return array
    */
    public function getStuInfo_byClassId($classId)
    {
        $table = array($this->teacherStudentTab,$this->teacherStudentInfoTab);
        $arr   = $this->teacherStudentArr;
        $where['where2'] = " AND s.`stuId` = f.`stuId` AND f.`classId` = {$classId}";
        return parent::fetchAll_byArrJoin($table,$arr,$where);
    }
    
    /**
     * 获得教师推荐的学员信息
     * @param string $accNumber 教师号
     * return array
     */
    public function getTeacherRecommend($accNumber)
    {
        $data                 = array();
        $arr                  = $this->recommendArr;
        $table                = 'recommend';
        $where['recommendId'] = $accNumber;
        $res                  = parent::fetchAll_byArr($table,$arr,$where);
        if(count($res) > 0){
            foreach ($res as $val){
                $data[] = array_merge($val,$this->getStuBase($val['stuId']));
            }
        }
        return $data;
    }
    
    /**
     * 通过学号获得学员信息
     * @param string $stuId
     * return array
     */
    public function getStuBase($stuId)
    {
        $table           = array($this->teacherStudentTab,$this->teacherStudentInfoTab);
        $arr             = $this->teacherStudentArr;
        $where['stuId']  = $stuId;
        $where['status'] = 1;
        return parent::fetchOne_byArrJoin($table,$arr,$where);
    }
    
}