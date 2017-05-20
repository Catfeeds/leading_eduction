<?php
namespace App\wechat\Model;

class wechatModel
{
	var $appid = "wxdda6ad81f6f31882";
	var $appsecret = "ee8a2d75446a00aaa5c750e4175f06d0";
	var $access_token = "";
    //构造函数，获取Access Token
	public function __construct($appid = NULL, $appsecret = NULL)
	{
        if($appid){
            $this->appid = $appid;
        }
        if($appsecret){
            $this->appsecret = $appsecret;
        }
        //hardcode
		//从数据库中获得access_token等数据
		$accArr = $this->getLastAccess();
		if(isset($accArr) && count($accArr) > 0){//如果数据库或session中有
			$id = $accArr['id'];
			$this->lasttime = $accArr['access_time'];
			$this->access_token = $accArr['access_token'];
			//var_dump($accArr);
		}else{
			$this->lasttime = 1395049256;
			$this->access_token = 		"RTEfXyAaZMEEMACbGyiqXknhjmNFc4we9S82x1EDV1l1G2MK3IHwqdIAxenPCgTvbNWyAtnU3wQPs1mNDgeM3MI-gwi1btXTaSwbtsDyxEAIMVhABAUYH";
		}
        if (time() > ($this->lasttime + 7200)){//如果access_token失效
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
            $res = $this->https_request($url);
            $result = json_decode($res, true);
            //save to Database or Memcache
			$arr = array(
				"access_time" => time(),
				"access_token" => $result["access_token"],
			);
			//插入数据库
			$this->setAccess($arr);
			//存入session
			//$_SESSION['access'] = $arr;
            $this->access_token = $result["access_token"];
            $this->lasttime = time();
        }
	}

    //获取关注者列表
	public function get_user_list($next_openid = NULL)
    {
		$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->access_token."&next_openid=".$next_openid;
        $res = $this->https_request($url);
        return json_decode($res, true);//数组
	}

    //获取用户基本信息
	public function get_user_info($openid)
    {
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->access_token."&openid=".$openid."&lang=zh_CN";
		$res = $this->https_request($url);
        return json_decode($res, true);
	}

    //创建菜单
    public function create_menu($data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }

    //发送客服消息，已实现发送文本，其他类型可扩展
	public function send_custom_message($touser, $type, $data)
    {
        $msg = array('touser' =>$touser);
        switch($type)
        {
			case 'text':
				$msg['msgtype'] = 'text';
				$msg['text']    = array('content'=> urlencode($data));
				break;
        }
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$this->access_token;
		return $this->https_request($url, urldecode(json_encode($msg)));
	}

    //生成参数二维码
    public function create_qrcode($scene_type, $scene_id)
    {
        switch($scene_type)
        {
			case 'QR_LIMIT_SCENE': //永久
                $data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
				break;
			case 'QR_SCENE':       //临时
                $data = '{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
				break;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        $result = json_decode($res, true);
        return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($result["ticket"]);
    }
    
    //创建分组
    public function create_group($name)
    {
        $data = '{"group": {"name": "'.$name.'"}}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }
    
    //移动用户分组
    public function update_group($openid, $to_groupid)
    {
        $data = '{"openid":"'.$openid.'","to_groupid":'.$to_groupid.'}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }
    
    //上传多媒体文件
    public function upload_media($type, $file)
    {
        $data = array("media"  => "@".dirname(__FILE__).'\\'.$file);
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->access_token."&type=".$type;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }
	//上传永久图文素材
	public function upload_news($data)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=".$this->access_token;
		$res = $this->https_request($url,$data);
		return json_decode($res,true);
	}
    //地理位置逆解析
    public function location_geocoder($latitude, $longitude)
    {
        $url = "http://api.map.baidu.com/geocoder/v2/?ak=B944e1fce373e33ea4627f95f54f2ef9&location=".$latitude.",".$longitude."&coordtype=gcj02ll&output=json";
        $res = $this->https_request($url);
        $result = json_decode($res, true);
        return $result["result"]["addressComponent"];
    }

    //https请求（支持GET和POST）
	//$data post请求参数
    protected function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);//post请求
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
		if($output === false) {
			return 'Curl error: ' . curl_error($ch) . "<br>\n\r";
		} else {
			 return $output;
		}
    }
	/**
	*插入一个access_token
	**/
	public function setAccess($arr)
	{
		$obj = M('access_token');//实例化模型
		return $obj->setAccess($arr);
	}
	/**
	*获得最后一条access_token
	*返回一个包含所信息的数组
	*/
	public function getLastAccess()
	{
		$obj = M('access_token');
		return $obj->getLastAccess();
	}
	/**
	*网页授权，base,获取code
	**/
	public function getCode()
	{
		//注意参数,注意编码
		$redirect_uri = urlencode("http://web1612191008206.bj01.bdysite.com/study/index.php?method=getOpenId");
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=lingsi#wechat_redirect";
		header('location:'.$url);
	}
	/**
	*获取OpenId等信息，返回数组
	**/
	public function getOpenId()
	{
		$code  = $_GET['code'];
		$state = $_GET['state'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=".$code."&grant_type=authorization_code";
		$res = $this->https_request($url);
		//$res =  file_get_contents($url);
		return json_decode($res,true);
	}
	/**获取网页详细信息的第一步**/
	public function getDetail()
	{
		//注意参数,注意编码
		$redirect_uri = urlencode("http://web1612191008206.bj01.bdysite.com/study/index.php?method=getUserInfo");
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=lingsi#wechat_redirect";
		header('location:'.$url);
	}
	/**获取详细信息第二步***/
	public function getUserInfo()
	{
		$res = $this->getOpenId();
		$access_token = $res['access_token'];//获取网页access_token
		$openid = $res['openid'];
		//获得用户详细信息
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
		$res = $this->https_request($url);
		//var_dump($res);
		return json_decode($res,true);
	}
}