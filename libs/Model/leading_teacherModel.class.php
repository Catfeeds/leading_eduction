<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_teacherModel
{
    private static $table = 'leading_teacher';
    private static $table2 = 'leading_teacher_info';
    private static $leading_teacher = array('id','teacherId','name','password','caseId','status','mobile','email','dateinto','token','token_exptime');
    private static $leading_teacher_info = array('id','teacherId','sex','title','description','picUrl');
    
    /**
     * 根据字段数组获得相应的一条信息
     * return array
     */
    public function getInfo_byArr($arr,$where,$where2='')
    {
        return DB::fetchOne_byArr(self::$table,$arr,$where,$where2);
    }
    /**
     * 根据字段数组获得相应的多条信息
     * return 多维数组
     */
    public function getInfoAll_byArr($arr,$where,$where2='')
    {
        return DB::fetchAll_byArr(self::$table,$arr,$where,$where2);
    }
    /**
     * 更新数据
     * @date: 2017年5月12日 下午1:32:44
     * @author: lenovo2013
     * @param: arr array 要更新的字段和值
     * @param: string where 更新条件 id = '2'
     * @return:
     */
    public function updateInfo_byArr($arr,$where)
    {
        return DB::update(self::$table,$arr,$where);
    }
    /**
     * 实现联合查询，获得一条数据
     */
    public function getInfo_byArrJoin($arr,$where,$table1='',$table2 = '')
    {
        $table = !empty($table1)?$table1:self::$table;
        $table2 = !empty($table2)?$table2:self::$table2;
        $tableArr = self::${$table};
        $table2Arr = self::${$table2};
        return DB::fetchOne_byArrJoin($arr,$where,$table,$table2,$tableArr,$table2Arr);
    }
}