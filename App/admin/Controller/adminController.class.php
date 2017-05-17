<?php
namespace App\admin\Controller;
use App\admin\Model\checkModel;
use App\admin\Model\verifyModel;
use App\admin\Model\doActionModel;
use App\admin\Model\getInfoModel;
use App\admin\Model\getStuInfoModel;
use framework\libs\core\VIEW;
class adminController
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
		$obj = new checkModel();
		$data = $obj->checkSign();
		VIEW::ajaxReturn($data);
	}
	/**
	 * @获得图片验证码
	 */
	public function getVerify()
	{
	    $data = verifyModel::getVerifyCode();
	    VIEW::ajaxReturn($data);
	}
	/**
	 * @处理登陆
	 */
	public function login()
	{
	    $obj = new checkModel();
	    $data = $obj->checkLogin();
	    VIEW::ajaxReturn($data);
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
	    $obj = new checkModel();
	    $data = $obj->logout();
	    VIEW::ajaxReturn($data);
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
	    $obj = new doActionModel();
	    $data = $obj->resetPassword();
	    VIEW::ajaxReturn($data);
	}
	/**
	* 获得登陆者的基本信息
	* @date: 2017年5月12日 下午5:35:56
	* @author: lenovo2013
	* @return:josn
	*/
	public function getLoginedBase()
	{
	    $obj = new getInfoModel();
	    $data = $obj->getLoginedBase();
	    //var_dump($data);
	    VIEW::ajaxReturn($data);
	}
	public function test()
	{
	    $obj = new getStuInfoModel();
	    $data = $obj->getCourse_byStuId('1601321102');
	    //var_dump($data);
	    VIEW::ajaxReturn($data);
	}
}