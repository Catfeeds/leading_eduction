<?php
namespace libs\Model;
use framework\libs\core\DB;

class vedioModel extends tableModel
{
    private static $table = 'vedio';
    
    private static $vedio = array('id','vedioName','description','vedioUrl','status','dateiont','author','courseId','secCourseId','picUrl');
    
    
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