<?php
namespace libs\Model;

class courseModel extends tableModel
{
    private static $table = 'course';
    
    private static $course = array('courseId','courseName','description','status');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
    
    
}