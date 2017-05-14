/**我的招聘js文件<--基于jquery-->***/

$(document).ready(function(){
	/***为单行绑定点击事件**/
	$("#com1").bind('click',function(){
		
	});
	$("tr[name=company]").bind('click',function(){
		window.location.href="http://www.568it.cn/#/recruitment";
	});
});