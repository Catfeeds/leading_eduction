<?php
namespace libs\Model;
use framework\libs\core\DB;

class recommendModel extends tableModel
{
    private static $table = 'recommend';
    
    private static $recommend = array('id','recommendId','stuId','dateinto');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}