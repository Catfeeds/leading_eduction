<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_signModel extends tableModel
{
    private static $table = 'leading_sign';
    
    private static $leading_sign = array('id','name','mobile','qq','wechat','sign_type','sign_time','listenTime','addressId','courseId','classId');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
    
    
    
    
    
    
    
}