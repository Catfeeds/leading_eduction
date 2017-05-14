<?php /* Smarty version Smarty-3.1.16, created on 2017-04-03 13:23:05
         compiled from "tpl\student\login.html" */ ?>
<?php /*%%SmartyHeaderCode:1353558ce1fbccfd355-16207133%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '97e4bdba64cc3ae2e9378851dff427f68f71392a' => 
    array (
      0 => 'tpl\\student\\login.html',
      1 => 1491218580,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1353558ce1fbccfd355-16207133',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_58ce1fbcecbc99_75587840',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58ce1fbcecbc99_75587840')) {function content_58ce1fbcecbc99_75587840($_smarty_tpl) {?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>学生登陆</title>
<link type="text/css" rel="stylesheet" href="static/style/main.css" />
<link type="text/css" rel="stylesheet" href="../../static/style/reset.css">
<script type="text/javascript" src="../../static/js/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="../../static/js/md5.js"></script>
<script type="text/javascript" src="../../static/js/common.js"></script>
<script type="text/javascript" src="../../App/student/static/js/login.js"></script>
</head>
<body>
<div class="headerBar">
	<div class="logoBar login_logo">
		<div class="comWidth">
			
			<h3 class="welcome_title">欢迎登陆</h3>
		</div>
	</div>
</div>
<form method="post" action="index.php?controller=student&method=login" name="form1">
<div style="height:400px;">
<div class="loginBox">
	<div class="login_cont">
		<ul class="login">
			<li class="l_tit">学号</li>
			<li class="mb_10"><input type="text" class="login_input user_icon" name="name" id="userName" placeholder="请填写学号" ></li>
			<li class="l_tit">密码</li>
			<li class="mb_10"><input type="password" class="login_input user_icon" name="password" id="password" ></li>
			<li><input type="button" value="" class="login_btn" name="submit1" id="subBt" ></li>
		</ul>
		
	</div>
</div>
</div>
</form>
<div class="hr_25"></div>
<div class="footer">
	<p><a href="#">上海海事大学</a><i>--</i><a href="#">被您遗忘的</a><i>|</i><a href="#">联系我们</a><i>|</i>客服热线：400-675-1234</p>
</div>
</body>
</html>
<?php }} ?>
