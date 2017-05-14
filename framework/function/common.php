<?php
    use framework\libs\core\VIEW;
    use framework\libs\core\DB;
    
    /**
    * 给返回参数加入成功提示
    * @date: 2017年5月12日 下午6:53:53
    * @author: lenovo2013
    * @param: array 默认为空
    * @return:array
    */
    function myMerge($data = array())
    {
        if(!(is_array($data) && isset($data['status']) && ($data['status'] > 0))){
            $data['status'] = 0;
            $data['msg'] = 'success';
        }
        return $data;
    }