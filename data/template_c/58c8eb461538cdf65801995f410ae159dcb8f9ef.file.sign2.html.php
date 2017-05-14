<?php /* Smarty version Smarty-3.1.16, created on 2017-05-05 22:16:30
         compiled from "tpl/index/sign2.html" */ ?>
<?php /*%%SmartyHeaderCode:1076539632590c880ae635d5-48784829%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '58c8eb461538cdf65801995f410ae159dcb8f9ef' => 
    array (
      0 => 'tpl/index/sign2.html',
      1 => 1493993783,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1076539632590c880ae635d5-48784829',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_590c880aee2480_90355965',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_590c880aee2480_90355965')) {function content_590c880aee2480_90355965($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>报名免费试听</title>
	<link rel="stylesheet" href="./static/index/css/weui.css"/>
	<link rel="stylesheet" href="./static/index/css/example.css"/>
    <link rel="stylesheet" href="./static/index/css/index2.css"/>
	<script type="text/javascript" src="./static/index/js/jquery.min.js"></script>
	<script type="text/javascript" src="./static/index/js/common.js"></script>
	<script type="text/javascript" src="./static/index/js/sign.js"></script>
</head>
<body ontouchstart>
	<div class="page__hd">
        <h1 class="page__title">
            <img src="./static/index/images/logo.png" alt="WeUI" height="21px" />
        </h1>
        <h3>上海领思教育报名免费试听</h3>
    </div>



<!-- 修改如下 -->


    <div>
        <form class="form_main" action="" method="post">
           
            <h2>选择课程：</h2>
                <input type="radio" name="course1" id="56802" value="56802">android开发
                <br>
                <input type="radio" name="course1" id="56803" value="56803">java后端开发
                <br>
                <input type="radio" name="course1" id="56801" value="56801">ios开发
                <br>
                <input type="radio" name="course1" id="56804" value="56804">web前端开发
                <br>
                <input type="radio" name="course1" id="56806" value="56806">全栈开发
                <br>
                <input type="radio" name="course1" id="56805" value="56805">UI开发
                <br>

            
                <input class="weui-input" type="date" value="" id="568date"/>
                <br>
                 尊姓大名：
                <input type="text" name="username" value=""/ id="signName">
                <br>
                手机号码：
                <input type="text" name="" value="" id="568Tel">
                <br>
                <input type="submit" name="提交">


        </form>
    </div>

</body>
</html><?php }} ?>
