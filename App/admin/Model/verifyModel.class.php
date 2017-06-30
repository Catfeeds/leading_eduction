<?php
namespace App\admin\Model;

class verifyModel extends infoModel
{
    private static $table = array(
        1 => 'leading_student',
        2 => 'leading_teacher',
        3 => 'leading_teacher',
        4 => 'leading_staff_info',
        5 => 'leading_staff_info',
        6 => 'leading_staff_info',
        9 => 'leading_company',
        8 => 'temp_register'
    );
    /**
     * @验证手机号是否已经注册
     * @return boolean true表示有注册
     */
    public static function verifyMobile($mobile,$caseId = null)
    {
        $res             = false;
        $arr             = array('id');
        $where['mobile'] = $mobile; 
        $table = !empty($caseId)?self::$table[$caseId]:array_unique(self::$table);
        if (is_array($table)) {
            foreach($table as $key=>$value){
                @$resp = parent::fetchOne_byArr($value,$arr,$where);
                if (count($resp) >0 && ($resp['id'] > 0)) {
                    $res = true;
                    break;
                }
            }
        } else {                                                    //单表
            @$resp = parent::fetchOne_byArr($table,$arr,$where);
            if(count($resp) >0 && ($resp['id'] > 0)){
                $res = true;
            }
        }
        return $res;
    }
    
    /**
     * @验证邮箱是否已经注册
     * @return boolean true 表示有注册
     */
    public static function verifyEmail($email,$caseId = null)
    {
        $res            = false;
        $arr            = array('id');
        $where['email'] = $email;
        $table = !empty($caseId)?self::$table[$caseId]:array_unique(self::$table);
        if (is_array($table)) {
            foreach($table as $key=>$value){
                @$resp = parent::fetchOne_byArr($value,$arr,$where);
                if (count($resp) >0 && ($resp['id'] > 0)) {
                    $res = true;
                    break;
                }
            }
        } else {                                                    //单表
            @$resp = parent::fetchOne_byArr($table,$arr,$where);
            if(count($resp) >0 && ($resp['id'] > 0)){
                $res = true;
            }
        }
        return $res;
    }
    
    /**
     * @获得图片的4位验证码
     */
    public static function getVerifyCode()
    {
        $str    = '';
        $data   = [];
        $strAll = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789';
        $max    = strlen($strAll) - 1;
        for($i=0;$i<4;$i++){
            $str .= $strAll[rand(0,$max)];
        }
        if($str){
            $time = time();
            //存入session中
            $_SESSION['verifyCode'] = strtolower($str);//换成小写
            $_SESSION['codeExpTime'] = $time;
            $data['info']   = array("verifyCode"=>$str,"codeExpTime"=>$time);
            $data['status'] = 0;
            $data['msg']    = 'success';
        }else{
            $data['status'] = 1;
            $data['msg']    = '获取验证码失败';
        }
        return $data;
    }
    /**
     * @验证图片验证码是否有效
     * @return boolean true 表示有效
     */
    public static function verifyPicCode($verifyCode)
    {
        $res = false;
        $now = time();
        if(!empty($_SESSION['codeExpTime']) && ($now-12000) > $_SESSION['codeExpTime']){//120失效
            $res = false;
        }else{
            if(!empty($_SESSION['verifyCode']) && strtolower($verifyCode) == $_SESSION['verifyCode']){//相等
                $res = true;   
            }
        }
        return $res;
    }
    /**
    * 根据省份编号获得省份名称
    * @date: 2017年5月15日 下午4:25:46
    * @author: lenovo2013
    * @param: int 省份编号
    * @return:
    */
    public static function  province($provinceId)
    {
        $arr                 = array('province');
        $where['provinceId'] = $provinceId;
        @$data               = parent::fetchOne_byArr('province',$arr,$where);
        return $data;
    }
    public static function verifyEducation($stuId)
    {
        $arr             = array('id','major','eduSchool');
        $where['stuId']  = $stuId;
        $where['where2'] = "order by dateOut desc";
        @$data           = parent::fetchOne_byArr('student_education',$arr,$where,$where2);
        return $data;
    }
}