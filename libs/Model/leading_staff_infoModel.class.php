<?php 
namespace libs\Model;
use framework\libs\core\DB;

class leading_staff_infoModel extends tableModel
{
    private static $table = 'leading_staff_info';
    
    private $leading_staff_info = array('id','accNumber','name','mobile','otherTel','password','workTime','email','caseId','status','rangeId','token','token_exptime');
    
    
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}