<?php
namespace App\admin\Model;
//use App\admin\Model\verifyModel;
class checkModel extends infoModel
{
    private $user  = [];
    private $table = array(
        1 => 'leading_student',
        2 => 'leading_teacher',
        3 => 'leading_staff_info',
        4 => 'leading_company',
        5 => 'temp_register'
    );
    private $where = array(
        'leading_student'    => 'stuId',
        'leading_teacher'    => 'teacherId',
        'leading_staff_info' => 'accNumber',
        'leading_company'    => 'compId',
        'temp_register'      => 'accNumber'
    );
    /**
     * @构造函数，检查是否登陆
     */
    public function __construct()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
            $this->user = $_SESSION['user'];
        }
    }
    public function __destruct()
    {
        if($this->user){
            unset($this->user);
        }
    }
    /**
    * 注销
    * @date: 2017年5月12日 上午10:35:57
    * @author: lenovo2013
    * @return:
    */
    public function logout()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
            unset($_SESSION['user']);
            $data['status'] = 0;
            $data['msg']    = 'logout successed';
        }else{
            $data['status'] = 1;
            $data['msg']    = '没有登录信息';
        }
        return $data;
    }
    /**
     * 检测是否是该用户登陆
     * param $accNumber 手机号或者学号等
     * return array
     */
    public function checkLogined($caseId,$accNumber)
    {
        $password = '';
        $res = array();
        if($this->user && is_array($this->user) && count($this->user)>0){//有登陆信息
            if(time() > ($this->user['user_expTime'] + 60 * 60 * 6)){//超过有效期6个小时
                $data['status'] = 5;
                $data['msg']    = '登陆已失效';
                $this->logout();
            }else{
                $res = $this->getPass_byCase($caseId,$accNumber);
                if(count($res) > 0 && ($this->user['password'] == $res['password'])){
                    $data['status'] = 0;
                    $data['msg']    = 'logined';
                }else{
                    $data['status'] = 2;
                    $data['msg']    = '账号不存在';
                }
            }
        }else{
            $data['status'] = 1;
            $data['msg']    = '请登陆';
        }
        return $data;
    }
    /**
     * @处理登陆，验证相关信息
     * @return array
     */
    public function checkLogin()
    {
        $data = array();
        if($this->user && is_array($this->user) && count($this->user)>0){//已登陆
            if(time() > ($this->user['user_expTime'] + 60 * 60 * 6)){//超过有效期6个小时
                $data['status'] = 5;
                $data['msg']    = '登陆已失效，请重新登陆';
                $this->logout();
            }else{
                $data['info']   = $this->user;
                $data['status'] = 1;
                $data['msg']    = '账号已登录';
            }
        } else {
            //获得post中的数据
            global $_LS;
            @$accNumber  = $_LS['accNumber'];
            @$password   = myMd5($_LS['password']);
            @$verifyCode = $_LS['verifyCode'];
            @$caseId     = $_LS['loginCase'];
            if ($accNumber && $password && $verifyCode) {
                if (verifyModel::verifyPicCode($verifyCode)) { // 验证码相等且有效
                    $checkPass = $this->getPass_byCase($caseId, $accNumber); // 获得数据库中密码
                    if (! empty($checkPass['password']) && ($checkPass['password'] == $password)) { // 两者密码相等
                        if ($checkPass['status'] == 0) { // 账号未激活
                            $data['status'] = 5;
                            $data['msg']    = '账号未激活';
                        } else {
                            /*存在session中*/
                            $_SESSION['user']                 = $checkPass;
                            $_SESSION['user']['user_expTime'] = time(); // 登陆时间
                            /*end*/
                            $this->writeLoginLog($accNumber, $checkPass['caseId']);
                            $data['info'] = array(
                                'caseId'    => $checkPass['caseId'],
                                'accNumber' => end($checkPass)
                            );
                            $data['status'] = 0;
                            $data['msg']    = 'success';
                        }
                    } else {
                        $data['status'] = 4;
                        $data['msg']    = '账号或密码错误';
                    }
                }  else {
                    $data['status'] = 3;
                    $data['msg']    = '验证码有误或失效';
                }
            } else {
                $data['status'] = 2;
                $data['msg']    = '登陆信息不全';
            }
        }
        return $data;
    }
    /**
    * 写入一条登陆记录
    * @date: 2017年5月12日 上午10:57:10
    * @author: lenovo2013
    * @param: $accNumber 登陆账号
    * @param $caseId 账号类型
    * @return:
    */
    public function writeLoginLog($accNumber,$caseId)
    {
        $obj = M('login_log');
        $obj->writeOne($accNumber,$caseId);
    }
    /**
     * @根据账号的不同类型获得密码
     * @return array
     */
    public function getPass_byCase($caseId,$accNumber){
        $res = [];
        $arr = array('password','caseId','mobile','status','email');//可添加status项，来确定该账号是否还有效
        if (isMobile($accNumber)) {
            $table           = $this->table;
            $where['mobile'] = $accNumber;
            foreach ($table as $value){
                $arr_2            = $this->where["{$value}"];
                $arr['accNumber'] = $arr_2;
                $res              = parent::fetchOne_byArr($value,$arr,$where);
                if(count($res)>0 && isset($res['password']) && !empty($res['password'])){
                    if($value == 'temp_register'){//查询临时表
                        $res['caseId'] = 0;//修改角色值
                    }
                    break;
                }
            }
        } else {  
            $table           = $this->table[$caseId];
            $key             = $this->where["{$table}"];
            $where["{$key}"] = $accNumber;
            $arr[]           = $key;
            $res             = parent::fetchOne_byArr($table,$arr,$where);
        }
        return $res;
    }
    
    /**
     * @处理注册
     * @return array
     */
    public function checkSign()
    {
        $data = [];
        if ($this->checkMobileVerify()) {//检测手机验证码
            /**
             * 获得post中的数据**
             */
            global $_LS;
            @$arr['caseId']      = $_LS['caseId'];
            @$arr['recommendId'] = $_LS['recommendId'];//推荐码
            @$arr['mobile']      = $_LS['mobile'];
            if(isMobile($arr['mobile'])){//手机号符合格式
                @$arr['name']     = $_LS['name'];
                @$password        = $_LS['password'];
                @$arr['password'] = myMd5($password);
                @$arr['email']    = $_LS['email'];
                if($arr['caseId'] && $arr['mobile']  && $arr['name'] && $arr['password'] && $arr['email']){//信息集全
                    if(!verifyModel::verifyMobile($arr['mobile'])){//此手机号没有注册
                        if(!verifyModel::verifyEmail($arr['email'])){//此邮箱没有注册
                            $arr['status']    = 0;//未通过后台验证
                            $tempObj          = new doActionModel();
                            $arr['accNumber'] = $tempObj->productTempId();
                            $res              = parent::insert('temp_register',$arr);
                            if($res > 0){
                                //把该账号注册到论坛
                                $res = $this->registerDiscuz($arr,$password);
                                if($res['status']  == 0){
                                    $data['status'] = 0;
                                    $data['msg']    = '注册成功';
                                }else{
                                    $data['status'] = 7;
                                    $data['msg']    = $res['msg'];
                                }
                            }else{
                                $data['status'] = 5;
                                $data['msg']    = '注册失败';
                            }
                        }else{
                            $data['status'] = 4;
                            $data['msg']    = '此邮箱已注册';
                        }
                    }else{
                        $data['status'] = 3;
                        $data['msg']    = '此手机号已注册，请登陆';
                    }
                }else{
                    $data['status'] = 2;
                    $data['msg']    = '注册信息不全，不能注册';
                }
            }else{
                $data['status'] = 6;
                $data['msg']    = '手机号格式不对';
            }
        } else{
            $data['status'] = 1;
            $data['msg']    = '验证码错误';
        }
        
        return $data;
    }
    
    /**
    * 在指定表中插入数据
    * @date: 2017年5月12日 下午4:12:03
    * @author: lenovo2013
    * @param: $tabel 表名
    * @param array $arr 要插入的字段和值组成的数组
    * @return:int
    */
    public function insert($table,$arr)
    {
        $obj = M($table);
        return $obj->insert($arr);
    }
    /**
     * @检查手机验证码
     * @return boolean true表示通过验证
     */
    public function checkMobileVerify()
    {
        return true;
    }
    /**
     * 账号注册到论坛
     */
    public function registerDiscuz($arr,$password)
    {
        $discuzArr['username']  = $arr['mobile'];
        $discuzArr['password']  = $password;
        $discuzArr['password2'] = $password; 
        $discuzArr['salt']      = 'ls5698';
        $discuzArr['email']     = $arr['email'];
        $obj = new discuzModel();
        return $obj->registerDiscuz($discuzArr);
    }
    
}