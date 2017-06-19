<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_studentModel extends tableModel
{
    //所关联的表
    private static $table1 = 'leading_student';
    private static $table2 = 'leading_student_info';
    
    //表属性数组
    private static $leading_student      = array('id','stuId','name','password','mobile','email','password','status','caseId','dateinto','token','token_exptime');
    private static $leading_student_info = array('stuId','sex','age','otherMobile','classId','eduBacId','ecardId','bloodType','homeAddress','picUrl','qq','wechat','provinceId','description','ls_assess');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
    
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
    
    public function fetchAll_byArrJoin($table,$arr,$where,$tableArr = null)
    {
        if(is_array($table) && is_null($tableArr)){
            $tableArr = array(self::${$table[0]},parent::getArr($table[1]));
        }
        return DB::fetchAll_byArrJoin($table,$arr,$where,$tableArr);
    }
    public function getNum($table,$arr,$where,$tableArr = null)
    {
        if (is_array($table)) {
            $tableArr = array(self::${$table[0]},parent::getArr($table[1]));
        }
        return DB::getNum($table,$arr,$where,$tableArr);
    }
    
}