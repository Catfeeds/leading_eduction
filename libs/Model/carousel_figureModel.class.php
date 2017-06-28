<?php
namespace libs\Model;
use framework\libs\core\DB;

class carousel_figureModel extends tableModel
{
    private static $table = 'carousel_figure';
    
    private static $carousel_figure = array('id','picName','picUrl','pic_type','top','status','courseId','description','url','addTime');
    
    public function getTabArr($name)
    {
        return self::${$name};
    }
}