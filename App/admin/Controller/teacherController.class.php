<?php
namespace App\admin\Controller;
use App\admin\Model\getTeacherModel;
use App\admin\Model\setTeacherModel;
class teacherController extends baseController
{
    
    
    public function getTeacherInfo()
    {
        $obj  = new getTeacherModel();
        $data = $obj->getTeacherInfo();
        parent::ajaxReturn($data,'modify');
    }
    
    //修改教师基本信息
    public function setTeacherBase()
    {
        $obj  = new setTeacherModel();
        $data = $obj->setTeacherBase();
        parent::ajaxReturn($data,'modify');
    }
    
    //修改教师密码
    public function setTeacherPass()
    {
        $obj  = new setTeacherModel();
        $data = $obj->setTeacherPass();
        parent::ajaxReturn($data,'modify');
    }
    
    //获得一个班级下所有的学生信息
    public function getClassStuInfo()
    {
        $obj  = new getTeacherModel();
        $data = $obj->getClassStuInfo();
        parent::ajaxReturn($data,'modify');
    }
    
    //班主任查看一个学生的简历信息
    public function getMasterStuResumeInfo()
    {
        $obj  = new getTeacherModel();
        $data = $obj->getMasterStuResumeInfo();
        parent::ajaxReturn($data,'modify');
    }
    
    //班主任更改学生评价
    public function setStuAssess()
    {
        $obj  = new setTeacherModel();
        $data = $obj->setStuAssess();
        parent::ajaxReturn($data,'modify');
    }
    //上传头像
    public function uploadImg()
    {
        $obj  = new setTeacherModel();
        $data = $obj->uploadImg();
        parent::ajaxReturn($data,'modify');
    }
    public function getTeacherClass()
    {
        $obj  = new getTeacherModel();
        $data = $obj->getTeacherClass();
        parent::ajaxReturn($data,'modify');
    }
}