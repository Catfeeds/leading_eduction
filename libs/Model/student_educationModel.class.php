<?php
namespace libs\Model;
use framework\libs\core\DB;

class student_educationModel extends tableModel
{
    private static $table = 'student_education';
    
    private static $student_education = array('id','stuId','major','eduSchool','dateinto','dateout','highest');
    
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}