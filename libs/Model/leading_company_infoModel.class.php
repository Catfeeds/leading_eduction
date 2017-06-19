<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_company_infoModel extends tableModel
{
    private static $table = 'leading_company_info';
    
    private static $leading_company_info = array('id','compId','unionCode','description','startTime','unionTime','address','picUrl','licenseUrl','tel','legalPreson');
    
    
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
    
    
    
}