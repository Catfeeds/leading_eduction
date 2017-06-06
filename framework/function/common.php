<?php
    use framework\libs\core\VIEW;
    use framework\libs\core\DB;
    
    /**
    * 给返回参数加入成功提示
    * @date: 2017年5月12日 下午6:53:53
    * @author: lenovo2013
    * @param: array 默认为空
    * @return:array
    */
    function myMerge($data = array())
    {
        if(!(is_array($data) && isset($data['status']) && ($data['status'] > 0))){
            $data['status'] = 0;
            $data['msg'] = 'success';
        }
        return $data;
    }
	
    /**
    * 获得文件扩展名
    * @date: 2017年5月15日 上午9:57:07
    * @author: lenovo2013
    * @param: string 文件名
    * @return:string 文件后缀名
    */
	function getExt($fileName)
	{
		$arr = explode('.',$fileName);
		return strtolower(array_pop($arr)); 
	}
	/**
	* 获得唯一标识符
	* @date: 2017年5月15日 上午10:08:41
	* @author: lenovo2013
	* @return:string 
	*/
	function getUniName()
	{
	    return md5(uniqid(microtime(true),true));
	}
	//日志文件
	function loggerSql($log_content)
    {
        if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            $max_size = 10000;
            $log_filename = "sql.xml";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
            file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
        }
    }
    
    /**
     * 验证字符的长度是否在[$min,$max]之间
     * @param string  $accNumber
     * @param int $min
     * @param int $max
     * return boolean
     */
    function verifyLen($accNumber,$min,$max)
    {
        $res = false;
        $len = strlen(strval($accNumber));
        if($len >=$min && $len <= $max){
            $res = true;
        }
        return $res;
    }