<?php
namespace libs\Model;
use framework\libs\core\DB;

class study_courseModel
{
    private static $table = 'study_course';
    
    /**
    * 根据学号获得该学生学习的所有课程
    * @date: 2017年5月16日 上午9:46:43
    * @author: lenovo2013
    * @param: string $stuId 学号
    * @return:array 多维数组 
    */
    public function getCourse_byStuId($stuId)
    {
        $sql = "select id,courseId,secCourseId from ".self::$table." where stuId = '{$stuId}' order by dateinto desc";
        return fetchAll($sql);
    }
    /**
     *根据字段数组信息  获得多条信息
     */
    public function getInfoAll_byArr($arr,$where,$where2)
    {
        return DB::fetchALL_byArr(self::$table,$arr,$where,$where2);
    }
}