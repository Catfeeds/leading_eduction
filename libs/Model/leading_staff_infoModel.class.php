<?php 
namespace libs\Model;
use framework\libs\core\DB;

class leading_staff_infoModel
{
    private static $table = 'leading_staff_info';
    
    /**
    * 根据不同传参获得不同的信息
    * @date: 2017年5月11日 下午10:11:40
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
        $sql .= " from ".self::$table." where 1=1 ";
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
        return DB::update(self::$table,$arr,$where);
    }
}