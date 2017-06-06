<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_class_teacherModel extends tableModel
{
    //表名
    private static $table  = 'leading_class_teacher';
    private static $talbe2 = 'leading_class';
    
    //表结构
    private static $leading_class_teacher = array('classId','teacherId');
    private static $leading_class         = array('classId','courseId','className','startTime','masterId','classType','addressId');
    
    public function fetchAll_byArrJoin($table,$arr,$where,$tableArr)
    {
        if(is_array($table) && is_null($tableArr)){
            $tableArr = array(self::${$table[0]},self::${$table[1]});
        }
        return DB::fetchAll_byArrJoin($table,$arr,$where,$tableArr);
    }
    
}