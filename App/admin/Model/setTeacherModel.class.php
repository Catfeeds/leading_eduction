<?php
namespace App\admin\Model;

class setTeacherModel extends infoModel
{
    const DESTINATION              = './static/admin/images/uploads/image_149/';
    
    private $user                  = array();
    //表名
    private $teacherTab            = 'leading_teacher';
    private $teacherInfoTab        = 'leading_teacher_info';
    private $teacherStudentInfoTab = 'leading_student_info';
    //数据数组
    private $baseArr               = array('description','name','age','sex','mobile','email');
    
    
    public function __construct()
    {
        $obj        = new getTeacherModel();
        $this->user = $obj->getUser();
    }
    
    public function verifyUser($accNumber)
    {
        $obj = new getTeacherModel();
        return $obj->verifyUser($accNumber);
    }
    
    /**
     * 修改教师基本信息
     * @return array
     */
    public function setTeacherBase()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        if (isset($accNumber)) {
            if ($this->verifyUser($accNumber)) {
                $table = array($this->teacherTab,$this->teacherInfoTab);
                $obj   = new doActionModel();
                $data  = $obj->setObjectBase($_LS,$this->baseArr,$table,array("teacherId"=>$accNumber));     //修改基本信息
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 修改教师密码
     */
    public function setTeacherPass()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        if(!empty($accNumber) && $this->verifyUser($accNumber)){
            $obj   = new doActionModel();
            $where = array("teacherId"=>$accNumber);
            $data  = $obj->setPass($_LS,$this->teacherTab,$this->user,$where);
        }else{
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 班主任修改学生评价
     * @return array
     */
    public function setStuAssess()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$stuId     = $_LS['stuId'];
        @$ls_assess = $_LS['ls_assess'];
        if ($accNumber && $stuId && $ls_assess) {                               //post中参数集全
            if ($this->verifyUser($accNumber)) {                                //符合登陆信息
                if ($this->verifyTeacherAndStuId($accNumber,$stuId)) {          //验证该教师下是否有该学员
                    $data = $this->setStuAssess_byStuId($stuId,$ls_assess);     //修改评价
                    $data = parent::formatResponse($data);                      //格式化结果集
                } else {
                    $data['status'] = 4;
                    $data['msg']    = '班级中没有改学生';
                }
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    //验证该教师下是否有该学员
    public function verifyTeacherAndStuId($accNumber,$stuId)
    {
        $obj = new getTeacherModel();
        return $obj->verifyTeacherAndStuId($accNumber,$stuId);
    }

    //通过学号修改学生评价
    public function setStuAssess_byStuId($stuId,$ls_assess)
    {
        $where['stuId']   = $stuId;
        $arr['ls_assess'] = $ls_assess;
        $talbe            = $this->teacherStudentInfoTab;
        return parent::update($talbe,$arr,$where);
    }
    
    //上传头像
    public function uploadImg()
    {
        $data = array();
        @$teacherId  = $this->user['teacherId'];
        if (!empty($teacherId)) {
            $table = $this->teacherInfoTab;
            $where = array('teacherId' => $teacherId);
            $obj = new doActionModel();
            $data = $obj->uploadPic($table,$where,self::DESTINATION);
        } else {
            $data['status'] = 3;
        }
        return $data;
    }
    
}