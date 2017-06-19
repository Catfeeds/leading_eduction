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
        parent::ajaxReturn($data,'modify');
    }
    /**
     * 修改学生密码
     */
    public function setStuPass()
    {
        $obj  = new setStudentModel();
        $data = $obj->setStuPass();
        parent::ajaxReturn($data,'modify');;
    }
    /**
     * 修改学生基本信息
     */
    public function setStuBase()
    {
        $obj  = new setStudentModel();
        $data = $obj->setStuBase();
        parent::ajaxReturn($data,'modify');
    }
    
    public function setStuWork()
    {
        $obj  = new setStudentModel();
        $data = $obj->setStuWork();
        parent::ajaxReturn($data,'modify');
    }
    
    public function setStuProject()
    {
        $obj  = new setStudentModel();
        $data = $obj->setStuProject();
        parent::ajaxReturn($data,'modify');
    }
    
    public function setStuEducation()
    {
        $obj  = new setStudentModel();
        $data = $obj->setStuEducation();
        parent::ajaxReturn($data,'modify');
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
        parent::ajaxReturn($data,'modify');
    }
    
    public function addStuWork()
    {
        $obj  = new setStudentModel();
        $data = $obj->addStuWork();
        parent::ajaxReturn($data,'modify');
    }
    
    public function addStuProject()
    {
        $obj  = new setStudentModel();
        $data = $obj->addStuPorject();
        parent::ajaxReturn($data,'modify');
    }
    
    //获得学员简历信息
    public function getStuResume()
    {
        $obj  = new getStudentModel();
        $data = $obj->getStuResume();
        parent::ajaxReturn($data,'modify');
    }
    
    //投递简历
    public function sentResume()
    {
        $obj  = new setStudentModel();
        $data = $obj->sentResume();
        parent::ajaxReturn($data,'modify');
    }
    
    //查看投递简历记录
    public function getStuResumeLog()
    {
        $obj  = new getStudentModel();
        $data = $obj->getStuResumeLog();
        parent::ajaxReturn($data,'modify');
    }
    
    //上传头像
    public function uploadImg()
    {
        $obj  = new setStudentModel();
        $data = $obj->uploadImg();
        parent::ajaxReturn($data,'modify');
    }
    
}