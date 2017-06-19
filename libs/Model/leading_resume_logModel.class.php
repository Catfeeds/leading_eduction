<?php
namespace libs\Model;

class leading_resume_logModel extends tableModel
{
    private static $table = 'leading_resume_log';
    
    
    private static $leading_resume_log = array('l_id','jobId','resumeTime','accNumber','caseId','r_status','d_count','m_count');
    
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
    
    
    
    
}