<?php
namespace App\wechat\Controller;
use App\wechat\Model\indexModel;

class wechatController
{
	/**
	*检查签名或回复消息
	*/
	private static $obj = '';
	public function __construct()
	{
		self::$obj = new indexModel();
	}
	public function index()
	{
		self::$obj->index();
	}
	public function create_menu()
	{
		self::$obj->create_menu();
	}
}