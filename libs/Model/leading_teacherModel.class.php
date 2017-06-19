<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_teacherModel extends tableModel
{
    private static $table  = 'leading_teacher';
    private static $table2 = 'leading_teacher_info';
    private static $leading_teacher      = array('id','teacherId','name','password','caseId','status','mobile','email','dateinto','token','token_exptime');
    private static $leading_teacher_info = array('id','teacherId','sex','title','description','picUrl','age');
    
    public function fetchOne_byArrJoin($table,$arr,$where,$tableArr)
    {
        if(is_null($tableArr)){
            $tableArr[0] = self::${$table[0]};
            $tableArr[1] = parent::getArr($table[1]);
        }
        return DB::fetchOne_byArrJoin($table,$arr,$where,$tableArr);
    }
    
    public function update($table,$arr,$where,$tableArr=null)
    {
        if(is_array($table) && is_null($tableArr)){
            $tableArr = array(self::${$table[0]},parent::getArr($table[1]));
        }
        return DB::update($table,$arr,$where,$tableArr);
    }
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}