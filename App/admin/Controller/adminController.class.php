<?php
namespace App\admin\Controller;
use App\admin\Model\checkModel;
use App\admin\Model\verifyModel;
use App\admin\Model\doActionModel;
use App\admin\Model\getInfoModel;
use App\admin\Model\getStuInfoModel;
use App\admin\Model\insertInfoModel;
use App\admin\Model\modifyInfoModel;
use framework\libs\core\VIEW;
class adminController extends baseController
{
    /**
     * just for test
     */
	public function index()
	{
		echo 'admin';
	}
	
	/**
	 * @Ԧ处理注册
	 */
	public function sign()
	{
		$obj  = new checkModel();
		$data = $obj->checkSign();
		parent::ajaxReturn($data);
	}
	/**
	 * @获得图片验证码
	 */
	public function getVerify()
	{
	    $data = verifyModel::getVerifyCode();
	    parent::ajaxReturn($data);
	}
	/**
	 * @处理登陆
	 */
	public function login()
	{
	    $obj  = new checkModel();
	    $data = $obj->checkLogin();
	    parent::ajaxReturn($data);
	    //var_dump($data);
	}
	/**
	* 注销
	* @date: 2017年5月12日 上午10:34:52
	* @author: lenovo2013
	* @return:json
	*/
	public function logout()
	{
	    $obj  = new checkModel();
	    $data = $obj->logout();
	    parent::ajaxReturn($data);
	}
	/**
	* 重置密码
	* @date: 2017年5月12日 下午1:18:06
	* @author: lenovo2013
	* @param: variable
	* @return:json
	*/
	public function resetPassword()
	{
	    $obj  = new doActionModel();
	    $data = $obj->resetPassword();
	    parent::ajaxReturn($data);
	}
	/**
	* 获得登陆者的基本信息
	* @date: 2017年5月12日 下午5:35:56
	* @author: lenovo2013
	* @return:josn
	*/
	public function getLoginedBase()
	{
	    $obj  = new getInfoModel();
	    $data = $obj->getLoginedBase();
	    //var_dump($data);
	    parent::ajaxReturn($data);
	}
	public function test()
	{
	    $obj  = new getStuInfoModel();
	    $data = $obj->getCourse_byStuId('1601321102');
	    //var_dump($data);
	    parent::ajaxReturn($data);
	}
	public function insert()
	{
		$obj  = new insertInfoModel();
		$data = $obj->insertStuContent();
		var_dump($data);
		//VIEW::ajaxReturn($data);
	}
	public function productStuId()
	{
	    $obj = new doActionModel();
	    var_dump($obj->productStaffId());
	}
	/**
	 * 修改信息
	 */
	public function modifyInfo()
	{
	    $obj  = new modifyInfoModel();
	    $data = $obj->modifyInfo();
	    parent::ajaxReturn($data);
	}
	
	public function sendMail()
	{
	    $obj  = new checkModel();
	    $data = $obj->sendMail2();
	    parent::ajaxReturn($data);
	}
	
	//邮箱找回密码
	public function startResetPassword()
	{
	    $obj  = new checkModel();
	    $data = $obj->startResetPassword();
	    parent::ajaxReturn($data,'modify');
	}
	public function checkresetPassVerify()
	{
	    $obj  = new checkModel();
	    $data = $obj->checkresetPassVerify();
	    parent::ajaxReturn($data,'modify');
	}
	
}