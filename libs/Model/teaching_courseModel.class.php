<?php
namespace libs\Model;
use framework\libs\core\DB;

class teaching_courseModel extends tableModel
{
    private static $table = 'teaching_course';
    
    
    
    private static $teaching_course = array('id','teacherId','courseId','secCourseId','dateinto');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}