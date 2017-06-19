<?php
namespace libs\Model;
use framework\libs\core\DB;

class teacher_coursewareModel extends tableModel
{
    private static $table = 'teacher_courseware';
    
    private static $teacher_courseware = array('id','teacherId','secCourseId','description','url','caseId','dateinto');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}