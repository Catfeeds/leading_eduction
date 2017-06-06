<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_student_infoModel extends tableModel
{
    private static $table = 'leading_student_info';
    
    /**
     * 根据字段数组获得相应的一条信息
     */
    /* public function getInfo_byArr($arr,$where,$where2='')
    {
        return DB::fetchOne_byArr(self::$table,$arr,$where,$where2);
    } */
}