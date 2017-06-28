<?php
namespace App\index\Controller;
use App\admin\Controller\baseController;
use App\index\Model\indexModel;
class indexController extends baseController
{
	public function index()
	{
		echo 'hello world!';
	}
	
	public function registerCourse()
	{
	    $obj  = new indexModel();
	    $data = $obj->registerCourse();
	    return parent::ajaxReturn($data,'modify');
	}
	
	public function getCourse()
	{
	    $obj  = new indexModel();
	    $data = $obj->getCourse();
	    return parent::ajaxReturn($data,'modify');
	}
	
	public function getCarousel()
	{
	    $obj  = new indexModel();
	    $data = $obj->getCarousel();
	    return parent::ajaxReturn($data,'modify');
	}
	
	public function getWorkInfo()
	{
	    $obj  = new indexModel();
	    $data = $obj->getWorkInfo();
	    return parent::ajaxReturn($data,'modify');
	}
	
	public function getClassInfo()
	{
	    $obj  = new indexModel();
	    $data = $obj->getClassInfo();
	    return parent::ajaxReturn($data,'modify');
	}
	public function getClassByAddress()
	{
	    $obj  = new indexModel();
	    $data = $obj->getClassByAddress();
	    return parent::ajaxReturn($data,'modify');
	}
}