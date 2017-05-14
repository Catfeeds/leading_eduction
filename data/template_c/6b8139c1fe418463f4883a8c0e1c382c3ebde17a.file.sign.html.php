<?php /* Smarty version Smarty-3.1.16, created on 2017-05-03 10:16:32
         compiled from "tpl/index/sign.html" */ ?>
<?php /*%%SmartyHeaderCode:166811738159093d80bd2358-51535253%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6b8139c1fe418463f4883a8c0e1c382c3ebde17a' => 
    array (
      0 => 'tpl/index/sign.html',
      1 => 1493721193,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '166811738159093d80bd2358-51535253',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_59093d80de0e57_75861945',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59093d80de0e57_75861945')) {function content_59093d80de0e57_75861945($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>报名免费试听</title>
	<link rel="stylesheet" href="./static/index/css/weui.css"/>
	<link rel="stylesheet" href="./static/index/css/example.css"/>
	<script type="text/javascript" src="./static/index/js/jquery.min.js"></script>
	<script type="text/javascript" src="./static/index/js/common.js"></script>
	<script type="text/javascript" src="./static/index/js/sign.js"></script>
</head>
<body ontouchstart>
	<div class="page__hd">
        <h1 class="page__title">
            <img src="./static/index/images/logo.png" alt="WeUI" height="21px" />
        </h1>
        <p class="page__desc"><h3>上海领思教育报名免费试听</h3></p>
    </div>
	<div class="weui-cells weui-cells_form">
		<div class="weui-cell">
             <div class="weui-cell__hd"><label for="" class="weui-label">姓名</label></div>
             <div class="weui-cell__bd">
                 <input class="weui-input" type="text" value=""/ id="signName">
             </div>
        </div>
		<div class="weui-cell">
             <div class="weui-cell__hd"><label for="" class="weui-label">手机</label></div>
             <div class="weui-cell__bd">
                 <input class="weui-input" type="tel" value="" id="568Tel"/>
             </div>
        </div>
		<div class="page__bd">
			<div class="weui-cells__title">选择课程</div>
			<div class="weui-cells weui-cells_radio">
				<label for="">android开发</label>
				<input type="radio" name="course1" id="56802" value="56802"/>
				<label for="">ios开发</label>
				<input type="radio" name="course1" id="56803" value="56803"/>
			</div>
			<div class="weui-cells weui-cells_radio">
				<label for="">java后端开发</label>
				<input type="radio" name="course1" id="56801" value="56801"/>
				<label for="">web前端开发</label>
				<input type="radio" name="course1" id="56804" value="56804"/>
			</div>
			<div class="weui-cells weui-cells_radio">
				<label for="">全栈开发</label>
				<input type="radio" name="course1" id="56806" value="56806"/>
				<label for="">UI开发</label>
				<input type="radio" name="course1" id="56805" value="56805"/>
			</div>
		</div>
		<div class="weui-cell">
             <div class="weui-cell__hd"><label for="" class="weui-label">试听时间</label></div>
             <div class="weui-cell__bd">
                 <input class="weui-input" type="date" value="" id="568date"/>
             </div>
        </div>
		<div class="button-sp-area">
			<a href="javascript:SHMTU.GLOBAL.INDEX.sign();" class="weui-btn weui-btn_plain-primary">提交报名</a>
		</div>
	</div>
</body>
</html><?php }} ?>
