<?php
namespace App\admin\Controller;

class baseController
{
    private static $msg = array(
        0  => 'success',
        1  => 'failed',
        2  => '没有身份标识符',
        3  => '与登录信息不符、未登录或已失效',
        4  => '没有要修改的信息',
        5  => '要修改的信息不安全',
        6  => '已超过数额限制',
        7  => '未知错误',
        8  => '',
        9  => '操作信息不集全',
        10 => '系统维修中',
        11 => '',
        12 => '操作信息不正确',
        13 => '身份信息不正确',
        14 => '标识符错误',
        15 => '信息不唯一',
        16 => '不用重复修改',
        17 => '不能重复添加',
        18 => '手机号已注册',
        19 => '邮箱已注册',
        20 => '手机号或邮箱已注册',
    );
    public function ajaxReturn($data,$type = null)
    {
        header('Content-type:application/json;charset=utf-8');
        $data = $this->formatResponse($data,$type);
        exit(json_encode($data));
    }
    
    //格式化结果集
    public function formatResponse($data,$type)
    {
        @$status = intval($data['status']);
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
        /* switch ($status) {
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
            case 10:
                $msg = '系统维修中';
                break;
            case 12:
                $msg = '需要操作的信息不正确';
                break;
            case 13:
                $msg = '身份信息不正确';
            case 14:
                $msg = '标识符错误';
                break;
            case 15:
                $msg = '信息不唯一';//不满足唯一性
                break;
            case 16:
                $msg = '不用重复修改';
                break;
            case 17:
                $msg = '不能重复添加';
                break;
            case 18:
                $msg = '手机号已注册';
                break;
            case 19:
                $msg = '邮箱已注册';
                break;
            case 20:
                $msg = '手机号或邮箱已注册';
                break;
        } */
        @$res = self::$msg[$status];
        if (!$res) {
            $res = '未知错误,系统需要维修';
        }
        return $res;
    }
    
}