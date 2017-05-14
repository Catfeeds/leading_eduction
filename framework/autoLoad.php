<?php

class autoLoad
{//实现自动加载
	
	public static function load($className)
	{
		$fileName = str_replace('\\','/',$className);//格式化文件名
		$fileName = sprintf('%s.class.php',$fileName);//根据要引用的类来获得加载的文件名
		if(is_file($fileName)){//如果是文件，引入
			require_once($fileName);
		}
		//echo $fileName."<br />";
	}
}
spl_autoload_register(['autoLoad','load']);//注册到__autoload队列中







