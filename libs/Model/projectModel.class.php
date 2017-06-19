<?php
namespace libs\Model;
use framework\libs\core\DB;

class projectModel extends tableModel
{
    private static $table = 'project';
    
    private static $project = array('projectId','projectName','description','status','startTime','endTime','picUrl','url','people','type');
    
    
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}