<?php
namespace App\admin\Model;

class setTeacherModel extends infoModel
{
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
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if (isset($accNumber) && $this->verifyUser($accNumber)) {
            //更改信息
            $arr = array_diff($_LS,array("accNumber"=>$accNumber));                          //更改信息
            if (count($arr) > 0) {
                if (empty(array_diff_key($arr,array_flip($this->baseArr)))) {
                    $table              = array($this->teacherTab,$this->teacherInfoTab);    //联合修改表名
                    $where['teacherId'] = $accNumber;                                        //修改条件
                    $res                = parent::update($table,$arr,$where);
                    $data               = parent::formatResponse($res);
                } else {
                    $data['status'] = 4;
                    $data['msg']    = '修改信息不安全';
                }
            } else {
                $data['status'] = 3;
                $data['msg']    = '没有要修改的信息';
            }
        } else {
            $data['status'] = 2;
            $data['msg']    = '账号为空或与登陆信息不符';
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
            $obj  = new doActionModel();
            $data = $obj->setPass($_LS,$this->teacherTab,$this->user);
        }else{
            $data['status'] = 2;
            $data['msg']    = '账号为空或与登陆信息不符';
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
                $data['msg']    = '与登录信息不符';
            }
        } else {
            $data['status'] = 2;
            $data['msg']    = '参数不集全';
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
    
}