<?php
	use framework\libs\core\VIEW;
	use framework\libs\core\DB;
	/**
	实例化控制器，且调用该控制器的$method方法
	
	@params string $name控制器名称
	@params string $method控制器中的方法
	原则上控制器的方法不能有参数
	@return void
	**/
	/* function C($module,$name,$method)
	{
		$class = "App\\{$module}\\Controller\\$name".'Controller';
		try {
		    echo inverse(5) . "\n";
		    echo inverse(0) . "\n";
		} catch (error $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		exit();
		try {
		    $obj = new $class();//实例化
		    throw new Exception($error);
		} catch (Exception $e) {
		    $obj = new App\admin\Controller\adminController;    
		}
		//$obj   = new $class();//实例化
		try {
		    $obj -> $method();
		} catch (Exception $e) {
		    $obj -> index();
		}
		
	} */
	function C($module,$name,$method)
	{
		$class = "App\\{$module}\\Controller\\$name".'Controller';
		$obj   = new $class();//实例化
		$obj   -> $method();
	} 

	
	
	
	/**
	实例化模型
	
	@params string $name模型名称
	
	@return object 实例化后的对象
	**/

	function M($name)
	{
		$class = "libs\\Model\\$name".'Model';
		$obj = new $class();//实例化
		return $obj;
	}
	
	/**
	实例化视图
	
	@params string $name视图名称
	
	@return object 实例化后的对象
	**/
	function V($name)
	{
		$class = "libs\\View\\$name".'View';
		$obj = new $class();//实例化
		return $obj;
	}
	
	/**
	
	//对特殊字符进行转义
	@params string $str 要转义的字符
	@return string 操作后的字符
	
	**/
	function daddslashes($str)
	{
		return (!get_magic_quotes_gpc())?addslashes($str):$str;//当魔法符号打开时，会自动对特殊字符进行转义
	}
	
	/**
	实例化第三方类
	@params string $path 第三方类的路径
	@params string $name 第三方类的名称
	@params array  $params 第三方类初始化需要指定、赋值的属性
		格式为 array(属性名=>属性值，......)
	@return Object $obj  第三方类实例化后的对象
	**/
	
	function ORG($path,$name,$params=array())
	{
		require_once('libs/ORG/'.$path.$name.'.class.php');//引入第三方类主文件
		$obj = new $name();
		if(!empty($params)){
			foreach($params as $key=>$value){
				$obj->$key = $value;
			}
		}
		return $obj;
	}
	/**
	 * 获得当前格式化的日期
	 */
	function getFormatDate()
	{
	    date_default_timezone_set('Asia/Shanghai');//设置时区
	    return date('m/d/Y',time());
	}
	
	/**
	 * 获得分页的页容量信息
	 * @param string $table 表名
	 * @param int $page 当前是第几页
	 * @param number $pageSize 每页信息条数
	 * @param string $where1 查询条件 格式是 '**** = ****'注意前后空格
	 * @param string $where2 查询结果排序等操作如order by 等
	 * @return array 页容量信息数组
	 */
	function showPages($table,$page,$pageSize=8,$where1='',$where2='')
	{
	    $page = ($page > 0)?$page:1;//page小于1时默认为1
	    $offset = ($page -1)*$pageSize;//偏移量
	    if($where1){//查询条件
	        $where = 'where '.$where1   ;
	        if($where2){
	            $where = $where.$where2;
	        }
	    }else{
	        if($where2){
	            $where = $where2;
	        }else{
	            $where = '';
	        }
	    }
	    $sql = "select * from {$table} {$where} limit $offset,$pageSize";
	    return DB::fetchAll($sql);
	}
	
	function showPage($table,$arr,$where,$page=1,$pageSize=8)
	{
	    $page   = ($page > 0)?$page:1;         //page小于1时默认为1
	    $offset = ($page -1)*$pageSize;        //偏移量
	    if (!empty($where['where2'])) {
	        $where['where2'] .= " LIMIT {$offset},{$pageSize}";
	    } else {
	        $where['where2']  = " LIMIT {$offset},{$pageSize}";
	    }
	    if (is_array($table)) {
	        $obj = M("{$table[0]}");
	        return $obj->fetchAll_byArrJoin($table,$arr,$where);
	    } else {
	        $obj = M("{$table}");
	        return $obj->fetchAll_byArr($table,$arr,$where);
	    }
	}
	
	/**
	 * 获得分页时页码信息
	 * @param unknown $table
	 * @param unknown $page
	 * @param unknown $url
	 * @param number $pageSize
	 * @param string $where
	 * @return string
	 */
	function getPages($table,$page,$pageSize=8,$where='')
	{
	    //$url .= "&table={$table}";
        $p   = '';
	    $sql = "select * from {$table}";
	    $totalNums = DB::getNums($sql);//总条数
	    $page = ($page > 0)?$page:1;//page小于1时默认为1
	    $page = ($page > $totalNums)?$totalNums:$page;//当page大于总记录条数时默认为总记录数
	    $pageNums = ceil($totalNums/$pageSize);//总页数
	    $index = ($page == 1)?'首页':"<a href='javascript:SHMTU.GLOBAL.page(\"1\");'>首页</a>";//首页
	    $pre = $page -1;
	    $pre = ($page == 1)?'上一页':"<a href='javascript:SHMTU.GLOBAL.page(\"{$pre}\");'>上一页</a>";//上一页
	    $next = $page + 1;
	    $next = ($page == $pageNums)?'下一页':"<a href='javascript:SHMTU.GLOBAL.page(\"{$next}\");'>下一页</a>";//下一页
	    $last = ($page == $pageNums)?'尾页':"<a href='javascript:SHMTU.GLOBAL.page(\"{$pageNums}\");'>尾页</a>";//尾叶
	    
	    for($i = 1;$i <= $pageNums;$i++){
	        if($page == $i){//当前页无连接
	            @$p .= "[$i]" ;
	        }else{
	            @$p .= "<a href='javascript:SHMTU.GLOBAL.page(\"{$i}\");'>[{$i}]</a>";
	        }
	    }
	    
	    $result = "当前是第{$page}页,总共{$pageNums}页<br />{$index}{$pre}{$p}{$next}{$last}";
	    return $result;
	}
	
	
	/**
	 * 获得分页页码信息
	 * @param array|string $table 表名
	 * @param array $arr       查询数组
	 * @param array $where     查询条件
	 * @param number $page     当前页
	 * @param number $pageSize 页容量
	 * @return multitype:array
	 */
	function getPage($table,$arr,$where,$page = 1,$pageSize = 8)
	{
	    $pages              = array();
	    if (is_array($table)) {
	        $obj = M("{$table[0]}");
	    } else {
	        $obj = M("{$table}");
	    }
	    $totalNums          = $obj->getNum($table,$arr,$where);                //总条数
	    $page               = ($page > 0)?$page:1;                             //page小于1时默认为1
	    $page               = ($page > $totalNums)?$totalNums:$page;           //当page大于总记录条数时默认为总记录数
	    $pages['totalNums'] = $totalNums;
	    $pages['pageNums']  = ceil($totalNums/$pageSize);                      //总页数
	    $pages['page']      = $page;                                           //当前页
	    $pages['index']     = 1;                                               //首页
	    $pages['pre']       = $page -1;                                        //上一页
	    $pages['next']      = $page + 1;                                       //下一页
	    $pages['last']      = $pages['pageNums'];                              //尾叶
	    return $pages;
	}
	
	/**
	 * 分页函数
	 * @param array|string $table  表名
	 * @param array $arr           
	 * @param array $where
	 * @param number $page         当前页
	 * @param number $pageSize     页容量
	 * @return array
	 */
	function page($table,$arr,$where,$page = 1,$pageSize = 8)
	{
	    $data          = array();
	    $data          = showPage($table,$arr,$where,$page=1,$pageSize=8);
	    if (count($data) > 0 ) {
	        $data['pages'] = getPage($table,$arr,$where,$page,$pageSize);
	    }
	    return $data;
	}
	
	
	/**
	*md5密钥加密
	***/
	function myMd5($string,$salt=null)
	{
		$salt = empty($salt)?'ls5698':$salt;
		return md5(md5($string).$salt);
	}
	
	/**
	 *验证手机号是否合格
	 */
	function isMobile($mobile)
	{
		$res = '';
		$len = is_string($mobile)?strlen(trim($mobile)):0;
		if($len == 11){
			$res = preg_match('/^1[34578]{1}\d{9}$/',trim($mobile));
		}
		return $res;
	}

    /**
     * 验证字符串是否符合是邮箱格式
     * @param string $email
     * @return bool 符合返回true
     */
	function isEmail($email)
	{
		if (filter_var($email,FILTER_VALIDATE_EMAIL))
		    return true;
	}