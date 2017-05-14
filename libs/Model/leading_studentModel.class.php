<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_studentModel
{
    //所关联的表
    private static $table1 = 'leading_student';
    private static $table2 = 'leading_student_info';
    private static $table3 = 'student_project';
    private static $table4 = 'student_work';
    private static $table5 = 'student_eduction';
    
    /**
    * 函数用途描述
    * @date: 2017年5月14日 下午4:35:45
    * @author: lenovo2013
    * @param: array $arr 要获得信息的字段数组 "0" => "password"
    * @param: array $where 条件数组  "stuId"=>"xxxxxxx"
    * @return:
    */
    public function getInfo_byArr($arr,$where)
    {
        $sql = "select id ";
        foreach($arr as $key=>$value){
            $sql .= ",".$value;
        }
        $sql .= " from ".self::$table1." where 1=1 ";
        foreach($where as $key=>$value){
            $sql .= "and {$key} = '".$value."'";
        }
       return DB::fetchOne($sql);
    }
    public function getInfo_byArrJoin($arr,$where)
    {
        $sql = "select id";
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
}