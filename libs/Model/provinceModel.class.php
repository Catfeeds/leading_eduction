<?php
namespace libs\Model;
use framework\libs\core\DB;

class provinceModel extends tableModel
{
    private static $table = 'province';
    
    /**
    * 根据查找字段数组arr和条件数组where获得相关信息
    * @date: 2017年5月15日 下午4:28:58
    * @author: lenovo2013
    * @param: array $arr 查找字段数组
    * @param array $where 条件数组
    * @return:
    */
    /* public function getInfo_byArr($arr,$where)
    {  
        $sql = "select provinceId ";
        foreach($arr as $key=>$value){
            $sql .= ",".$value;
        }
        $sql .= " from ".self::$table." where 1=1 ";
        foreach($where as $key=>$value){
            $sql .= "and {$key} = '".$value."'";
        }
       return DB::fetchOne($sql);
    } */
}