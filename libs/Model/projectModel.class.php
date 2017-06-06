<?php
namespace libs\Model;
use framework\libs\core\DB;

class projectModel extends tableModel
{
    private static $table = 'project';
    
    /**
     * 根据字段数组获得相应的一条信息
     */
    /* public function getInfo_byArr($arr,$where,$where2='')
    {
        return DB::fetchOne_byArr(self::$table,$arr,$where,$where2);
    } */
    /**
     * 根据字段数组获得相应的多条信息
     */
   /*  public function getInfoAll_byArr($arr,$where,$where2='')
    {
        return DB::fetchAll_byArr(self::$table,$arr,$where,$where2);
    } */
}