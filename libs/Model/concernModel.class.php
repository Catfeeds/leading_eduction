<?php
namespace libs\Model;
use framework\libs\core\DB;

class concernModel
{
    private static $table = 'concern';
    
    /**
    * 获得我关注的名单
    * @date: 2017年5月15日 下午5:51:45
    * @author: lenovo2013
    * @param: string $con 我的账号
    * @return:array 
    */
    public function getCon($con)
    {
        $sql = "select concerned from ".self::$table." where concern = '".$con."'";
        return DB::fetchAll($sql);
    }
    /**
    * 获得关注我的所有名单
    * @date: 2017年5月15日 下午5:58:46
    * @author: lenovo2013
    * @param: string $con
    * @return:array
    */
    public function getConed($con)
    {
        $sql  ="select concern from ".self::$table." where concerned = '".$con."'";
        return DB::fetchAll($sql);
    }
    /**
     * 根据字段数组获得相应的一条信息
     */
    public function getInfo_byArr($arr,$where,$where2='')
    {
        return DB::fetchOne_byArr(self::$table,$arr,$where,$where2);
    }
    /**
     * 根据字段数组获得相应的多条信息
     */
    public function getInfoAll_byArr($arr,$where,$where2='')
    {
        return DB::fetchAll_byArr(self::$table,$arr,$where,$where2);
    }
}