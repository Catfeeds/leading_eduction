<?php
namespace App\admin\Controller;
use App\admin\Model\getEditModel;
use App\admin\Model\setEditModel;
class editController extends baseController
{
    /**
     * 获取信息
     */
    public function getEditInfo()
    {
        $obj  = new getEditModel();
        $data = $obj->getEditInfo();
        parent::ajaxReturn($data,'modify');
    }
    
    /**
     * 处理临时注册信息
     */
    public function handleTempInfo()
    {
        $obj  = new setEditModel();
        $data = $obj->handleTempInfo();
        parent::ajaxReturn($data,'modify');
    }
    /**
     * 获得注册信息
     */
    public function showRegisterInfo()
    {
        $obj  = new getEditModel();
        $data = $obj->showRegisterInfo();
        parent::ajaxReturn($data,'modify');
    }
    
    public function modifyCompStatus()
    {
        $obj  = new setEditModel();
        $data = $obj->modifyCompStatus();
        parent::ajaxReturn($data,'modify');
    }
    //显示所有的大课信息
    public function showCourses()
    {
        $obj  = new getEditModel();
        $data = $obj->showCourses();
        parent::ajaxReturn($data,'modify');
    }
    //修改课程信息
    public function modifyCourseInfo()
    {
        $obj  = new setEditModel();
        $data = $obj->modifyCourseInfo();
        parent::ajaxReturn($data,'modify');
    }
    
    //添加大课课程信息
    public function addCourses()
    {
        $obj  = new setEditModel();
        $data = $obj->addCourses();
        parent::ajaxReturn($data,'modify');
    }
    
    //显示所有子课程内容信息
    public function showSecCourse()
    {
        $obj  = new getEditModel();
        $data = $obj->showSecCourse();
        parent::ajaxReturn($data,'modify');
    }
    //修改子课程信息
    public function modifySecCourseInfo()
    {
        $obj  = new setEditModel();
        $data = $obj->modifySecCourseInfo();
        parent::ajaxReturn($data,'modify');
    }
    //添加子课程信息
    public function addSecCourse()
    {
        $obj  = new setEditModel();
        $data = $obj->addSecCourse();
        parent::ajaxReturn($data,'modify');
    }
    
    //修改编辑员密码
    public function setStaffPass()
    {
        $obj  = new setEditModel();
        $data = $obj->setStaffPass();
        parent::ajaxReturn($data,'modify');
    }
    
    //展示一个课程下的所有班级
    public function showClass()
    {
        $obj  = new getEditModel();
        $data = $obj->showClass();
        parent::ajaxReturn($data,'modify');
    }
    //添加班级
    public function addClass()
    {
        $obj  = new setEditModel();
        $data = $obj->addClass();
        parent::ajaxReturn($data,'modify');
    }
    //添加班级课程教师
    public function addClassTeacher()
    {
        $obj  = new setEditModel();
        $data = $obj->addClassTeacher();
        parent::ajaxReturn($data,'modify');
    }
    
    //修改一个班级信息
    public function modifyClass()
    {
        $obj  = new setEditModel();
        $data = $obj->modifyClass();
        parent::ajaxReturn($data,'modify');
    }
    
    public function showClassTeacher()
    {
        $obj  = new getEditModel();
        $data = $obj->showClassTeacher();
        parent::ajaxReturn($data,'modify');
    }
    
    //展示一个班主任下所有的课程
    public function showMasterClass()
    {
        $obj  = new getEditModel();
        $data = $obj->showMasterClass();
        parent::ajaxReturn($data,'modify');
    }
    
    //新增一个项目
    public function addProject()
    {
        $obj  = new setEditModel();
        $data = $obj->addProject();
        parent::ajaxReturn($data,'modify');
    }
    
    //展示一个课程下的所有教师
    public function showCourseTeacher()
    {
        $obj  = new getEditModel();
        $data = $obj->showCourseTeacher();
        parent::ajaxReturn($data,'modify');
    }
    
    public function addProjectTeacher()
    {
        $obj  = new setEditModel();
        $data = $obj->addProjectTeacher();
        parent::ajaxReturn($data,'modify');
    }
    //展示所有的项目
    public function showPorjects()
    {
        $obj  = new getEditModel();
        $data = $obj->showPorjects();
        parent::ajaxReturn($data,'modify');
    }
    
    public function getRecommend()
    {
        $obj  = new getEditModel();
        $data = $obj->getRecommend();
        parent::ajaxReturn($data,'modify');
    }
    
    public function getBaseInfo()
    {
        $obj  = new getEditModel();
        $data = $obj->getBaseInfo();
        parent::ajaxReturn($data,'modify');
    }
    
    public function getRegisterInfo()
    {
        $obj  = new getEditModel();
        $data = $obj->getRegisterInfo();
        parent::ajaxReturn($data,'modify');
    }
    public function addPhotoFigure()
    {
        $obj  = new setEditModel();
        $data = $obj->addPhotoFigure();
        parent::ajaxReturn($data,'modify');
    }
    
    public function uploadPhoto()
    {
        $obj  = new setEditModel();
        $data = $obj->uploadPhoto();
        parent::ajaxReturn($data,'modify');
    }
    
    public function modifyPhotoStatus()
    {
        $obj  = new setEditModel();
        $data = $obj->modifyPhotoStatus();
        parent::ajaxReturn($data,'modify');
    }
    
    public function addTuition()
    {
        $obj  = new setEditModel();
        $data = $obj->addTuition();
        parent::ajaxReturn($data,'modify');
    }
    
    public function showTuition()
    {
        $obj  = new getEditModel();
        $data = $obj->showTuition();
        parent::ajaxReturn($data,'modify');
    }
    
    public function modifyTuitonById()
    {
        $obj  = new setEditModel();
        $data = $obj->modifyTuitonById();
        parent::ajaxReturn($data,'modify');
    }
    
    //添加视频信息
    public function addVedioInfo()
    {
        $obj  = new setEditModel();
        $data = $obj->addVedioInfo();
        parent::ajaxReturn($data,'modify');
    }
    
    //为视频添加图片
    public function addVedioPicUrl()
    {
        $obj  = new setEditModel();
        $data = $obj->addVedioPicUrl();
        parent::ajaxReturn($data,'modify');
    }
}