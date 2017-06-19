<?php
namespace libs\Model;
use framework\libs\core\DB;

class provinceModel extends tableModel
{
    private static $table = 'province';
    
    private static $province = array('provinceId','province');
    
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}