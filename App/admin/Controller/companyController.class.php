<?php
namespace App\admin\Controller;
use App\admin\Model\getCompanyModel;

class companyController extends baseController
{
    public function getCompanyInfo()
    {
        $obj  = new getCompanyModel();
        $data = $obj->getCompanyInfo();
        parent::ajaxReturn($data);
    }
}