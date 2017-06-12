<?php
namespace framework\libs\core;
//use framework\libs\db\mysqli;
/**实现DB工厂类**/
class DB
{
	public static $db;//
	public static $link;
	
	/**
	*实例DB类
	*@params string $dbType 数据库类
	*@params array  $config 该类数据库的配置信息
	*@return void
	**/
	public static function init($dbType,$config)
	{
		$class = "framework\\libs\\db\\"."$dbType";
		self::$db = new $class($config);//这样调用时一定要注意写全地址
		
		self::$link = self::$db -> getLink();
		// return self::$link;
	}
	
	/**
	 *封装query函数
	 **/
	public static function query($sql)
	{
		return self::$db->query($sql);
	}
	
	/**
	 *封装insert函数
	 **/
	public static function insert($table,$arr)
	{
		return self::$db->insert($table,$arr);
	}
	public static function insertSql($sql)
	{
		return self::$db->insertSql($sql);
	}
	
	/**
	 *封装deleteRow函数
	 **/
	public static function deleteRow($table,$where)
	{
		return self::$db->deleteRow($table,$where);
	}
	public static function deleteRowSql($sql)
	{
		return self::$db->deleteRowSql($sql);
	}
	
	/**
	 *封装update函数
	 **/
	public static function update($table,$arr,$where,$tableArr=null)
	{
		return self::$db->update($table,$arr,$where,$tableArr);
	}
	public static function updateSql($sql)
	{
		return self::$db->updateSql($sql);
	}
	/**
	 *封装fetchOne函数
	 **/
	public static function fetchOne($sql)
	{
		$query = self::$db->query($sql);
		return self::$db->fetchOne($query);
	}
	/**
	 *封装fetchAll函数
	 **/
	public static function fetchAll($sql)
	{
		$query = self::$db->query($sql);
		return self::$db->fetchAll($query);
	}
	/**
	 *封装fetchOne_byArr函数
	 *
	 ***/
	public static function fetchOne_byArr($table,$arr,$where)
	{
		return self::$db->fetchOne_byArr($table,$arr,$where);
	}
	
	/**
	 *封装fetchAll_byArr函数
	 *
	 */
	public static function fetchAll_byArr($table,$arr,$where)
	{
		return self::$db->fetchAll_byArr($table,$arr,$where);
	}
	
	/**
	 *封装fetchOne_byArrJoin函数
	 *
	 *
	 **/
	public static function fetchOne_byArrJoin($table,$arr,$where,$tableArr)
	{
		return self::$db->fetchOne_byArrJoin($table,$arr,$where,$tableArr);
	}
	
	/**
	 *封装fetchAll_byArrJoin函数
	 *
	 */
	public static function fetchAll_byArrJoin($table,$arr,$where,$tableArr)
	{
		return self::$db->fetchAll_byArrJoin($table,$arr,$where,$tableArr);
	}
	
	
	/**
	 *封装getNums函数
	 *
	 */
	public static function getNums($sql)
	{
		$query = self::$db->query($sql);
		return self::$db->getNums($query);
	}
	
	
	public static function getNum($table,$arr,$where,$tableArr=null)
	{
	    return self::$db->getNum($table,$arr,$where,$tableArr);
	}
	

	
	
}
















