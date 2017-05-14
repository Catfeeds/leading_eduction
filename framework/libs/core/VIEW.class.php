<?php
namespace framework\libs\core;
require_once('framework/libs/view/smarty/Smarty.class.php');
/***视图工厂类****/
class VIEW
{
	public static $view;
	
	/***
	*初始化视图类
	*@params string $viewType 是视图类名
	*@params array  $viewConfig 该类的配置信息
	***/
	public static function init($viewType,$viewConfig)
	{
		self::$view = new $viewType;
		foreach($viewConfig as $key=>$value){
			self::$view->$key = $value;
		}
	}
	
	/*
	*封装assign函数
	*@params array $data 信息数组
	*/
	public static function assign($data)
	{
		foreach($data as $key=>$value){
			self::$view->assign($key,$value);
		}
	}
	
	/**
	*封装display函数
	*@params string $template 模版名
	**/
	public static function display($template)
	{
		self::$view->display($template);
	}
	
	/**
	*Ajax方式返回数据到客服端
	*@params mixed $data 要返回的数据,格式为$data['data'],$data['status'],$data['info'];分别表示返回的数据、状态及提示信息
	*@retrun void 
	**/
	public static function ajaxReturn($data)
	{
		header('Content-type:application/json;charset=utf-8');
		exit(json_encode($data));
	}
	
	
	
	
}




























