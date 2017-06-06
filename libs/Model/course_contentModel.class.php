<?php
namespace libs\Model;
use framework\libs\core\DB;

class course_contentModel extends tableModel
{
    private static $table = 'course_content';
    
    /**
     *根据字段数组信息  获得多条信息
     */
   /*  public function getInfoAll_byArr($arr,$where,$where2)
    {
        return DB::fetchALL_byArr(self::$table,$arr,$where,$where2);
    }
	
	public function insert($arr)
	{
		return DB::insert(self::$table,$arr);
	} */
}