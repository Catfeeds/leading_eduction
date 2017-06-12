<?php
namespace App\admin\Controller;
use App\admin\Model\superModel;

class superController extends baseController
{
    public function modifyCaseId() {
        $obj  = new superModel();
        $data = $obj->modifyCaseId();
        parent::ajaxReturn($data,'modify');
    }
}