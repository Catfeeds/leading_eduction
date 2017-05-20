<?php
	header("Content-type:text/html;charset=utf-8");
	session_start();
	date_default_timezone_set('Asia/Shanghai');
	require_once('framework/autoLoad.php');
	require_once('config/config.php');
	require_once('framework/pc.php');
	//单一入口文件
	use framework\PC;
	$module = (!empty($_GET['module']))?$_GET['module']:'index';
	PC::run($module,$config);








