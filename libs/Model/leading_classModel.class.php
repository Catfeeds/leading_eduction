<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_classModel extends tableModel
{
    private static $table = 'leading_class';
    
    /**
     * 根据字段数组获得相应的一条信息
     * return array
     */
    /* public function getInfo_byArr($arr,$where,$where2='')
    {
        return DB::fetchOne_byArr(self::$table,$arr,$where,$where2);
    } */
    /**
     * 根据字段数组获得相应的多条信息
     * return 多维数组
     */
   /*  public function getInfoAll_byArr($arr,$where,$where2='')
    {
        return DB::fetchAll_byArr(self::$table,$arr,$where,$where2);
    } */
}