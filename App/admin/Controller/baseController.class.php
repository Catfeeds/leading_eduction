<?php
namespace App\admin\Controller;

class baseController
{
    public function ajaxReturn($data,$type = null)
    {
        header('Content-type:application/json;charset=utf-8');
        $data = $this->formatResponse($data,$type);
        exit(json_encode($data));
    }
    
    //格式化结果集
    public function formatResponse($data,$type)
    {
        $status = intval($data['status']);
        if (!isset($data['msg']) && !is_null($type)) {
            switch ($type) {
                case 'modify':
                    $data['msg'] = $this->formatMsg($status);
                    break;
            }
        }
        return $data;
    }
    
    //返回提示信息
    public function formatMsg($status)
    {
        $msg = '';
        switch ($status) {
            case 0:
                $msg = 'success';
                break;
            case 1:
                $msg = 'failed';
                break;
            case 2:
                $msg = '没有身份标示符';
                break;
            case 3:
                $msg = '与登录信息不符、未登录或已失效';
                break;
            case 4:
                $msg = '没有要修改的信息';
                break;
            case 5:
                $msg = '要操作的信息不安全';
                break;
            case 6:
                $msg = '已超过数额限制';
                break;
            case 7:
                $msg = '未知错误';
                break;
            case 9:
                $msg = '操作信息不集全';
                break;
        }
        return $msg;
    }
    
}