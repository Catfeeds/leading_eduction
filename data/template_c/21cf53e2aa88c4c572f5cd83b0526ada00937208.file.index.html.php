<?php /* Smarty version Smarty-3.1.16, created on 2017-03-20 21:55:28
         compiled from "tpl\student\index.html" */ ?>
<?php /*%%SmartyHeaderCode:3164358ce201b68ed38-97377750%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '21cf53e2aa88c4c572f5cd83b0526ada00937208' => 
    array (
      0 => 'tpl\\student\\index.html',
      1 => 1490017398,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3164358ce201b68ed38-97377750',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_58ce201b6e9cc1_18004588',
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58ce201b6e9cc1_18004588')) {function content_58ce201b6e9cc1_18004588($_smarty_tpl) {?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8"/>
<title>主页面</title>
<link type="text/css" rel="stylesheet" href="../../App/student/static/style/index.css"/>
<script type="text/javascript" src="../../static/js/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="../../static/js/common.js"></script>
<script type="text/javascript" src="../../App/student/static/js/index.js"></script>
<script type="text/javascript" src="../../App/student/static/js/form1.js"></script>
</head>
<body>
<div class="div1">
	<div class="h_left">
		<table class="table">
			<tr>
				<td id="f_head">上海海事大学</td>
			</tr>
			<tr><td id="s_head">Shanghai Maritime University</td></tr>
		</table>
	</div>
	<h2 class="h_h3">被您遗忘的</h2>
</div>
<div class="div2">
	<div class="link fl"><a href="#">上海海事大学</a><span>&gt;&gt;</span>学生首页</div>
    <div class="link fr">
            <span class="bar"><?php echo $_smarty_tpl->tpl_vars['data']->value['name'];?>
，欢迎您</span><span class="bar"><a href="index.php?controller=student&method=index" class="icon icon_i">首页</a></span><span class="bar"><a href="javascript:SHMTU.GLOBAL.fresh();" class="icon icon_n">刷新</a></span><span><a href="index.php?controller=student&method=logout" class="icon icon_e">注销</a></span><span class="bar">日期(<?php echo $_smarty_tpl->tpl_vars['data']->value['date'];?>
)</span>
    </div>   
</div>
<div class="div3">
	<div class="div21">
		<div class="div211" style="">
		<h3 style="background-color:SeaGreen;margin-top:0px;">功能列表<span>&gt;&gt;</span></h3>
		<table class="table3">
			<tr><td><a href="#" id="modify">个人信息完善</a></td></tr>
			<tr><td><a href="#" id="found">发布寻物启事</a></td></tr>
		</table>
		</div>
		<div class="div212">
			<ul>
				<li style="color:green;font-size:13px;">温馨提示：当丢失的物品上有个人信息时，请到查找学生信息页面进行相关操作</li>
				<li style="color:green;font-size:13px;">温馨提示：当丢失的物品上没有个人信息时，请到查看和发布页面进行相关操作</li>
			</ul>
		</div>
	</div>
	<div class="div22">
		<table class="table" id="infoTab" >
			<thead>
			<tr>
				<th width="10%">编号</th>
				<th>内容</th>
				<th width="15%">发布日期</th>
			</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
			<form action="post" class="form" method="post" name="form1" id="form1" style="display:none">
				<table width="500" height="363" border="0" align="left" cellpadding="0" cellspacing="0" margin-top="15px">
					<tr>
						<th colspan="3">请填写要修改的信息</th>
					</tr>
					<tr>
						<td><span class="style1">姓名：</span></td>
						<td id="username"><?php echo $_smarty_tpl->tpl_vars['data']->value['name'];?>
</td>
					</tr>
					<tr>
						<td><span class="style1">学号：</span></td>
						<td id="stuId"><?php echo $_smarty_tpl->tpl_vars['data']->value['stuId'];?>
</td>
					</tr>
					<tr>
						<td colspan="2" ><a href="#" id="modifyPass">修改密码:</a></td>
					</tr>
					<tr id="trPass" style="display:none">
						<td>登录密码：</td>
						<td><input name="pass1" type="password" id="pass1"	class="formstyle"> <font color="#F9481C">*</font></td><td id="fontPass" width="100px"></td>
					</tr>
					<tr id="trPass2" style="display:none">
						<td>确认密码：</td>
						<td><input name="pass2" type="password" id="pass2"
							class="formstyle"> <font color="#F9481C">*</font></td><td id="fontPass2" width="100px"></td>
					</tr>
					<tr>
						<td>卡编号：</td>
						<td><input name="ecardId" type="text" class="formstyle" id="ecardId">
							<font color="#F9481C" >*</font></td><td id="fontEcardId" width="100px"></td>
					</tr>
					<tr>
						<td>联系方式1：</td>
						<td><input name="tel" type="text" class="formstyle" id="tel1">
							<font color="#F9481C" >*</font></td><td id="fontTel1" width="100px"></td>
					</tr>
					<tr>
						<td>联系方式2：</td>
						<td><input name="tel" type="text" class="formstyle" id="tel2">
							<font color="#F9481C" >*</font></td><td id="fontTel2" width="100px"></td>
					</tr>
					<tfoot>
						<tr>
							<td colspan="3"><input name="submit" type="button" id="butForm1"
								value="修改">
							</li> <input name="reset" type="reset" value="重置">
							</li></td>
						</tr>
					</tfoot>
				</table>
			</form>
			<form action="post" method="post"  class="form"name="form2" id="form2" style="display:none">
				<table width="500" height="363" border="0" align="left" cellpadding="0" cellspacing="0" margin-top="15px">
					<tr>
						<th colspan="2">发布寻物启事</th>
					</tr>
					<tr>
						<td><span class="style1">丢失人：</span></td>
						<td><input name="ecardId" type="text" class="formstyle" id="userName2"></td>
					</tr>
					<tr>
						<td><span class="style1">学号：</span></td>
						<td><input name="ecardId" type="text" class="formstyle" id="stuId2"></td>
					</tr>
					<tr>
						<td>丢失物：</td>
						<td><select name="select">
								<option>----请选择丢失物----</option>
								<option value="1">校园卡</option>
								<option value="2">钱包</option>
								<option value="3">其他请输入</option>
						</select></td>
					</tr>
					<tr>
						<td><span class="style1">寻物信息：</span></td>
						<td id="stuId"><textarea></textarea></td>
					</tr>
					<tfoot>
						<tr>
							<td colspan="2"><input name="submit" type="submit"
								value="提交">
							</li> 
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
</div>
</body>
</html><?php }} ?>
