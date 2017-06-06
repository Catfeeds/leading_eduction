<?php
namespace libs\Model;
use framework\libs\core\DB;

class student_workModel extends tableModel
{
    private static $table = 'student_work';
    
    /**
    * 根据字段数字获得相关信息
    * @date: 2017年5月16日 下午1:33:43
    * @author: lenovo2013
    * @param: variable
    * @return:
    */
    /* public function getInfo_byArr($arr,$where,$where2='')
    {
        return DB::fetchOne_byArr(self::$table,$arr,$where,$where2);
    } */
}