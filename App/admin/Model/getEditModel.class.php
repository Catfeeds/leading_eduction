<?php
namespace App\admin\Model;

class getEditModel extends infoModel
{
    const PAGESIZE       = 8;                       //页容量
    const USEREXPTIME    = 21600;                   //登陆有效期，单位秒
    const VIDIOPICPAGESIZE = 
    private $user = array();
    //数据表
    private $staffInfoTab      = 'leading_staff_info';
    private $tempTab           = 'temp_register';
    private $stuTab            = 'leading_student';
    private $stuInfoTab        = 'leading_student_info';
    private $recomTab          = 'recommend';
    private $compTab           = 'leading_company';
    private $compInfoTab       = 'leading_company_info';
    private $teachTab          = 'leading_teacher';
    private $teachInfoTab      = 'leading_teacher_info';
    private $courseTab         = 'course';
    private $projectTab        = 'project';
    private $courseConTab      = 'course_content';
    private $classTab          = 'leading_class';
    private $teacherClassTab   = 'leading_class_teacher';
    private $courseProTab      = 'course_project';
    private $recommendTab      = 'recommend';
    private $carouselFigureTab = 'carousel_figure';
    private $tuitionTab        = 'tuition';
    private $vedioTab          = 'vedio';
    
    //表属性
    private $courseInfo      = array('courseName','description','status');
    private $classInfo       = array('className','courseId','masterId','startClassTime','endClassTime','classType','addressId','teacherId'); 
    private $projectInfo     = array('projectId','projectName','description','status','startTime','endTime','picUrl','url','people','type');
    private $tuitionInfo     = array('id','courseId','caseId','teaching','tuitionCase','tuitionMoney');
    private $vedioInfo       = array('vedioName','description','vedioUrl','author','status','courseId','secCourseId','dateinto');
    public function __construct()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
            if (($_SESSION['user']['user_expTime'] + self::USEREXPTIME) > time()){
                $this->user = $_SESSION['user'];
            } else {
                unset($_SESSION['user']);
            }
         }
    }
    
    public function verifyUser($accNumber)
    {
        $res = false;
        if (count($this->user) > 0 && isset($this->user['accNumber']) && ($this->user['accNumber'] == $accNumber)) {
            $res = true;
        }
        return $res;
    }
    
    public function getArr($arr)
    {
        return $this->{$arr};
    }
    public function getTable($table)
    {
        return $this->{$table};
    } 
    public function getUser()
    {
        return $this->user;
    }
    
    /**
      *获得信息
     */
    public function getEditInfo()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        @$param     = $_LS['param'];
        if ($accNumber && $param) {                                         //存在传参
            if ($this->verifyUser($accNumber)) {
                if ($this->user['caseId'] == 6) {                           //确认是编辑员
                    $data = $this->getInfo_byParam($accNumber,$param);      //根据param获得信息
                } else {
                    $data['status'] = 4;
                    $data['msg']    = '没有相关权限';
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
    * 根据param参数获得信息
    * @date: 2017年6月13日 上午11:39:44
    * @author: lenovo2013
    * @param: $accNumber string 身份标识符
    * @param: $param    string 信息区别参数
    * @return:array
   **/
    public function getInfo_byParam($accNumber,$param)
    {
        $data = array();
        switch ($param) {
            case 'base':
                $data = $this->getEditBase($accNumber);         //获得编辑员基本信息
                break;
        }
        return $data;
    }
    /**
     *获得编辑员基本信息
     */
    public function getEditBase($accNumber)
    {
        $data  = array();
        $arr   = array('mobile','name','email');
        $table = $this->getArr('staffInfoTab');
        $where = array('accNumber' => $accNumber);
        $data  = parent::fetchOne_byArr($table,$arr,$where);
        return $data;
    }
    
    /**
     * 查看注册信息
     * @return array
     */
    public function showRegisterInfo()
    {
        global $_LS;
        $data = array();
        //获得提交的参数
        @$accNumber = $_LS['accNumber'];
        @$page      = intval($_LS['page']);
        $page       = empty($page)?1:$page;
        @$pageSize  = intval($_LS['pageSize']);
        $pageSize   = empty($pageSize)?self::PAGESIZE:$pageSize;
        @$status    = intval($_LS['status']);
        $status     = empty($status)?0:$status;
        if ($accNumber) {
            if ($this->verifyUser($accNumber)) {
                $arr   = array('name','mobile','caseId','tmpId','id');                  //需要查询的信息
                $where = array('status' => $status,'where2' => ' ORDER BY id DESC ');   //查询条件
                $table = $this->getTable('tempTab');
                $data['info']  = page($table,$arr,$where,$page,$pageSize);
                $data  = parent::formatResponse($data['info']);
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 显示所有的大课信息
     */
    public function showCourses()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if ($accNumber) {
            if ($this->verifyUser($accNumber)) {                    //存在身份标识符且有效
                $res['info'] = $this->showCourseInfo();
                $data        = parent::formatResponse($res['info']);
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 获得所有的课程信息
     */
    public function showCourseInfo()
    {
        $table = $this->courseTab;
        $arr   = array('*');
        $where = array();
        return parent::fetchAll_byArr($table,$arr,$where);
    }
    
    /**
     * 显示一个大课程下的所有子课程内容信息
     */
    public function showSecCourse()
    {
        global $_LS;
        $data = array();
        //获得post中的参数
        @$accNumber = $_LS['accNumber'];
        @$courseId  = $_LS['courseId'];
        @$page      = empty(intval($_LS['page']))?1:intval($_LS['page']);
        @$pageSize  = empty(intval($_LS['pageSize']))?self::PAGESIZE:intval($_LS['pageSize']);
        if ($accNumber && $courseId) {                      
            if ($this->verifyUser($accNumber)) {                                            //身份标识符存在且有效
                $arr   = array('id','stageId','content','focus');
                $where = array('courseId' => $courseId);
                $table = $this->courseConTab;
                $data['info']  = page($table,$arr,$where,$page,$pageSize);                  //获得页容量信息
                $data  = parent::formatResponse($data['info']);
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 展示一个课程下的所有班级
     * @return array
     */
    public function showClass()
    {
        global $_LS;
        $data = array();
        //获得post中的参数
        @$accNumber = $_LS['accNumber'];
        @$courseId  = intval($_LS['courseId']);
        @$page      = empty(intval($_LS['page']))?1:intval($_LS['page']);                       //当前页
        @$pageSize  = empty(intval($_LS['pageSize']))?self::PAGESIZE:intval($_LS['pageSize']);  //页容量
        if ($accNumber && $courseId) {
            if ($this->verifyUser($accNumber)) {                                                //身份标识符存在且有效
                $data['info'] = $this->getClassInfo_byCourseId($courseId,$page,$pageSize);      //根据课程号获得其下的班级信息
                $data         = parent::formatResponse($data['info']);                          //格式化结果集
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 根据课程号获得其下的所有班级信息
     * @param int $courseId     //课程号  
     * @param int $page         //当前页
     * @param int $pageSize     //页容量
     * @return array
     */
    public function getClassInfo_byCourseId($courseId,$page,$pageSize)
    {
        $table = array($this->classTab,$this->teachTab);
        $where = array('courseId' => $courseId,'where2' => " AND s.masterId = f.teacherId ORDER BY s.startClassTime DESC ");
        $arr   = array('classId','className','masterId','name','startClasstTime','endClassTime','classType','addressId');
        return page($table,$arr,$where,$page,$pageSize);
    }
    
    public function showClassTeacher()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if ($accNumber) {
           if ($this->verifyUser($accNumber)) {
               if (count($_LS) == 2) {
                   @$classId   = $_LS['classId'];
                   @$teacherId = $_LS['teacherId'];
                   if ($classId || $teacherId) {
                        if ($classId) {                                 //班级
                            $res['info'] = $this->getTeacherInfo_byClassId($classId);
                        } else {                                        //教师号
                            $res['info'] = $this->getClassInfo_byTeacherId($teacherId);
                        }
                        $data = parent::formatResponse($res['info']);
                   } else {
                       $data['status'] = 5;
                   }
               } else {
                   $data['status'] = 5;
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
     * 根据班级号，获得其所有的任课老师
     * @param unknown $classId
     */
    public function getTeacherInfo_byClassId($classId)
    {
        $where = array('classId' => $classId,'where2' => ' AND s.`teacherId` = f.`teacherId`');
        $table = array($this->teacherClassTab,$this->teachTab);
        $arr   = array('teacherId','name','mobile');
        return parent::fetchAll_byArrJoin($table,$arr,$where);
    }
    
    /**
     * 根据教师号获得其下的所有班级信息
     * @param unknown $teacherId
     */
    public function getClassInfo_byTeacherId($teacherId)
    {
        $where = array('teahcerId' => $teacherId ,'where2' => ' AND s.`classId` = f.`classId` ORDER BY f.`classId` DESC');
        $table = array($this->teacherClassTab,$this->classTab);
        $arr   = array('classId','className','masterId','classType','startClassTime');
        return page($table,$arr,$where);
    }
    
    /**
     * 获得一个班主任其下的所有班级信息
     */
    public function showMasterClass()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$masterId  = $_LS['masterId'];
        if ($accNumber && $masterId) {
            if ($this->verifyUser($accNumber)) {                            //身份标识符存在且有效
                $res['info'] = $this->getClassInfo_byMasterId($masterId);   //根据班主任号获得所有信息
                $data        = parent::formatResponse($res['info']);        //格式化结果集
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 根据班主任号获得其下的所有班级信息
     * @param string $masterId
     */
    public function getClassInfo_byMasterId($masterId)
    {
        $where = array('masterId' => $masterId,'where2' => ' ORDER BY startClassTime DESC ');
        $arr   = array('*');
        $table = $this->classTab;
        return page($table,$arr,$where);
    }
    
    public function showCourseTeacher()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$courseId  = $_LS['courseId'];
        if ($accNumber && $courseId) {
            if ($this->verifyUser($accNumber)) {
                @$page       = empty(intval($_LS['page']))?1:intval($_LS['page']);
                $pageSize    = self::PAGESIZE;
                $res['info'] = $this->getTeacherInfo_byCourseId($courseId,$page,$pageSize);
                $data        = parent::formatResponse($res['info']);
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    
    public function getTeacherInfo_byCourseId($courseId,$page,$pageSize)
    {
        $where = array('courseId' => $courseId,'where2' => ' AND s.teacherId = f.teacherId ');
        $arr   = array('teacherId','teacherName','mobile','dateinto');
        $table = array($this->courseProTab,$this->teachTab);
        return page($table,$arr,$where,$page,$pageSize);
    }
    
    
    public function showPorjects()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$page      = empty(intval($_LS['page']))?1:intval($_LS['page']);
        @$pageSize  = empty(intval($_LS['pageSize']))?self::PAGESIZE:intval($_LS['pageSize']);
        @$status    = intval($_LS['status']);
        if ($accNumber && $status) {
            if ($this->verifyUser($accNumber)) {
                if ($status == 1 || $status == 2) {
                    $where['status'] = $status;
                }
                $where['where2'] = ' ORDER BY startTime';
                $arr             = array('*');
                $table           = $this->projectTab;
                $res['info']     = page($table,$arr,$where,$page,$page);
                $data            = parent::formatResponse($res['info']);
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 获得所有推荐者信息
     */
    public function getRecommend()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if ($accNumber) {
            if ($this->verifyUser($accNumber)) {                                    //身份标识法存在且有效
                @$page     = empty(intval($_LS['page']))?1:intval($_LS['page']);
                @$pageSize = empty(intval($_LS['pageSize']))?self::PAGESIZE:intval($_LS['pageSize']);
                $table     = $this->recommendTab;                                   //推荐表
                $arr       = array('*');    
                $where     = array('where2' => ' ORDER BY dateinto DESC ');         //按推荐时间排序
                $res['info'] = page($table,$arr,$where,$page,$pageSize);            //获得所有分页信息
                $count     = count($res['info']);
                if ($count > 0) {
                    for ($i = 0;$i < $count-1;$i++) {
                        if (isset($res['info'][$i]['recommendId'])) {               //获得推荐者姓名
                            $resp = $this->getName($res['info'][$i]['recommendId']);
                            $res['info'][$i]['recommendName'] = $resp['name'];
                        }
                        if (isset($res['info'][$i]['stuId'])) {                             //获得被推荐者姓名
                            $resp = $this->getName($res['info'][$i]['stuId']);
                            $res['info'][$i]['name'] = $resp['name'];
                        }
                    }
                    $data = parent::formatResponse($res['info']);                   //格式化结果集
                }
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    
    public function getName($accNumber)
    {
        $obj = new doActionModel();
        $caseId = $obj->verifyCaseId($accNumber);
        if ($caseId) {
            $table = $obj->getTable_byCaseId($caseId);              //获得数据表
        }
        if ($table) {
            $key = $obj->getWhere($table);                          //获得添加key值
        }
        if ($key) {
            $where["{$key}"] = $accNumber;                          //组成条件where
        }
        $arr = array('id','name');                                  //要查询的字段
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    public function getBaseInfo()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        @$userId    = $_LS['userId'];
        @$caseId    = intval($_LS['caseId']);
        if ($accNumber && $userId && $caseId) {
            if ($this->verifyUser($accNumber)) {
                $res['info'] = $this->getBaseInfos($userId,$caseId);
                $data        = parent::formatResponse($res['info']);
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    public function getBaseInfos($userId,$caseId)
    {
        $data  = array();
        $obj   = new checkModel(); 
        //$table = $obj->getTable_byKey($caseId);
        switch ($caseId) 
        {
            case 1:
                $obj_1 = new getStudentModel();
                $data  = $obj_1->getStuBase_byStuId($userId);
                break;
            case 2:
            case 3:
                $obj_1 = new getTeacherModel();
                $data  = $obj_1->getTeacherBase($userId);
                break;
            case 4:
            case 5:
            case 6:
            case 7:
                $data  = $this->getEditBase($userId);
                break;
            case 9:
                $obj_1 = new getCompanyModel();
                $where = array('compId' => $userId);
                $data  = $obj_1->getCompBase_byCompId($where);
                break;
            case 8:
            default:
                @$obj_1 = new getTempRegister();
                break;
        }
        return $data;
    }
    
    /**
     * 获得报名试听信息
     * @return array
     */
    public function getRegisterInfo()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$param     = $_LS['param'];
        @$page      = intval($_LS['page']);
        $page       = empty($page)?1:$page;
        @$pageSize  = intval($_LS['pageSize']);
        $pageSize   = empty($pageSize)?self::PAGESIZE:$pageSize;
        
        if ($accNumber && $param) {
            if ($this->verifyUser($accNumber)) {
                $res['info'] = $this->getRInfo_byParam($param,$page,$pageSize);
                $data        = parent::formatResponse($res['info']);
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 通过param值获得报名表中的信息
     * @param string $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getRInfo_byParam($param,$page,$pageSize)
    {
        $data = array();
        switch ($param) {
            case 'sign':
                $data = $this->getRSignInfo($page,$pageSize);
                break;
        }
        return $data;
    }
    
    public function getRSignInfo($page,$pageSize)
    {
        $table = 'leading_sign';
        $where = array();
        $arr   = array('id','name','mobile','qq','signTime','listenTime');
        return page($table,$arr,$where,$page,$pageSize);
    }
    
    public function showTuition()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$courseId  = $_LS['courseId'];
        if ($accNumber && $courseId) {
            if ($this->verifyUser($accNumber)) {
                $data['info'] = $this->getTuitions($courseId);                  //通过课程号获得其下的所有的学费
                $data         = parent::formatResponse($data['info']);          //格式化信息
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    public function getTuitions($courseId) 
    {
        $arr   = array('id','caseId','teaching','tuitionCase','tuitionMoney');
        $where = array('courseId' => $courseId);
        $table = $this->getTable('tuitionTab');
        return parent::fetchAll_byArr($table,$arr,$where);
    }
    
    public function getVedioInfosByCourseId()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$courseId  = intval($_LS['courseId']);
        @$page      = intval($_LS['page']);
        $page       = empty($page)?1:$page;
        @$pageSize  = intval($_LS['pageSize']);
        $pageSize   = empty($pageSize)?self::VIDIOPICPAGESIZE:$pageSize;
        return $data;
    }
    
    
    
    
}