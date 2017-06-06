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
    private $teacherStudentArr = array('stuId','name','sex','age','qq','wechat','dateinto','dateout','status','mobile','email','ls_assess');
    private $recommendArr      = array('id','stuId','dateinto');
    private $classArr          = array('classId','className','startClassTime','masterId','classType','endClassTime');
    
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
     * 获得登陆者信息
     */
    public function getUser()
    {
        return $this->user;
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
            case   'class':
                $data = $this->getTeacherClass($where);
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
        $where = " AND f.teacherId = s.teacherId AND f.teacherId = '{$accNumber}' ";
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
    public function getTeacherClass($where)
    {
        $data = array();
        
        //获得教师下的所有班级
        $arr             = $this->classArr;
        $table           = array($this->teacherClassTab,$this->classTab );
        $where['where2'] = " AND s.`classId` = f.`classId` ";
        $data            = parent::fetchAll_byArrJoin($table,$arr,$where);
        return $data;
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
     * 获得班级下所有的学生信息
     * return array
     */
    public function getClassStuInfo()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$classId   = $_LS['classId'];
        if ($accNumber && $classId) {
            if ($this->verifyUser($accNumber)) {                                    //与登录信息一致
                if ($this->verifyAccAndClass($accNumber,$classId)) {                //教师下存在该班级号
                    $data['info']   = $this->getStuInfo_byClassId($classId);        //通过班级号获得学生信息
                    $data['status'] = 0;
                    $data['msg']    = 'success';
                } else {
                    $data['status'] = 4;
                    $data['msg']    = '班级号不正确';
                }
            } else {
                $data['status'] = 3;
                $data['msg']    = '与登录信息不符';
            }
        } else {
            $data['status'] = 2;
            $data['msg']    = '传参不全';
        }
        return $data;
    }
    
    public function getStuInfo_byClassId($clssId)
    {
        $data             = array();
        $arr              = $this->teacherStudentArr;
        $where['classId'] = $clssId;
        $where['where2']  = ' AND s.`stuId` = f.`stuId`';
        $table            = array($this->teacherStudentTab,$this->teacherStudentInfoTab);
        $data             = parent::fetchAll_byArrJoin($table,$arr,$where);
        return $data;
    }
    /**
     * 查看该教师下是否有该班级号
     * @param string $accNumber 教师号
     * @param int $classId      班级号
     * @return boolean
     */
    public function verifyAccAndClass($accNumber,$classId)
    {
        $res                = false;
        $arr                = array('classId');
        $where['classId']   = $classId;
        $where['teacherId'] = $accNumber;
        $table              = $this->teacherClassTab;
        $res                = parent::fetchOne_byArr($table,$arr,$where);
        if(count($res) > 0){
            $res = true;
        }
        return $res;
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
        $where['status'] = 1;                               //必须是激活状态
        return parent::fetchOne_byArrJoin($table,$arr,$where);
    }
    
    /**
     * 获得该班主任下一个学员的简历信息
     * @return array
     */
    public function getMasterStuResumeInfo()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        @$stuId     = $_LS['stuId'];
        if ($accNumber && $stuId) {
            if ($this->verifyUser($accNumber)) {
                if ($this->verifyTeacherAndStuId($accNumber,$stuId)) {
                    $data = $this->getStuResume_byStuId($stuId);            //通过学号获得学员简历信息
                    $data = parent::formatResponse($data);                  //格式化结果集
                } else {
                    $data['status'] = 4;
                    $data['msg']    = '该教师下没有该学号学员信息';
                }
            } else {
                $data['status'] = 3;
                $data['msg']    = '账号与登录信息不符';
            }
        } else {
            $data['status'] = 2;
            $data['msg']    = '参数不集全';
        }
        return $data;
    }
    
    /**
     * 通过学号获得学员简历信息
     * @param string $stuId 学号
     * @return array
     */
    public function getStuResume_byStuId($stuId)
    {
        $obj = new getStudentModel();
        return $obj->getStuResume_byStuId($stuId);
    }
    
    /**
     * 验证该教师下是否有该学员
     * @param string $accNumber 教师号
     * @param string $stuId 学号
     * @return boolean
     */
    public function verifyTeacherAndStuId($accNumber,$stuId)
    {
        $res                = false;
        $resp               = array();
        $arr                = array('classId');
        $where['stuId']     = $stuId;
        $where['teacherId'] = $accNumber;
        $table              = array($this->teacherClassTab,$this->teacherStudentInfoTab);
        $resp               = parent::fetchOne_byArrJoin($table,$arr,$where);
        if (count($resp) > 0 ) {
            $res = true;
        } 
        return $res;
    }
}