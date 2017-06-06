<?php
namespace App\admin\Controller;
use App\admin\Model\getTeacherModel;
class teacherController extends baseController
{
    
    
    public function getTeacherInfo()
    {
        $obj  = new getTeacherModel();
        $data = $obj->getTeacherInfo();
        parent::ajaxReturn($data);
    }
}