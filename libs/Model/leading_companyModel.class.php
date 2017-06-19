<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_companyModel extends tableModel
{
    //表名
    private static $table  = 'leading_company';
    private static $table2 = 'leading_company_info'; 
    //表属性
    private static $leading_company = array('id','compId','compName','mobile','email','password','caseId','status','token','token_exptime','dateinto');
    private static $leading_company_info = array('id','compId','unionCode','description','startTime','unionTime','address','picUrl','licenseUrl','tel','legalPerson');
    
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
    
    
    
    public function fetchOne_byArrJoin($table,$arr,$where,$tableArr)
    {
        if(is_null($tableArr)){
            $tableArr[0] = self::${$table[0]};
            $tableArr[1] = parent::getArr($table[1]);
        }
        return DB::fetchOne_byArrJoin($table,$arr,$where,$tableArr);
    }
    
    public function update($table,$arr,$where,$tableArr=null)
    {
        if(is_array($table) && is_null($tableArr)){
            $tableArr = array(self::${$table[0]},parent::getArr($table[1]));
        }
        return DB::update($table,$arr,$where,$tableArr);
    }
    
}