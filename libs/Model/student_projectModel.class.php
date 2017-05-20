<?php
namespace libs\Model;
use framework\libs\core\DB;

class student_projectModel
{
    private static $table = 'student_project';
    
    /**
     *根据字段数组信息  获得多条信息
     */
    public function getInfoAll_byArr($arr,$where,$where2)
    {
        return DB::fetchALL_byArr(self::$table,$arr,$where,$where2);
    }
}