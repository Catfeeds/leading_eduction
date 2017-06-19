<?php
namespace libs\Model;
use framework\libs\core\DB;

class course_contentModel extends tableModel
{
    private static $table = 'course_content';
    
    private static $course_content = array('id','courseId','stageId','content','focus');
    
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
    
    
    
    public function getNum($table,$arr,$where,$tableArr = null)
    {
        if (is_array($table)) {
            $tableArr = array(self::${$table[0]},parent::getArr($table[1]));
        }
        return DB::getNum($table,$arr,$where,$tableArr);
    }
}