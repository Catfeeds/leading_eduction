<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_companyModel extends tableModel
{
    private static $table = 'leading_company';
    
    /**
     * 根据字段数组获得相应的一条信息
     */
    /* public function getInfo_byArr($arr,$where,$where2='')
    {
        return DB::fetchOne_byArr(self::$table,$arr,$where,$where2);
    } */
    /**
     * 根据字段数组获得相应的多条信息
     */
    /* public function getInfoAll_byArr($arr,$where,$where2='')
    {
        return DB::fetchAll_byArr(self::$table,$arr,$where,$where2);
    } */
    /**
     * 更新数据
     * @date: 2017年5月12日 下午1:32:44
     * @author: lenovo2013
     * @param: arr array 要更新的字段和值
     * @param: string where 更新条件 id = '2'
     * @return:
     */
    /* public function updateInfo_byArr($arr,$where)
    {
        return DB::update(self::$table,$arr,$where);
    } */
}