<?php
namespace libs\Model;
use framework\libs\core\DB;

class access_tokenModel extends tableModel
{
	/**表名**/
	private static $table = 'access_token';
	/**表结构**/
	private static $id = 'id';
	private static $access_token = 'access_token';
	private static $time = 'access_time';
	/**
	*获得最后一个access_token和插入时间
	**/
	public function getLastAccess()
	{
		$sql = "SELECT * FROM ".self::$table." ORDER BY ".self::$time." DESC LIMIT 1";
		return DB::fetchOne($sql);
	}
	/**
	*插入一个access_token
	*插入成功返回最后插入id
	*/
	public function setAccess($arr)
	{
		$data = DB::insert(self::$table,$arr);
		return $data;
	}
}