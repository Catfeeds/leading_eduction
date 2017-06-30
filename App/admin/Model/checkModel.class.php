<?php
namespace App\admin\Model;
class checkModel extends infoModel
{
    const EXPTIME = 7200;       //token过期时间，单位秒
    
    private $mailPassTitle = '上海领思教育科技有限公司找回密码';
    
    private $user  = [];
    
    private $table = array(
        1 => 'leading_student',
        2 => 'leading_teacher',
        3 => 'leading_staff_info',
        4 => 'leading_company',
        5 => 'temp_register'
    );
    private $tableId = array(
        1 => 'leading_student',
        2 => 'leading_teacher',
        3 => 'leading_teacher',
        4 => 'leading_staff_info',
        5 => 'leading_staff_info',
        6 => 'leading_staff_info',
        7 => 'leading_staff_info',
        8 => 'temp_register',
        9 => 'leading_company'
    );
    private $where = array(
        'leading_student'    => 'stuId',
        'leading_teacher'    => 'teacherId',
        'leading_staff_info' => 'accNumber',
        'leading_company'    => 'compId',
        'temp_register'      => 'tmpId'
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
    
    public function getTable_byKey($caseId)
    {
        return $this->tableId["{$caseId}"];
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
                        $res['caseId'] = 8;//修改角色值
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
            @$arr['recommendId'] = $_LS['recommendId'];                 //推荐码
            @$arr['mobile']      = $_LS['mobile'];
            if(isMobile($arr['mobile'])){                               //手机号符合格式
                @$arr['name']     = $_LS['name'];
                @$password        = $_LS['password'];
                if (verifyLen($password,6,15)) {
                    @$arr['password'] = myMd5($password);
                    @$arr['email']    = $_LS['email'];
                    if($arr['caseId'] && $arr['mobile']  && $arr['name'] && $arr['password'] && $arr['email']){//信息集全
                        if(!verifyModel::verifyMobile($arr['mobile'])){     //此手机号没有注册
                            if(!verifyModel::verifyEmail($arr['email'])){   //此邮箱没有注册
                                $arr['status']    = 0;                      //未通过后台验证
                                $arr['dateinto']  = time();                 //注册时间
                                $tempObj          = new doActionModel();
                                $arr['tmpId']     = $tempObj->productTempId();
                                $res              = parent::insert('temp_register',$arr);
                                if ($res > 0) {
                                    $data['status'] = 0;
                                    //把该账号注册到论坛
                                    /* $res = $this->registerDiscuz($arr,$password);
                                    if($res['status']  == 0){
                                        $data['status'] = 0;
                                    }else{
                                        $data['status'] = 33;
                                        $data['msg']    = '论坛注册失败，原因是：'.$res['msg'];
                                    } */
                                } else {
                                    $data['status'] = 1;
                                }
                            } else {
                                $data['status'] = 19;
                            }
                        } else {
                            $data['status'] = 18;
                        }
                    } else {
                        $data['status'] = 9;
                    }
                } else {
                    $data['status'] = 21;
                }
                
            } else {
                $data['status'] = 22;
            }
        } else {
            $data['status'] = 23;
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
    
    //开始重置密码
    public function startResetPassword()
    {
        global $_LS;
        $data    = array();
        @$mobile = $_LS['mobile'];
        @$email  = $_LS['email'];
        @$caseId = intval($_LS['caseId']);
        if ($mobile && $email) {                                        //post中存在手机号和邮箱
            $arr   = array('id','password','caseId','mobile');
            $where = array('mobile' => $mobile,'email' => $email);
            $table = empty($caseId)?$this->table:$this->table[$caseId]; //根据caseId获得相关数据表
            if (is_array($table)) {                                     //没有指定表
                foreach ($table as $val) {
                    $resp = parent::fetchOne_byArr($val,$arr,$where);
                    if (count($resp) > 0) {
                        if ($val == 'temp_register') {
                            $resp['caseId'] = 8;                        //角色为临时
                        }
                        break;
                    }
                }
            } else {                                                    //caseId指定表
                $resp = parent::fetchOne_byArr($table,$arr,$where);
            }
            if (count($resp) > 0 ) {                                    //账号信息正确
                $token_exptime = time() - self::EXPTIME;                //过期时间
                $where_2       = array('mobile' => $mobile,'where2' => ' AND token_expTime > '.$token_exptime);
                $table         = $this->getTab_byid($resp['caseId']);
                $res_2         = parent::fetchOne_byArr($table,array('id'),$where_2);             
                if (count($res_2) == 0 ) {                              //没有发送邮箱或者已过期
                    $token         = md5($mobile.$resp['password'].$token_exptime);                             //获得token
                    $arr           = array('token' => $token,'token_exptime' => time());
                    $where         = array('mobile' => $mobile);
                    $response      = $this->updateInfo($resp['caseId'],$arr,$where);
                    if ($response > 0) {
                        $data      = $this->sendEMail($email,$resp['mobile'],$mobile,$resp['caseId'],$token); //发邮箱
                    } else {
                        $data['status'] = 4;
                        $data['msg']    = '系统维修中';
                    }
                } else {
                    $data['status'] = 5;
                    $data['msg']    = '邮件尚未过期，请至邮箱查看';
                }
            } else {
                $data['status'] = 3;
                $data['msg']    = '手机号或邮箱不正确';
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 根据caseId更新一条记录
     * @param int $caseId
     * @param array $arr
     * @param array $where
    **/
    public function updateInfo($caseId,$arr,$where)
    {
        $table = $this->getTab_byid($caseId);
        return parent::update($table,$arr,$where);
    }
    /**
     * 通过id获得表名
     * @param int $id
     * return string 表名
     */
    public function getTab_byid($id)
    {
        return $this->tableId[$id];
    }
    /**
     * 发送邮件
     * @param string $email toUser
     * @param string $name  userName/手机号
     * @param int $caseId   
     * @param string $token 验证码
     */
    public function sendEMail($email,$name,$mobile,$caseId,$token)
    {
        $toUser   = $email;
        $title    = $this->mailPassTitle;
        $content  = $this->formatEmailContent($name,$mobile,$caseId,$token);
        $flag     = $this->sendMail($toUser,$title,$content);
        return parent::formatResponse($flag);
        /* $flag = $this->sendMail('359418894@qq.com','上海领思教育重置密码','<span style="color:red;">重置密码</span><br/>重置密码');
        return parent::formatResponse($flag); */
    }
    /**
     * 格式化邮箱内容
     * @param string $name      用户名|企业名
     * @param string $mobile    手机号
     * @param int $caseId       账号类型
     * @param string $token     验证码
     * @return string
     */
    public function formatEmailContent($name,$mobile,$caseId,$token)
    {
        $content = "尊敬的" . $name . "：<br/>此邮件仅是帮您重置密码。<br/>点击链接重置你的密码。<br/><a href='http://localhost:8066/leading/index.php?module=admin&method=checkresetPassVerify&verify=" . $token . "&caseId=".$caseId."&mobile=".$mobile."' target='_blank'>http://localhost:8066/leading/index.php?module=admin&method=checkresetPassVerify&verify=" .  $token . "&caseId=".$caseId."&mobile=".$mobile . "</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接2小时内有效。<br/>如果此次操作请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>-------- 上海领思教育科技有限公司</p>";
        return $content;
    }
    
    /**
     * 发送邮箱
     * @param string $to        邮箱接受者
     * @param string $title     邮箱主题
     * @param string $content   邮箱内容
     * @return boolean          发送成功为true
     */
    public function sendMail($to, $title, $content)
    {
        $mail = new mailerModel();                  // 实例化PHPMailer核心类
        // $mail->SMTPDebug = 1;                    //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        $mail->isSMTP();                            // 使用smtp鉴权方式发送邮件
        $mail->SMTPAuth   = true;                   // smtp需要鉴权 这个必须是true
        $mail->Host       = 'smtp.qq.com';          // 链接qq域名邮箱的服务器地址
        $mail->SMTPSecure = 'ssl';                  // 设置使用ssl加密方式登录鉴权
        $mail->Port       = 465;                    // 设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
        $mail->CharSet    = 'UTF-8';                // 设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
        $mail->FromName   = '测试邮件发送';            // 设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->Username   = '913346548@qq.com';     // smtp登录的账号 这里填入字符串格式的qq号即可
        $mail->Password   = 'htcjugryuqpjbdcg';     // smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）【非常重要：在网页上登陆邮箱后在设置中去获取此授权码】
        $mail->From       = '913346548@qq.com';     // 设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
        $mail->isHTML(true);                        // 邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
        $mail->addAddress($to);                     // 设置收件人邮箱地址
        $mail->Subject    = $title;                 // 添加该邮件的主题
        $mail->Body       = $content;               // 添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
        // 简单的判断与提示信息
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 验证重置密码是否有效和安全
     */
    public function checkresetPassVerify()
    {
        @$caseId = intval(daddslashes($_GET['caseId']));
        @$mobile = strval(daddslashes($_GET['mobile']));
        @$verify = strval(daddslashes($_GET['verify']));
        if ($caseId && $mobile && $verify) {
            $arr   = array('id','token_exptime');
            $table = $this->getTab_byid($caseId);
            $where = array('mobile' => $mobile,'token' => $verify);
            $res   = parent::fetchOne_byArr($table,$arr,$where);            //获得数据库中的验证信息
            if (count($res) > 0 && isset($res['token_exptime'])) {
                if ((time() -self::EXPTIME) < $res['token_exptime']) {      //有效
                    $data['status'] = 0;
                } else {                                                    //超时
                    $data['status'] = 3;
                    $data['msg']    = '已超时';
                }
            } else {
                $data['status'] = 5;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
}