<?php
namespace App\admin\Controller;

class baseController
{
    public function ajaxReturn($data)
    {
        header('Content-type:application/json;charset=utf-8');
        exit(json_encode($data));
    }
}