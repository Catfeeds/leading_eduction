<?php
namespace libs\Model;

class leading_teacher_infoModel
{
    private static $talbe = 'leading_teacher_info';
    
    private static $leading_teacher_info = array('id','teacherId','sex','title','description','picUrl','age');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}