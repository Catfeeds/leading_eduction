/**后台管理主页js文件<--基于jquery-->****/
$(document).ready(function(){
	SHMTU.GLOBAL.ADMIN.attachEvent();
}
);
SHMTU.GLOBAL.ADMIN = {};

SHMTU.GLOBAL.ADMIN.attachEvent = function()
{
	/**检查是否登陆**/
	//1.没有登录，跳到登陆页面
	SHMTU.GLOBAL.ADMIN.checkLogin();
	//2.登陆了，获得需要的信息
	/**如果登陆获取listen表信息***/
	//SHMTU.GLOBAL.ADMIN.getListen(null);
}
//检查是否登陆
SHMTU.GLOBAL.ADMIN.checkLogin = function()
{
	var data = {};
	var url = "index.php?module=admin&controller=admin&method=checkLogin";
	SHMTU.GLOBAL.AJAX(url,data,SHMTU.GLOBAL.ADMIN.checkCallback);
}
/**检查登录回调函数**/
SHMTU.GLOBAL.ADMIN.checkCallback = function(data)
{
	if(data.status == 1){
		//登陆了，获得需要的信息
		//1.获得试听列表信息
		SHMTU.GLOBAL.ADMIN.getListen(null);
		//2.获得登陆用户信息
		SHMTU.GLOBAL.ADMIN.getUser();
	}else{
		//没有登录，跳到登陆页面
		window.location.href = "index.php?module=admin&controller=admin&method=login";
	}
}
/**获得listen信息**/
SHMTU.GLOBAL.ADMIN.getListen = function(data)
{
	var url = "index.php?module=admin&controller=admin&method=getListen";
	//var data = {};
	SHMTU.GLOBAL.AJAX(url,data,SHMTU.GLOBAL.ADMIN.getLCallback);
}

/**获得listen信息后的回调函数***/
SHMTU.GLOBAL.ADMIN.getLCallback = function(data)
{
	if(data.status == 1){
		$("tr[name=test]").remove();//删除默认行
		var len = data.info.length;
		for(i=0;i<len;i++){
			//格式化日期
			var date   = SHMTU.GLOBAL.getDate(data['info'][i]['listen_time']);
			var listen_time = data['info'][i]['sign_time'];
			var id     = data['info'][i]['id'];
			var name   = data['info'][i]['name'];
			var course = data['info'][i]['course'];
			var tel    = data['info'][i]['telPhone'];
			//追加行
			$("#lostTab").append("<tr name='test'><td>"+id+"</td><td>"+name+"</td><td>"+course+"</td><td>"+date+"</td><td>"+listen_time+"</td><td>"+tel+"</td></tr>");
		}
		var page = data['page'];
		$("#lostTab").append("<tr name='test'><td colspan='5'>"+page+"</td><td colspan='1'>每页显示<span><select id='pageSel'><option value='1'>8</option><option value='2'>20</option><option value='3'>50</option></select></span>条信息</td></tr>");
	}
}
/*获得登陆用户信息**/
SHMTU.GLOBAL.ADMIN.getUser = function()
{
	var url = "index.php?module=admin&controller=admin&method=getUser";
	var data = {};
	SHMTU.GLOBAL.AJAX(url,data,SHMTU.GLOBAL.ADMIN.getUCallback);
}

/**获得登陆用户信息回调函数**/
SHMTU.GLOBAL.ADMIN.getUCallback = function(data)
{
	if(data.status == 1){
		$("#userInfo").text(data.info.name);
	}
}
/**实现注销**/
SHMTU.GLOBAL.ADMIN.logout = function()
{
	//注销成功跳至登陆页
	var data = {};
	var url = "index.php?module=admin&controller=admin&method=logout";
	SHMTU.GLOBAL.AJAX(url,data,function(data){
		if(data.status == 1){
			window.location.href="index.php?module=admin&controller=admin&method=login";
		}
	});
}
/**实现分页**/
SHMTU.GLOBAL.page = function(page)
{
	//var url = "index.php?module=admin&controller=admin&method=getListen";
	var pageSize = $("#pageSel").find("option:selected").text();//获得pageSize
	var data = {"page":page,"pageSize":pageSize};
	SHMTU.GLOBAL.ADMIN.getListen(data);//获得信息
}