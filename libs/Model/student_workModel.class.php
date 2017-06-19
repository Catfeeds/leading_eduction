<?php
namespace libs\Model;
use framework\libs\core\DB;

class student_workModel extends tableModel
{
    private static $table = 'student_work';
    
    private static $student_work = array('id','stuId','compName','compAddress','jobName','salary','treatment','dateWork','workOut','description'); 
    
    
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}