<?php
namespace libs\Model;
use framework\libs\core\DB;

class course_contentModel extends tableModel
{
    private static $table = 'course_content';
    
    public function getNum($table,$arr,$where,$tableArr = null)
    {
        if (is_array($table)) {
            $tableArr = array(self::${$table[0]},self::${$table[1]});
        }
        return DB::getNum($table,$arr,$where,$tableArr);
    }
}