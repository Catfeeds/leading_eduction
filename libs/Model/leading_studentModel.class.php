<?php
namespace libs\Model;
use framework\libs\core\DB;

class leading_studentModel
{
    //所关联的表
    private static $table1 = 'leading_student';
    private static $table2 = 'leading_student_info';
    /* private static $table3 = 'student_project';
    private static $table4 = 'student_work';
    private static $table5 = 'student_eduction'; */
    private static $leading_student = array('id','stuId','name','password','mobile','email','password','status','caseId','dateinto','token','token_exptime');
    private static $leading_student_info = array('stuId','sex','age','otherMobile','classId','status','eduBacId','ecardId','bloodType','homeAddress','picUrl','qq','wechat','province','description');
    
    public function getInfo_byArrJoin($arr,$where,$talbe1='leading_student',$table2='leading_student_info')
    {
        $i = $j = 0;
        foreach($arr as $val){
            if(in_array($val,self::$leading_student)){
                $value[] = " s.{$val} ";
                $i++;
            }
            if(in_array($val,self::$leading_student_info)){
                $value[] = " f.{$val} ";
                $j++;
            }
        }
        $selectInfo = implode(',',$value);
        if( $j == 0 && $i > 0){//表名
            $table = '`'.$table1.'` as s ';
        }
        if($i==0 && $j > 0){
            $table = '`'.$table2.'` as f ';
        }
        if($i>0 && $j>0){//联合表名
            $table = self::$table1." as s ,".self::$table2." as f";
        }
        $sql = "select ".$selectInfo." from ".$table." where ".$where;
        return DB::fetchOne($sql);
    }
    
    /**
    * 函数用途描述
    * @date: 2017年5月14日 下午4:35:45
    * @author: lenovo2013
    * @param: array $arr 要获得信息的字段数组 "0" => "password"
    * @param: array $where 条件数组  "stuId"=>"xxxxxxx"
    * @return:
    */
    public function getInfo_byArr($arr,$where,$table='leading_student')
    {
        $table1 = !empty($table)?$table:self::$table1;
        $sql = "select id ";
        foreach($arr as $key=>$value){
            $sql .= ",".$value;
        }
        $sql .= " from ".$table1." where 1=1 ";
        foreach($where as $key=>$value){
            $sql .= "and {$key} = '".$value."'";
        }
       return DB::fetchOne($sql);
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
        return DB::update(self::$table1,$arr,$where);
    }
    /**
     * 实现联合查询，获得多条数据
     */
    public function getInfoAll_byArrJoin($arr,$where,$table1='',$table2 = '')
    {
        $table = !empty($table1)?$table1:self::$table;
        $table2 = !empty($table2)?$table2:self::$table2;
        $tableArr = self::${$table};
        $table2Arr = self::${$table2};
        return DB::fetchAll_byArrJoin($arr,$where,$table,$table2,$tableArr,$table2Arr);
    }
}