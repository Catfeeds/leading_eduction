<?php
namespace framework;
use framework\libs\core\DB;
use framework\libs\core\VIEW;
include_once('function/function.php');
include_once('function/common.php');
use App\index\Controller\indexController;//1
class PC
{
	public static $controller;
	public static $method;
	private static $config;
	private static $controllerArr;
	private static $methodArr;
	// private static $controllerArr = array('index','test');
	// private static $methodArr = array('index','test','show');
	//public static $link;
	
	private static function init_db()
	{
		DB::init('mysqli',self::$config['dbConfig']);
	}
	
	private static function init_view()
	{
		VIEW::init('smarty',self::$config['viewConfig']);
	}
	
	private static function init_controller()
	{
		//self::$controller = in_array($_GET['controller'],self::$controllerArr)?daddslashes($_GET['controller']):'index';
		if($_GET && isset($_GET['controller']) && !empty($_GET['controller'])){
			self::$controller = $_GET['controller'];
		}else{
			self::$controller = (!empty($_GET['module']))?$_GET['module']:'index';
			//self::$controller = 'index';
		}
	}
	
	private static function init_method()
	{
		//self::$method = in_array($_GET['method'],self::$methodArr)?daddslashes($_GET['method']):'index';
		if($_GET  && isset($_GET['method']) && !empty($_GET['method'])){
			self::$method = $_GET['method'];//的确有问题
		}else{
			self::$method = 'index';
		}
	}
	
	public static function run($module,$config)
	{
		
		//exit();
		self::$config = $config;
		self::init_db();
		self::init_view();
		self::init_method();//有问题？
		self::init_controller();
		C($module,self::$controller,self::$method);
	}
	// public static function run($config)
	// {
		
		// //exit();
		// self::$config = $config;
		// self::init_db();
		// self::init_view();
		// self::init_method();//有问题？
		// self::init_controller();
		// C(self::$controller,self::$method);
	// }
	
	
}





































