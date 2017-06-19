<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_student_infoModel extends tableModel
{
    private static $table = 'leading_student_info';
    
    private static $leading_student_info = array('id','stuId','sex','age','otherMobile','classId','eduBacId','ecardId','bloodType','homeAddress','picUrl','qq','wechat','provinceId','description');
    
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
    
}