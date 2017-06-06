<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_class_teacherModel extends tableModel
{
    //表名
    private static $table  = 'leading_class_teacher';
    private static $talbe2 = 'leading_class';
    private static $table2 = 'leading_student_info';
    //表结构
    private static $leading_class_teacher = array('classId','teacherId');
    private static $leading_class         = array('classId','courseId','className','startClassTime','masterId','classType','addressId','endClassTime');
    private static $leading_student_info = array('stuId','sex','age','otherMobile','classId','eduBacId','ecardId','bloodType','homeAddress','picUrl','qq','wechat','provinceId','description','ls_assess');

    
    public function fetchAll_byArrJoin($table,$arr,$where,$tableArr)
    {
        if(is_array($table) && is_null($tableArr)){
            $tableArr = array(self::${$table[0]},self::${$table[1]});
        }
        return DB::fetchAll_byArrJoin($table,$arr,$where,$tableArr);
    }
    
    public function fetchOne_byArrJoin($table,$arr,$where,$tableArr)
    {
        if(is_null($tableArr)){
            $tableArr[0] = self::${$table[0]};
            $tableArr[1] = self::${$table[1]};
        }
        return DB::fetchOne_byArrJoin($table,$arr,$where,$tableArr);
    }
    
}