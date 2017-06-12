<?php
namespace App\admin\Controller;
use App\admin\Model\getCompanyModel;
use App\admin\Model\setCompanyModel;

class companyController extends baseController
{
    public function getCompanyInfo()
    {
        $obj  = new getCompanyModel();
        $data = $obj->getCompanyInfo();
        parent::ajaxReturn($data,'modify');
    }
    
    //添加一条招聘信息
    public function addARecruitedInfo()
    {
        $obj  = new setCompanyModel();
        $data = $obj->addARecruitedInfo();
        parent::ajaxReturn($data,'modify');
    }
    
    //修改一条招聘信息
    public function setResumeInfo()
    {
        $obj  = new setCompanyModel();
        $data = $obj->setResumeInfo();
        parent::ajaxReturn($data,'modify');
    }
    
    //修改企业登录密码
    public function setCompPass()
    {
        $obj  = new setCompanyModel();
        $data = $obj->setCompPass();
        parent::ajaxReturn($data,'modify');
    }
    
    //修改公司基本信息
    public function setCompBase()
    {
        $obj  = new setCompanyModel();
        $data = $obj->setCompBase();
        parent::ajaxReturn($data,'modify');
    }
    
    //查看学生投递简历记录
    public function getStuResume()
    {
        $obj  = new getCompanyModel();
        $data = $obj->getStuResume();
        parent::ajaxReturn($data,'modify');
    }
    
    //查看投递简历的简历信息
    public function getResumeInfo ()
    {
        $obj  = new getCompanyModel();
        $data = $obj->getResumeInfo();
        parent::ajaxReturn($data,'modify');
    }
    //修改简历状态
    public function setResumeStatus()
    {
        $obj  = new setCompanyModel();
        $data = $obj->setResumeStatus();
        parent::ajaxReturn($data,'modify');
    }
}