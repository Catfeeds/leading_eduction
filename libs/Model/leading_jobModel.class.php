<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_jobModel extends tableModel
{
    //表名
    private static $table  = 'leading_job';
    private static $table2 = 'leading_resume_log'; 
    
    //表属性
    private static $leading_job        = array('jobId','compId','jobName','status','people','duty','people','demand','treatment','workAddress','jobDate','eduBacId');
    private static $leading_resume_log = array('l_id','jobId','resumeTime','accNumber','caseId','r_status');
    
    public function fetchOne_byArrJoin($table,$arr,$where,$tableArr = null)
    {
        if(is_null($tableArr)){
            $tableArr[0] = self::${$table[0]};
            $tableArr[1] = self::${$table[1]};
        }
        return DB::fetchOne_byArrJoin($table,$arr,$where,$tableArr);
    }
    
    public function fetchAll_byArrJoin($table,$arr,$where,$tableArr = null)
    {
        if(is_array($table) && is_null($tableArr)){
            $tableArr = array(self::${$table[0]},self::${$table[1]});
        }
        return DB::fetchAll_byArrJoin($table,$arr,$where,$tableArr);
    }
    public function getNum($table,$arr,$where,$tableArr = null)
    {
        if (is_array($table)) {
            $tableArr = array(self::${$table[0]},self::${$table[1]});
        }
        return DB::getNum($table,$arr,$where,$tableArr);
    }
}