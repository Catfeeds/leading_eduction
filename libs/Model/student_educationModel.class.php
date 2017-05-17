<?php
namespace libs\Model;
use framework\libs\core\DB;

class student_educationModel
{
    private static $table = 'student_education';
    
    /**
    * 根据字段参数获得相应的值
    * @date: 2017年5月16日 下午1:16:04
    * @author: lenovo2013
    * @param: array $arr 字段数组 $where1 array 查询条件1 $where2 条件2
    * @return:array
    */
    public function getInfo_byArr($arr,$where1,$where2 = '')
    {
        $where = '';
        $value = implode(',',$arr);
        foreach($where1 as $key=>$val){
            $where .= " and {$key} = '{$val}'";
        }
        $where = !empty($where2)?$where.$where2:$where;
        $sql = "select {$value} from ".self::$table." where 1 = 1 {$where}";
        return DB::fetchOne($sql);
    }
}