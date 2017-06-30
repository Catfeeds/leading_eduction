<?php
namespace App\admin\Controller;
use App\admin\Model\getStudentModel;
use App\admin\Model\setStudentModel;
use framework\libs\core\VIEW;
class studentController extends baseController
{
    
    /**
     * 获得学生信息
     */
    public function getStuInfo()
    {
        $obj  = new getStudentModel();
        $data = $obj->getStuInfo();
        parent::ajaxReturn($data);
    }
    /**
     * 修改学生密码
     */
    public function setStuPass()
    {
        $obj  = new setStudentModel();
        $data = $obj->setStuPass();
        parent::ajaxReturn($data);;
    }
    /**
     * 修改学生基本信息
     */
    public function setStuBase()
    {
        $obj  = new setStudentModel();
        $data = $obj->setStuBase();
        parent::ajaxReturn($data);
    }
    
    public function setStuWork()
    {
        $obj  = new setStudentModel();
        $data = $obj->setStuWork();
        parent::ajaxReturn($data);
    }
    
    public function setStuProject()
    {
        $obj  = new setStudentModel();
        $data = $obj->setStuProject();
        parent::ajaxReturn($data);
    }
    
    public function setStuEducation()
    {
        $obj  = new setStudentModel();
        $data = $obj->setStuEducation();
        parent::ajaxReturn($data);
    }
    
    public function testInsert()
    {
        $obj  = new setStudentModel();
        $data = $obj->testInsert();
        parent::ajaxReturn($data);
    }
    
    public function addStuEducation()
    {
        $obj  = new setStudentModel();
        $data = $obj->addStuEducation();
        parent::ajaxReturn($data);
    }
    
    public function addStuWork()
    {
        $obj  = new setStudentModel();
        $data = $obj->addStuWork();
        parent::ajaxReturn($data);
    }
    
    public function addStuProject()
    {
        $obj  = new setStudentModel();
        $data = $obj->addStuPorject();
        parent::ajaxReturn($data);
    }
    
    //获得学员简历信息
    public function getStuResume()
    {
        $obj  = new getStudentModel();
        $data = $obj->getStuResume();
        parent::ajaxReturn($data);
    }
    
    //投递简历
    public function sentResume()
    {
        $obj  = new setStudentModel();
        $data = $obj->sentResume();
        parent::ajaxReturn($data);
    }
    
    //查看投递简历记录
    public function getStuResumeLog()
    {
        $obj  = new getStudentModel();
        $data = $obj->getStuResumeLog();
        parent::ajaxReturn($data);
    }
    
    //上传头像
    public function uploadImg()
    {
        $obj  = new setStudentModel();
        $data = $obj->uploadImg();
        parent::ajaxReturn($data);
    }
    
}