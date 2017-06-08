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
    private static $leading_resume_log = array('id','jobId','resumeTime','accNumber','caseId','r_status');
    
    public function fetchOne_byArrJoin($table,$arr,$where,$tableArr)
    {
        if(is_null($tableArr)){
            $tableArr[0] = self::${$table[0]};
            $tableArr[1] = self::${$table[1]};
        }
        return DB::fetchOne_byArrJoin($table,$arr,$where,$tableArr);
    }
    
    
}