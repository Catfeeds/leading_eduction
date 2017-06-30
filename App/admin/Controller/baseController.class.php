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
        21 => '',
        22 => '手机格式不正确',
        23 => '图片验证码错误',
        24 => '手机验证码错误',
        25 => '邮箱错误',
        26 => '手机号未注册',
        27 => '',
        28 => '职位已招满',
        29 => '不存在该职位',
        30 => '该项目不能更改',
        
        40 => '新密码与旧密码一致，不用修改',
        41 => '旧密码错误',
        42 => '新密码与确认密码不一致',
        43 => '密码长度不符合规定',
        
        51 => '上传失败',
        61 => '还没有职位信息，请到管理职位页面添加职位',
        62 => '该学员没有投递贵公司简历的记录',
        71 => '不能更改自己的 权限',
        
        81 => '已达到每月投递次数的限额',
        82 => '已达到每日投递次数的限额',
        83 => '30天内不能重复投递同一岗位',
    );
    public function ajaxReturn($data,$type = 'modify')
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
        @$res = self::$msg[$status];
        if (!$res) {
            $res = '未知错误,系统需要维修';
        }
        return $res;
    }
    
}