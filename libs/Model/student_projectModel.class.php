<?php
namespace libs\Model;
use framework\libs\core\DB;

class student_projectModel extends tableModel
{
    private static $table1          = 'student_project';
    private static $table2          = 'project';
    private static $student_project = array("id","projectId","stuId","assess","stuDescription","professional");
    private static $project         = array("projectId","projectName","courseId","teacherId","description","status","startTime","endTime","picUrl","url","people");
    
    public function fetchAll_byArrJoin($table,$arr,$where,$tableArr)
    {
        if(is_null($tableArr)){
            $tableArr[0] = self::${$table[0]};
            $tableArr[1] = self::${$table[1]};
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
    
    public function update($table,$arr,$where,$tableArr = null)
    {
        if(is_array($table) && is_null($tableArr)){
            $tableArr = $this->getTableArr($table);
        }
        return DB::update($table,$arr,$where,$tableArr);
    }
    
    public function getTableArr($table)
    {
        $tableArr = array();
        $tableArr[0] = self::${$table[0]};
        $tableArr[1] = self::${$table[1]};
        return $tableArr;
    }
}