<?php
namespace App\wechat\Model;

class getCustomInfoModel
{
	/**
	*获得自定义的数据类
	*获得自定义的数据
	***/
	
	
	//获得关注回复信息内容
	public function responseSubText()
	{
		$content = '';
		return $content;
	}
	//获得自定义菜单数据,记得数据要json_encode，即返回json数据
	public function create_menu()
	{
		$data = '';
		return $data;
	}
	
	//获得菜单点击事件回复信息，若为多图文信息是返回的是多维数组
	public function responseClick($key)
	{
		$data = '';
		return $data;
	}
}