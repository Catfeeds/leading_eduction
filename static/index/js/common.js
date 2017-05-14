/**js公共函数<--基于jquery-->**/

var SHMTU = SHMTU || {};
SHMTU.GLOBAL = {};//公共类
SHMTU.APP = {};//模块类

/*
*实现登陆
*@params string url URL地址
*@params function callBack响应成功后处理函数
**/
SHMTU.GLOBAL.login = function (url,callback)
{
	var userName = $("#userName").val();//获得表单中的数据
	var password = $("#password").val();
	checkInfo(userName,password,url,callback);//信息处理及加密
}
/**登陆信息处理及加密**/
function checkInfo (userName,password,url,callback)
{
	this.userName = userName;
	this.password = password;
	if(!(userName && password)){//两者都不能为空
		alert('登陆信息不能为空');
		return false;
	}else{
		password = hex_md5(password);//密码不为空时md5加密
		SHMTU.GLOBAL.postInfo(password,url,callback);//向后端传递数据
	}
}

/**向后端传递数据**/
SHMTU.GLOBAL.postInfo = function(password,url,callback)
{
	var data = {"userName":userName,"password":password};//传递的数据
	SHMTU.GLOBAL.AJAX(url,data,callback);//使用Ajax方式传递
}

/*
*Ajax事件
*@params mixed data
**/
SHMTU.GLOBAL.AJAX = function(url,data,callback)
{
	var type = 'POST';
	var dataType = 'json';
	var error = function(jqXHR){//响应错误时的提醒信息
		alert("发生错误："+jqXHR.status);
	}
	$.ajax({
		"url":url,"type":type,"data":data,"dataType":dataType,"success":callback,"error":error,
	});
}

/**为登陆按钮绑定回车事件**/
SHMTU.GLOBAL.checkLogin = function(fun)
{
	document.onkeydown = function(e){
		if(!e){
			e = window.event;
		}
		if((e.keyCode || e.which) == 13){
			fun();
		}
	}
}

/**
 *分页码函数
 **/
SHMTU.GLOBAL.pageUrl = function(url,data,callback)
{
	SHMTU.GLOBAL.AJAX(url,data,callback);
}

/**
 *格式化日期
 *timestamp是以秒为单位
 **/
SHMTU.GLOBAL.getDate = function(timestamp)
{
	var y,m,d;
	var now = new Date(timestamp * 1000);
	y = now.getFullYear();
	m = now.getMonth() + 1;
	d = now.getDate();
	return  (m<10?"0"+m:m)+"/"+(d<10?"0"+d:d)+"/"+y;
}

/**
 * 刷新
 */
SHMTU.GLOBAL.fresh = function()
{
	location.reload();
}

/**
 * 点击显示id控件
 */
SHMTU.GLOBAL.show = function(id)
{
	$("#"+id).show();
}

/**
 * 点击隐藏id控件
 */
SHMTU.GLOBAL.hide = function(id)
{
	$("#"+id).hide();
}

/**
 * 输入框只能输入数字
 */
SHMTU.GLOBAL.pregNum = function(id)
{
	$("#"+id).bind('keyup',function(){
		this.value = this.value.replace(/[^0-9]/,'');
	});
}
/**
 * 输入框只能输入数字,字母及下划线
 */
SHMTU.GLOBAL.pregW = function(id)
{
	$("#"+id).bind('keyup',function(){
		this.value = this.value.replace(/[^\w]/,'');
	});
}

/**
 * 手机号是否匹配
 * telId是控件id
 */
SHMTU.GLOBAL.pregTel = function(telId)
{
	var reg = /^1[3458]{1}\d{9}$/;//手机号匹配格式
	var tel = $("#"+telId).val();//获得手机号
	if(tel.length == 11 && reg.test(tel)){//如果匹配
		return tel;
	}else{
		return false;
	}
}

/**
 * 绑定失去焦点事件，来匹配字符的长短
 * return 长度相等返回true，否则false
 */
SHMTU.GLOBAL.blurLen = function(id,length,id2,msg)
{
	$("#"+id).bind('blur',function(){
		var len = $("#"+id).val().length;
		if(len != length && len!= 0){
			$("#"+id).css('border','1px red solid');
			$("#"+id2).text(msg);//提示信息
			$("#"+id2).css('color','red');
		}
	}
	);
}

/**
 * 绑定失去焦点事件
 */
SHMTU.GLOBAL.blur = function(id,fun)
{
	$("#"+id).bind('blur',fun);
}

/**
 * 绑定获得焦点事件
 */
SHMTU.GLOBAL.focus = function(id,id2)
{
	$("#"+id).bind('focus',function(){
		SHMTU.GLOBAL.recover(id,id2);
	});
	
}


/**
 * 恢复原样
 */
SHMTU.GLOBAL.recover = function(id,id2)
{
	$("#"+id).css('border','1px solid black');
	$("#"+id2).text('');
}

/**
 * 提示信息
 * id2是提示信息所在控件id,msg是提示信息
 */
SHMTU.GLOBAL.warn = function(id,id2,msg)
{
	$("#"+id).css('border','1px solid red');
	$("#"+id2).text(msg);
	$("#"+id2).css('color','red');
}
