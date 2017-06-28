<?php
namespace libs\Model;
use framework\libs\core\DB;

class tuitionModel extends tableModel
{
    private static $table = 'tuition';
    
    private static $tuition = array('id','courseId','caseId','tuitionCase','teaching','tuitionMoney');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}