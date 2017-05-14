<?php
namespace App\admin\Model;
use App\admin\Model\verifyModel;
class checkModel
{
    private $user = [];
    
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
            $data['msg'] = 'logout successed';
        }else{
            $data['status'] = 1;
            $data['msg'] = '没有登录信息';
        }
        return $data;
    }
    
    /**
     * @处理登陆，验证相关信息
     * @return array
     */
    public function checkLogin()
    {
        $data = [];
        if($this->user && is_array($this->user) && count($this->user)>0){//已登陆
            if(time() > ($this->user['user_expTime'] + 60 * 60 * 6)){//超过有效期6个小时
                $data['status'] = 5;
                $data['msg'] = '登陆已失效，请重新登陆';
            }else{
                $data['info'] = $this->user;
                $data['status'] = 1;
                $data['msg'] = '账号已登录，不用再次登录';
            }
        }else{
            //获得post中的数据
            @$accNumber = strval(daddslashes($_POST['accNumber']));
            @$password = myMd5(strval(daddslashes($_POST['password'])));
            @$verifyCode = strval(daddslashes($_POST['verifyCode']));
            @$caseId = intval(daddslashes($_POST['loginCase']));
            if($accNumber && $password && $verifyCode){
                if(verifyModel::verifyPicCode($verifyCode)){//验证码相等且有效
                    $checkPass = $this->getPass_byCase($caseId,$accNumber);//获得数据库中密码
                    if(!empty($checkPass['password']) && ($checkPass['password'] == $password)){//两者密码相等
                        /**存在session中***/
                        $_SESSION['user'] = $checkPass;
                        $_SESSION['user']['user_expTime'] = time();//登陆时间
                        /**end***/
                        $this->writeLoginLog($accNumber,$checkPass['caseId']);
                        $data['info'] = array('caseId'=>$checkPass['caseId']);
                        $data['status'] = 0;
                        $data['msg'] = 'success';
                    }else{
                        $data['status'] = 4;
                        $data['msg'] = '账号或密码错误';
                    }
                }else{
                    $data['status'] = 3;
                    $data['msg'] = '验证码有误或失效';
                }
            }else{
                $data['status'] = 2;
                $data['msg'] = '登陆信息不全';
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
        $arr = array('password','caseId');
        if(isMobile($accNumber) == 1){//是手机号
            $caseId = 4;
        }
        switch($caseId){
            case 1://学号登录
                $table = 'student';
                $where['stuId'] = $accNumber;
                break;
            case 2://教师登录
                $table = 'teacher';
                $where['teacherId'] = $accNumber;
                break;
            case 3://员工登陆
                $table = 'leading_staff_info';
                $where['accNumber'] = $accNumber;
                break;
            case 4://手机号登陆
            default://默认手机登录
                $where['mobile'] = $accNumber;
                $table = array('student','teacher','leading_staff_info','leading_company','temp_register');
                break;
        }
        if(is_array($table)){//手机登录
            foreach($table as $value){
                $res = $this->getInfo_byArr($value,$arr,$where);
                if(count($res)>0 && ($res['id'] > 0)){
                    if($value = 'temp_register'){//查询临时表
                        $res['caseId'] = 0;//修改角色值
                    }
                    break;
                }
            }
        }else{//学号、工号、教师号
            $res = $this->getInfo_byArr($table,$arr,$where);
        }
        return $res;
    }
    /**
    * 根据不同的模型与字段信息，获得相关信息
    * @date: 2017年5月12日 上午9:54:38
    * @author: lenovo2013
    * @param: $table string 表名
    * @param $arr array 字段数组
    * @param $arr array 条件数组
    * @return:array
    */
    public function getInfo_byArr($table,$arr,$where)
    {
        $obj = M("{$table}");
        return $obj->getInfo_byArr($arr,$where);
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
            @$arr['caseId'] = intval(daddslashes($_POST['caseId']));
            @$arr['recommendId'] = strval(daddslashes($_POST['invitation']));//推荐码
            @$arr['mobile'] = strval(daddslashes($_POST['mobile']));
            if(isMobile($arr['mobile'])){//手机号符合格式
                @$arr['name'] = strval(daddslashes($_POST['name']));
                @$arr['password'] = myMd5(strval(daddslashes($_POST['password'])));
                @$arr['email'] = strval(daddslashes($_POST['email']));
                if($arr['caseId'] && $arr['mobile']  && $arr['name'] && $arr['password'] && $arr['email']){//信息集全
                    if(!verifyModel::verifyMobile($arr['mobile'])){//此手机号没有注册
                        if(!verifyModel::verifyEmail($arr['email'])){//此邮箱没有注册
                            $arr['status'] = 0;//未通过后台验证
                            $res = $this->insert('temp_register',$arr);
                            if($res > 0){
                                $data['status'] = 0;
                                $data['msg'] = '注册成功';
                            }else{
                                $data['status'] = 5;
                                $data['msg'] = '注册失败';
                            }
                        }else{
                            $data['status'] = 4;
                            $data['msg'] = '此邮箱已注册';
                        }
                    }else{
                        $data['status'] = 3;
                        $data['msg'] = '此手机号已注册，请登陆';
                    }
                }else{
                    $data['status'] = 2;
                    $data['msg'] = '注册信息不全，不能注册';
                }
            }else{
                $data['status'] = 6;
                $data['msg'] = '手机号格式不对';
            }
        } else{
            $data['status'] = 1;
            $data['msg'] = '验证码错误';
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
    
}