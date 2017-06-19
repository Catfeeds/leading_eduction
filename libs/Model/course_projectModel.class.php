<?php
namespace libs\Model;
use framework\libs\core\DB;

class course_projectModel extends tableModel
{
    //表名
    private static $table = 'course_project';

    //表属性
    private static $course_project = array('id','projectId','courseId','teacherId');

    public function getTabArr($name)
    {
        return self::${$name};
    }
    public function fetchAll_byArrJoin($table,$arr,$where,$tableArr = null)
    {
        if (is_null($tableArr)) {
            $tableArr[0] = self::${$table[0]};
            $tableArr[1] = parent::getArr($table[1]);
        }
        return DB::fetchAll_byArrJoin($table,$arr,$where,$tableArr);
    }
    public function getNum($table,$arr,$where,$tableArr = null)
    {
        if (is_array($table) && is_null($tableArr)) {
            $tableArr[0] = self::${$table[0]};
            $tableArr[1] = parent::getArr($table[1]);
        }
        return DB::getNum($table,$arr,$where,$tableArr);
    }

    public function update($table,$arr,$where,$tableArr=null)
    {
        if(is_array($table) && is_null($tableArr)){
            $tableArr = array(self::${$table[0]},parent::getArr($table[1]));
        }
        return DB::update($table,$arr,$where,$tableArr);
    }


}