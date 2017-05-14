<?php /* Smarty version Smarty-3.1.16, created on 2017-05-02 18:33:23
         compiled from "tpl/index/login.html" */ ?>
<?php /*%%SmartyHeaderCode:1162135825907f96ae92416-80692309%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e6df0d0d6437f1a41cb152a4cac72bd789a7f541' => 
    array (
      0 => 'tpl/index/login.html',
      1 => 1493721193,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1162135825907f96ae92416-80692309',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_5907f96b0db7a4_39393960',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5907f96b0db7a4_39393960')) {function content_5907f96b0db7a4_39393960($_smarty_tpl) {?><!DOCTYPE html>
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
