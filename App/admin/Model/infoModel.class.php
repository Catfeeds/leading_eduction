<?php
namespace App\admin\Model;

class infoModel
{
    /**
     * 实例化不同的模型，调用该模型的getInfo_byArr方法,只获得一条数据
     * return array
     */
    public function getInfo_byArr($table,$arr,$where,$where2 = '')
    {
        $obj = M("{$table}");
        return $obj->getInfo_byArr($arr,$where,$where2);
    }
    /**
     * 实例化不同的模型，调用该模型的getInfoAll_byArr方法,获得多条数据
     * retrun 多维数组
     */
    public function getInfoAll_byArr($table,$arr,$where,$where2 = '')
    {
        $obj = M("{$table}");
        return $obj->getInfoAll_byArr($arr,$where,$where2);
    }
    /**
     * 两个数据表的联合查询
     * @date: 2017年5月16日 上午11:05:41
     * @author: lenovo2013
     * @param: $where string 查询条件
     * @param $table1 左表名 $table2 右表名
     * @return:
     */
    public function getInfo_byArrJoin($arr,$where,$table1,$table2)
    {
        $obj = M("{$table1}");
        return $obj->getInfo_byArrJoin($arr,$where,$table1,$table2);
    }
}