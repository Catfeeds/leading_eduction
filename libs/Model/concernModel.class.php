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
        return fetchAll($sql);
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
        return fetchAll($sql);
    }
}