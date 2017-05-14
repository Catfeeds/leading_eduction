/**微信报名js文件<--基于jquery-->****/
$(document).ready(function(){
	//SHMTU.GLOBAL.INDEX.attachEvents();
});
SHMTU.GLOBAL.INDEX = {};
SHMTU.GLOBAL.INDEX.attachEvents = function()
{
	//为电话输入框绑定失焦事件
	$("#568Tel").bind('blur',function(){
		var tel = $("#568Tel").val();
		if(!SHMTU.GlOBAL.SHMTU.pregTel(tel)){//校研不通过，提示号码不通过
			
		}
	});
}
SHMTU.GLOBAL.INDEX.sign = function()
{
	var name = $("#signName").val();
	var tel = $("#568Tel").val();
	//$("input[name='course1']:checked").val();
	var courseId = SHMTU.GLOBAL.INDEX.formatCId($("input[name='course1']:checked").val());
	var date = $("#568date").val();
	var data = {"name":name,"tel":tel,"courseId":courseId,"date":date};
	var url = "index.php?module=admin&controller=admin&method=sign";
	SHMTU.GLOBAL.AJAX(url,data,SHMTU.GLOBAL.INDEX.signCallback);
}
SHMTU.GLOBAL.INDEX.signCallback = function(data)
{
	if(data.status == 1){
		alert("报名成功");
	}
}
/**格式化courseId**/
SHMTU.GLOBAL.INDEX.formatCId = function(id)
{
	var formatId = id.substr(4,1);
	return formatId;
}