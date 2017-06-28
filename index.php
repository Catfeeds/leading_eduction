<?php
	header("Content-type:text/html;charset=utf-8");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods:POST,GET");
	session_start();
	date_default_timezone_set('Asia/Shanghai');
	require_once('framework/autoLoad.php');
	
	require_once('config/config.php');
	require_once('framework/pc.php');
	//��һ����ļ�
	use framework\PC;
	$module = (!empty($_GET['module']))?$_GET['module']:'index';
	PC::run($module,$config);








