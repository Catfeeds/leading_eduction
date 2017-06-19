<?php
namespace framework\libs\db;

/**使用mysqli操作MySQL**/

class mysqli
{
	
	private static $link;//定义链接
	
	/**
	*构造函数，执行数据库连接函数
	*@params array $config 数据库的配置信息
	*@return object $link 数据库的链接
	**/
	public function __construct($config)
	{
		extract($config);
		self::$link = mysqli_connect($dbHost,$dbUser,$dbPsw,$dbName);//获得链接
		if(!self::$link){
			$this->err(mysql_error());
		}
		self::$link->set_charset($charset);//设置字符集
	}
	/**
	*报错信息
	*@params string $error 错误信息
	*@return void 
	**/
	public function err($error)
	{
		die("对不起，您操作有误，错误信息为：".$error);
	}
	
	//写入日志
	function loggerSql($log_content,$fileName = null)
    {
        if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            $max_size     = 10000;
            $log_filename = !empty($fileName)?$fileName:"sql.xml";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
            file_put_contents($log_filename, date('Y-m-d H:i:s')." ".$log_content."\r\n", FILE_APPEND);
        }
    }
	/**
	*mysql数据库的连接及设置字符集为utf8
	*@params array $config 			MySQL配置数组，格式为array('dbHost'=>服务器地址,'dbUser'=>用户名,'dbPsw'=>密码,'dbName'=>数据库名,'charset'=>字符集)
	@return bool
	**/
	public function connect($config)
	{
		 return self::$link;
	}
	
	
	/**
	*执行sql语句
	*@params string $sql sql语句
	*@return mixed $query 返回执行成功、资源或执行失败
	**/
	public function query($sql)
	{
		$query = '';
		$query = self::$link-> query($sql);//获得资源句柄
		if(!$query){//出错时
			$query = '';
			$this->loggerSql($sql,'error.xml');//modifyed
			$this->err($sql."<br />".mysql_error());
		}else{
		    $this->loggerSql($sql);
		}
		return $query;
	}
	
	
	
	/**
	*数据库的插入
	*@params string $table 要插入的表名
	*@params array  $arr 包含表明要插入的字段及值得一维数组
	*@return int 最后插入的id
	**/
	public function insert($table,Array $arr)
	{
		$sql = $this->createInsertSql($table,$arr);
		return $this->insertSql($sql);
	}
	public function insertSql($sql)
	{
		$this->query($sql);
		$inser_id = self::$link->insert_id;
		$res      = empty($insert_id)?self::$link->affected_rows:$inser_id;
		return $res;
	}
	
	/**
	*删除记录
	*@params array|string $table 表名
	*@params array $where 删除时的条件
	*@return int 受影响的记录条数
	**/
	public function deleteRow($table,$where)
	{
		$sql = $this->createDeleteSql($table,$where);//删除sql语句格式
		return $this->deleteRowSql($sql);
	}
	public function deleteRowSql($sql)
	{
		$this->query($sql);
		return self::$link->affected_rows;
	}
	
	/**
	*更新一条记录
	*@params string $table 表名
	*@params array $arr 要更新的字段及值
	*@params array $where 更新条件
	*@return int 返回更新后受影响的记录条数
	**/
	public function update($table,$arr,$where,$tableArr=null)
	{
		if(is_null($tableArr)){//单表更新
			$sql = $this->createUpdateSql($table,$arr,$where);//sql语句
		}else{                 //多表更新
			$sql = $this->createUpdateSqlMore($table,$arr,$where,$tableArr);
		}
		return $this->updateSql($sql);
	}
	public function updateSql($sql)
	{
		$this->query($sql);
		return self::$link->affected_rows;
	}
	
	/**
	*获得一条记录信息
	*@params source $query query函数执行后所获得的资源句柄
	*@return array 关联数组
	**/
	public function fetchOne($query)
	{
		$result = $query->fetch_assoc();//获得关联数组
		return $result;
	}
	/**
	*获得多条记录信息
	*@params source $query query函数执行后所获得的资源句柄
	*@return array 多维关联数组
	**/
	public function fetchAll($query)
	{
		$result = array();
		while($res = $query->fetch_assoc()){//有记录时存入结果数组
			$result[] = $res;
		}
		return $result;
	}
	/**
	* 根据字段数组获得相应的一条信息
	* @date: 2017年5月16日 下午1:44:09
	* @author: lenovo2013
	* @param: string $table 表名
	* @param: array $arr字段数组
	* @param: array $where1 查询条件数组
	* @param: string $where2 查询条件
	* @return:array
	*/
	public function fetchOne_byArr($table,$arr,$where)
	{
		$sql = $this->createFetchSql($table,$arr,$where);
	    return $this->fetchOne($this->query($sql));
	}
	/**
	 * 根据字段数组获得相应的多条信息
	 * @date: 2017年5月16日 下午1:44:09
	 * @author: lenovo2013
	 * @param: string $table 表名
	 * @param: array $arr字段数组
	 * @param: array $where1 查询条件数组
	 * @param: string $where2 查询条件
	 * @return:array
	 */
	public function fetchAll_byArr($table,$arr,$where)
	{
		$sql = $this->createFetchSql($table,$arr,$where,true);
	    return $this->fetchAll($this->query($sql));
	}
	
	
	
	
	/**
	*获得记录数目
	*@params source $query query函数执行后所获得的资源句柄
	*@return int 所有的记录数目
	**/
	public function getNums($query)
	{
		return $query->num_rows;
	}
	
	public function getNum($table,$arr,$where,$tableArr = null)
	{
	    if (is_null($tableArr)) {
	        $sql = $this->createFetchSql($table,$arr,$where);
	    } else {
	        $sql = $this->createFetchSqlMore($table,$arr,$where,$tableArr);
	    }
	    return $this->getNums($this->query($sql));
	}
	
	/**获得链接**/
	public function getLink()
	{
		return self::$link;
	}
	/**
	*联合查询，获得一条数据
	*/
	public function fetchOne_byArrJoin($table,$arr,$where,$tableArr)
	{
		$sql = $this->createFetchSqlMore($table,$arr,$where,$tableArr);
        return $this->fetchOne($this->query($sql));
	}
	/**
	*联合查询，获得多条数据
	*/
	public function fetchAll_byArrJoin($table,$arr,$where,$tableArr)
	{
		$sql = $this->createFetchSqlMore($table,$arr,$where,$tableArr);
        return $this->fetchAll($this->query($sql));
	}
	/**
	 *生成sql语句
	 *@param array|string $table
	 *@param array $arr sql中的字段数组
	 *@param array $where 条件数组
	 *   SELECT id,name from table where mobile = 'XXXXX' ORDER BY ID LIMIT 0,1;
	 *   SELECT s.id ,f.name FROM. table1 s,table2 f WHERE s.id = f.id AND s.id = xxx ORDER BY s.id ;
	 *   UPDATE table SET name = 'xxxx' where id = xx ;
	 *   UPDATE table1 s,table2 f SET s.name = 'xxxx',f.age = xxxxx WHERE s.id = f.id AND s.id = xxxx ;
	 *	 INSERT INTO table (`id`,`name`) VALUES (xx,'xxxxx');
	 *   DELETE FROM table WHERE id = xxx;
	 *	 DELETE f,s FROM table1 f,table2 s WHERE s.id = f.id AND s.id = xxx;
	 **/
	/**
	 *生成插入sql语句
	 *@param $table string 插入表
	 *@param array $arr 插入的字段数组
	 *@return string sql语句
	 **/
	public function createInsertSql($table,array $arr)
	{
		$sql = '';
		foreach($arr as $key=>$value){
			$value    = mysqli_real_escape_string(self::$link,$value);//转义 SQL 语句中使用的字符串中的特殊字符
			$keyArr[] = "`".$key."`";//把$arr中的所有key放在$keyArr数组中
			$valArr[] = "'".$value."'";
		}
		$keys   = $this->implodeArr($keyArr);//把字段所在的数组合成一个字符串
		$values = $this->implodeArr($valArr);
		$sql    = "INSERT INTO `{$table}` (".$keys.") VALUES(".$values.")";//要插入的sql语句
		return $sql;
	}
	/**
	 *生成删除sql语句
	 *@param array|string $table 
	 *@param array $where 
	 *   DELETE FROM table WHERE id = xxx;
	 *	 DELETE f,s FROM table1 f,table2 s WHERE s.id = f.id AND s.id = xxx;
	 *@return string 
	 **/
	public function createDeleteSql($table,$where)
	{
		$sql      = '';
		$whereSql = '';//modifyed
		if(is_string($table)){//单表删除
			foreach($where as $key=>$val){
				$val       = mysqli_real_escape_string(self::$link,$val);
				$whereSql .= "AND `{$key}` = '{$val}'";
			}
			$sql = "DELETE FROM {$table} WHERE 1 = 1 {$whereSql}";
		}else{//多表删除,只是两个表,可以改进
			$tableSql = $table[0].' f,'.$table[1].' s ';
			foreach($where as $key=>$val){
				$val       = mysqli_real_escape_string(self::$link,$val);
				$whereSql .= "AND s.`{$key}` = f.`{$key}` AND s.`{$key}` = '{$val}' ";
			}
			$sql = "DELETE f,s FROM {$tableSql} WHERE 1 = 1 {$whereSql}";
		}
		return $sql;
	}
	/**
	 *生成修改sql语句
	 *   UPDATE table SET name = 'xxxx' where id = xx ;
	 *   
	 *
	 **/
	public function createUpdateSql($table,$arr,$where)
	{
		$sql      = '';
		$whereSql = '';
		foreach($arr as $key=>$value){
			$value       = mysqli_real_escape_string(self::$link,$value);//过滤sql语句的值
			$keyAndVal[] = "`{$key}`='{$value}'";
		}
		$keyAndVals = $this->implodeArr($keyAndVal);//把数组合成一个字符串
		foreach($where as $key => $val){
			$val       = mysqli_real_escape_string(self::$link,$val);
			$whereSql .= " AND `{$key}` = '{$val}'";
		}
		$sql = "UPDATE `{$table}` SET {$keyAndVals} WHERE 1 = 1 {$whereSql}";//sql语句
		return $sql;
	}
	/**
	 *@param array $arr 更新字段数组与值
	 *@param array $where 条件数组
	 *@param array $table 更新的表
	 *@param $tableArr array 二维数组
	 *	UPDATE table1 s,table2 f SET s.name = 'xxxx',f.age = xxxxx WHERE s.id = f.id AND s.id = xxxx ;
	 ***/
	public function createUpdateSqlMore($table,$arr,$where,$tableArr)
	{
		$sql      = '';
		$whereSql = '';
		$i = $j = 0;
		foreach($arr as $key=>$val){
			$val = mysqli_real_escape_string(self::$link,$val);
			if(in_array($key,$tableArr[0])){
				$keyAndVal[] = " s.`{$key}` = '{$val}' ";
				$i++;
				//continue;//modifyed
			}
			if(in_array($key,$tableArr[1])){
				$keyAndVal[] = " f.`{$key}` = '{$val}' ";
				$j++;
			}
		}
		$keyAndVals = $this->implodeArr($keyAndVal);//把数组合成一个字符串
		
		$whereSql = $this->formatWhere($where,$tableArr,$i,$j);
		
		//获得表名
		$tableVal = $this->formatTable($table,$i,$j);
		
		$sql = 'UPDATE '.$tableVal.' SET '.$keyAndVals.' WHERE 1 = 1 '.$whereSql;
		return $sql;
	}
	/**
	 *生成单表查询语句，
	 *@param $table string 表名
	 *@param $arr array 查询数组
	 *@return string sql语句
	 **/
	public function createFetchSql($table,$arr,$where,$distinct=false)
	{
		$sql      = '';
		$whereSql = '';
		foreach($arr as $val){
			$val         = mysqli_real_escape_string(self::$link,$val);
			$selectVal[] = ($val == '*')?'*':"`{$val}`";//modified here
		}
	    $selectVals = $this->implodeArr($selectVal);
	    $whereSql = $this->formatWhereSql($where);
		if($distinct){
			$selectVal = " DISTINCT {$selectVals} ";
		}
		$sql = "SELECT {$selectVals} FROM `{$table}` where 1 = 1 {$whereSql}";//modified here
		return $sql;
	}
	/**
	 *生成多表查询语句
	 *SELECT s.id ,f.name FROM table1 s,table2 f WHERE s.id = f.id AND s.id = xxx ORDER BY s.id ;
	 *@param $table array 数据表名
	 *@param $arr array 需要查询的字段
	 *@param $where string 条件
	 *@param $tableArr array 二维数组 每一个数据表中的所有字段
	 *@return string sql语句
	 **/
	public function createFetchSqlMore($table,$arr,$where,$tableArr)
	{
		$sql = '';
		$i = $j = 0;
		foreach($arr as $val){
			$val = mysqli_real_escape_string(self::$link,$val);
            if(in_array($val,$tableArr[0])){
                $value[] = " s.`{$val}` ";
                $i++;
               // continue;
            }
            if(!empty($tableArr[1]) && in_array($val,$tableArr[1])){
                $value[] = " f.`{$val}` ";
                $j++;
            }
        }
		$selectVals = $this->implodeArr($value);
		//获得表名
		$tableVal   = $this->formatTable($table,$i,$j);
		$whereSql   = is_string($where)?$where:$this->formatWhere($where,$tableArr,$i,$j);
        $sql        = "SELECT ".$selectVals." FROM ".$tableVal." WHERE  1=1 ".$whereSql;
		return $sql;
	}
	/**
	 *把数组合成一个字符串
	 *@param array $arr
	 *@return string 
	 **/
	public function implodeArr($arr)
	{
		$res = '';
		$count = count($arr);
		if($count > 1){
			$res = implode(',',$arr);
		}else{
			$res = implode(' ',$arr);
		}
		return $res;
	}
	
	/**
	 * 格式sql中的表名
	 * @param array $table
	 * @param int $i
	 * @param int $j
	 * @return string
	 */
	public function formatTable($table,$i,$j)
	{
	    $tableVal = '';
	    if( $j == 0 && $i > 0){//表名
	        $tableVal = '`'.$table[0].'` as s ';
	    }
	    if($i==0 && $j > 0){
	        $tableVal = '`'.$table[1].'` as f ';
	    }
	    if($i>0 && $j>0){//联合表名
	        $tableVal = '`'.$table[0]."` as s ,`".$table[1]."` as f";
	    }
	    return $tableVal;
	}
	
	/**
	 * 格式sql语句where条件
	 * @param array $where
	 * @param array $tableArr
	 * @param int $i
	 * @param int $j
	 * @return string
	 */
	public function formatWhere($where,$tableArr,$i,$j)
	{
	    $whereSql = '';
	    foreach($where as $key=>$val){
	        $val = mysqli_real_escape_string(self::$link,$val);
	        if($key == 'where2'){//modifyed here
	            $whereSql .= $val;
	        }else{
	            if ($i > 0 && $j > 0) {
                    if (in_array($key, $tableArr[0]) && in_array($key, $tableArr[1])) {
                        $whereSql .= " AND s.`{$key}` = f.`{$key}` AND s.`{$key}` = '{$val}' ";
                    } else {
                        $whereSql .= $this->formatWhereKey($i, $j, $key, $val, $tableArr);
                    }
                } else {
                    $whereSql .= $this->formatWhereKey($i, $j, $key, $val, $tableArr);
                }
	        }
	    }
	    return $whereSql;
	}
	
	public function formatWhereKey($i, $j, $key, $val, $tableArr)
    {
        $whereSql = '';
        if ($i > 0 && in_array($key, $tableArr[0])) {
            $whereSql .= " AND s.`{$key}` = '{$val}' ";
        }
        if ($j > 0 && in_array($key, $tableArr[1])) {
            $whereSql .= " AND f.`{$key}` = '{$val}' ";
        }
        return $whereSql;
    }
	
    public function formatWhereSql($where)
    {
        $whereSql = '';
        foreach($where as $key=>$val){
            $val = mysqli_real_escape_string(self::$link,$val);
            if($key == 'where2'){
                $whereSql .= " {$val}";//modify here
            }else{
                $whereSql .= " AND `{$key}` = '{$val}'";
            }
        }
        return $whereSql;
    }
    
    
}


























