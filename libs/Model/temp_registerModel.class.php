<?php
namespace libs\Model;
use framework\libs\core\DB;

class temp_registerModel extends tableModel
{
    private static $table = 'temp_register';
    
    private static $temp_register = array('id','tmpId','recommendId','caseId','mobile','qq','wechat','email','name','password','dateinto','status','token','token_exptime');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}