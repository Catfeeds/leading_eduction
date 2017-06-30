<?php
namespace libs\Model;
use framework\libs\core\DB;
class leading_teacher_infoModel
{
    private static $talbe = 'leading_teacher_info';
    
    private static $leading_teacher_info = array('id','teacherId','sex','title','description','picUrl','age');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
    
    public function update($table,$arr,$where,$tableArr=null)
    {
        if(is_array($table) && is_null($tableArr)){
            $tableArr = array(self::${$table[0]},parent::getArr($table[1]));
        }
        return DB::update($table,$arr,$where,$tableArr);
    }
}