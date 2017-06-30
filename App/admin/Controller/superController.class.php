<?php
namespace App\admin\Controller;
use App\admin\Model\superModel;

class superController extends baseController
{
    public function modifyCaseId() {
        $obj  = new superModel();
        $data = $obj->modifyCaseId();
        parent::ajaxReturn($data);
    }
    
    public function addAddress()
    {
        $obj  = new superModel();
        $data = $obj->addAddress();
        parent::ajaxReturn($data);
    }
    
    
    
}