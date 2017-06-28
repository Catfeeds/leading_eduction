<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_addressModel extends tableModel
{
    private static $table = 'leading_address';
    
    private static $leading_address = array('addressId','address');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}