<?php
namespace App\wechat\Model;

class customModel
{
	/**
	*自定义类
	*自定义回复内容及菜单内容
	*/
	private static $obj = '';
	
	public function __construct() 
	{
		self::$obj = new getCustomInfoModel();
	}
	/**
	*自定义关注回复内容
	***/
	public function responseSubscribe($key)
	{
		$text = "恭喜你，终于找到上海领思啦！
说明你离IT技术大牛的路不远咯！
了解课程介绍请回复【001】；
了解师资力量请回复【002】；
了解学员风采请回复【003】；
申请免费试听课请点击：http://www.568it.cn
课程咨询热线：021-58011176
悄悄告诉你，每周都送电影票，别走开哟！";
		$content = self::$obj->responseSubText();
		$content = empty($content)?$text:$content;
		$content .= (!empty($key))?("\n来自二维码场景 ".str_replace("qrscene_","",$key)):"";
		return $content;
	}
	//自定义菜单数据
	public function create_menu()
	{
		$data = '';
		$data =  '{
		 "button":[
		  {
			   "name":"走进领思",
			   "sub_button":[
			   {	
				   "type":"click",
				   "name":"企业介绍",
				   "key":"568IT"
				},
				{
				   "type":"click",
				   "name":"师资力量",
				   "key":"568TEACHER"
				},
				{	
				   "type":"click",
				   "name":"办学特色",
				   "key":"568EDUCTION"
				},
				{	
				   "type":"click",
				   "name":"学员风采",
				   "key":"568STUDENT"
				},
				{	
				   "type":"click",
				   "name":"最新新闻",
				   "key":"568NEWS"
				}
				]
		   },
		   {
			   "name":"领思家园",
			   "sub_button":[
			   {	
				   "type":"click",
				   "name":"课程介绍",
				   "key":"568COURSE"
				},
				{
				   "type":"click",
				   "name":"名师课堂",
				   "key":"568GOOD_COURSE"
				},
				{	
				   "type":"click",
				   "name":"开班典礼",
				   "key":"568CEREMONY"
				},
				{	
				   "type":"click",
				   "name":"技术干货",
				   "key":"568RESOURCE"
				},
				{	
				   "type":"click",
				   "name":"官方好书",
				   "key":"568BOOK"
				}
				]
		   },
		   {
			   "name":"我的领思",
			   "sub_button":[
			   {	
				   "type":"view",
				   "name":"我要招聘",
				   "url":"http://web1612191008206.bj01.bdysite.com/study/index.php?method=showRecruit"
				},
				{	
				   "type":"view",
				   "name":"我要合作",
				   "url":"http://web1612191008206.bj01.bdysite.com/study/index.php?method=showCooperate"
				},
				{	
				   "type":"view",
				   "name":"报名试听",
				   "url":"http://web1612191008206.bj01.bdysite.com/study/index.php?method=showTpl"
				},
				{	
				   "type":"click",
				   "name":"我的客服",
				   "key":"568SERVER"
				}
				]
		   }
		   ]
		}';
		$res = self::$obj->create_menu();
		$data = !empty($res)?$res:$data;
		return $data;
	}
	//回复点击菜单的内容
	public function responseMenuClick($key)
	{
		$content[] = array("Title"=>"领思教育，企业介绍",  "Description"=>"领思教育，领先一步", "PicUrl"=>"http://www.568it.cn/assets/images/logo.png", "Url" =>"http://www.568it.cn");
		$content[] = array("Title"=>"领思教育，企业介绍",  "Description"=>"领思教育，领先一步", "PicUrl"=>"http://www.568it.cn/assets/images/logo.png", "Url" =>"http://www.568it.cn");
		$content[] = array("Title"=>"领思教育，企业介绍",  "Description"=>"领思教育，领先一步", "PicUrl"=>"http://www.568it.cn/assets/images/logo.png", "Url" =>"http://www.568it.cn");
		$res = self::$obj->responseClick($key);
		$content = !empty($res)?$res:$content;
		return $content;
	}
}