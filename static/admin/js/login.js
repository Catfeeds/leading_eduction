/**领思微信后台登陆管理js文件<--基于jquery-->***/

$(document).ready(function(){
	SHMTU.GLOBAL.ADMIN.attachEvent();
});

SHMTU.GLOBAL.ADMIN = {};

SHMTU.GLOBAL.ADMIN.attachEvent = function()
{
	/**为登陆按钮绑定回车事件**/
	SHMTU.GLOBAL.checkLogin(SHMTU.GLOBAL.ADMIN.login);
	/**为登录按钮绑定登陆事件**/
	$("#lsLogin").bind('click',SHMTU.GLOBAL.ADMIN.login);
}

SHMTU.GLOBAL.ADMIN.login = function()
{
	var name = $("#name").val();
	var password = hex_md5($("#password").val());
	/**需要验证信息**/
	var data = {"name":name,"password":password};
	var url = "index.php?module=admin&controller=admin&method=loginAdmin";
	SHMTU.GLOBAL.AJAX(url,data,SHMTU.GLOBAL.ADMIN.loginCallback);
}
/**登陆回调函数***/
SHMTU.GLOBAL.ADMIN.loginCallback = function(data)
{
	if(data.status == 1){
		//登陆成功，跳转至后台首页面
		window.location.href = "index.php?module=admin&controller=admin&method=admin";
	}
}