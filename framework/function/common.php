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
    
    /**
     * 验证该时间戳与当前时间是否超过多少天
     * @param int $timestamp 时间戳
     * @param number $interval 默认是30天
     * return boolean 没超过intval 返回true
     */
    function verifyInterVal($timestamp,$interval = 30)
    {
        $res  = false;
        $diff = abs(time() - intval($timestamp));
        $max  = 30 * 24 * 60 * 60;
        if ($diff < $max) {
            $res = true;
        }
        return $res;
    }
    /**
     * 验证该时间戳代表的日期是否与当前是同一个月
     * @param int $timestamp
     * @return boolean  同一个月 返回true
     */
    function verifyInMonth($timestamp)
    {
        $res       = false;
        $nowMonth  = date('y-m');
        $lastMonth = date('y-m',$timestamp);
        if ($nowMonth == $lastMonth) {
            $res = true;
        }
        return $res;
    }
    /**
     * 验证该时间戳代表的日期是否与当前是同一天
     * @param int $timestamp
     * @return boolean  同一天 返回true
     */
    function verifyInDay($timestamp)
    {
        $res       = false;
        $nowMonth  = date('y-m-d');
        $lastMonth = date('y-m-d',$timestamp);
        if ($nowMonth == $lastMonth) {
            $res = true;
        }
        return $res;
    }
    