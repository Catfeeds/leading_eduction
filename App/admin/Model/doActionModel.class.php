<?php
namespace App\admin\Model;

class doActionModel extends infoModel
{
    
    private static $table = array(
        1 => 'leading_student',
        2 => 'leading_teacher',
        3 => 'leading_staff_info',
        4 => 'leading_company',
        5 => 'temp_register'
    );
    
    private static $where = array(
        'leading_student'    => 'stuId',
        'leading_teacher'    => 'teacherId',
        'leading_staff_info' => 'accNumber',
        'leading_company'    => 'compId',
        'temp_register'      => 'tmpId'
    );
    
    
    public function getTable_byCaseId($caseId)
    {
        return self::$table[$caseId];
    }
    
    public function getWhere($table)
    {
        return self::$where[$table];
    }
    
    /**
    * 重置密码
    * @date: 2017年5月12日 下午1:21:02
    * @author: lenovo2013
    * @return:array
    */
    public function resetPassword()
    {
        global $_LS;
        $res         = '';
        // 获得post中的数据
        @$caseId     = intval($_LS['caseId']);
        @$mobile     = $_LS['mobile'];
        @$password_1 = $_LS['password_1'];
        @$password_2 = $_LS['password_2'];
        if($password_1 == $password_2){//两个密码一致
            if (verifyLen($password_1, 6, 15)) {
                if(verifyModel::verifyMobile($mobile)){                             //手机号是否注册
                    $resetPass = myMd5($password_1);
                    if ($caseId && $mobile && $resetPass) {
                        if(isMobile($mobile)){
                            $arr             = array( "password" => $resetPass  );  // 要更新的字段和值
                            $where['mobile'] = $mobile;
                            $obj             = new checkModel();
                            $table           = $obj->getTable_byKey($caseId);       //获得相关表
                            $res  = parent::update($table,$arr,$where);
                            $data = parent::formatResponse($res);
                        }else{
                            $data['status'] = 2;
                            $data['msg']    = "手机格式不符";
                        }
                    }else{
                        $data['status'] = 3;
                        $data['msg']    = "数据不全";
                    }
                }else{
                    $data['status'] = 5;
                    $data['msg']    = '手机号未注册';
                }
            } else {
                $data['status'] = 6;
                $data['msg']    = '密码长度不符合规定';
            }
        }else{
            $data['status'] = 4;
            $data['msg']    = "修改密码与确认密码不一样，不能修改";
        }
        return $data;
    }
    /**
     * 根据班级及上学地点生成学号
     * 学号10位数字组成的字符串
     * 1,2位是当前年份后两位，如2017年的17
     * 3，4，位是班级号，1改为01，两位数不变
     * 5，6位是上课地点编号，改变如上
     * 7，8，9，10位是该班级的第几位学员
     * return string
     */
    public function productStuId($classId = null,$addressId = null)
    {
        //获得1，2位
        $classId          = is_null($classId)?1:$classId;
        $addressId        = is_null($addressId)?1:$addressId;
        $year             = substr(date('y-m-d'),0,2);//获取入学年份
        
        //获得最后一个学生学号
        $arr              = array('id','stuId');
        $where['classId'] = $classId;
        $where['where2']  = "order by id desc ";
        $data = parent::fetchOne_byArr('leading_student_info',$arr,$where);
        
        //规定学生数量，获得7，8，9，10
        if(count($data)>0){
            $lastStuId = substr($data['stuId'],-4,4);
            $num       = $lastStuId + 1;
        }else{
            $num = '0001';
        }
        //规定班级号，获得3，4位
        $len = strlen($classId);
        if($len == 1){
            $classId = "0".$classId;
        }
        //获得5，6位
        $addressId = (strlen($addressId) == 2)?$addressId:"0".$addressId;
        $stuId     = $year.$classId.$addressId.$num;
        return $stuId;
    }
    /**
     * 生成教师号如   t56801,不超过十位
     * 获得最后一个教师号，去除‘t’字符，然后加1
     * return string 
     */
    public function productTeacherId()
    {
        $teacherId       = '';
        $arr             = array('id','teacherId');
        $where['where2'] = 'order by id desc ';
        $res             = parent::fetchOne_byArr('leading_teacher',$arr,$where);
        //去除学号的第一't'字符
        if(count($res) > 0){
            $last = substr($res['teacherId'],1);
            $num  = $last + 1;
        }
        if($num){
            $teacherId = 't'.$num;
        }
        return $teacherId;
    }
    /**
     * 生成企业号 如com5680001,不超过10位
     * 获得最后一个企业号，去掉前缀'com'，然后加1
     * return string
     */
    public function productCompId()
    {
        $compId          = '';
        $arr             = array('id','compId');
        $where['where2'] = ' order by id desc ';
        $res             = parent::fetchOne_byArr('leading_company',$arr,$where);
        //去掉前缀，然后加1
        if(count($res) > 0){
            $last = substr($res['compId'],3);
            $num  = $last + 1;
        }else{
            $num = '5680001';
        }
        $compId = 'com'.$num;
        return $compId;
    }
    /**
     * 生成员工号 如 ls5680001
     * return string
     */
    public function productStaffId()
    {
        $staffId         = '';
        $arr             = array('id','accNumber');
        $where['where2'] = ' order by id desc ';
        $res             = parent::fetchOne_byArr('leading_staff_info',$arr,$where);
        if(count($res) > 0){
            $last = substr($res['accNumber'],2);
            $num  = $last + 1;
        }else{
            $num = '5680001';
        }
        $staffId = 'ls'.$num;
        return $staffId;
    }
    
    /**
     * 生成临时号 如 tmp5680001
     */
    public function productTempId()
    {
        $tempId          = '';
        $arr             = array('accNumber','id');
        //$where['caseId'] = 8;
        $where['where2'] = ' order by id desc';
        $res             = parent::fetchOne_byArr('temp_register',$arr,$where);
        if(count($res) > 0){
            $last = substr($res['accNumber'],3);
            $num  = $last + 1;
        }else{
            $num = '5680001';
        }
        $tempId = 'tmp'.$num;
        return $tempId;
    }
    
    //生成课程id
    public function productCourseId()
    {
        $courseId = 0;
        $arr      = array('courseId');
        $where    = array('where2' => ' ORDER BY courseId DESC ');
        $res      = parent::fetchOne_byArr('course',$arr,$where);
        if (count($res) > 0) {
            $courseId = intval($res['courseId']) + 1;
        } else {
            $courseId = 56801;
        }
        return $courseId;
    }
    
    
    
    /**
     * 修改密码
     * @param array $_LS post中的数据
     * @param string $table
     * @param array $user
     * @return array
     */
    public function setPass($_LS,$table,$user,$where = null)
    {
        $data       = array();
        //获得post中的数据
        @$accNumber = $_LS['accNumber'];
        @$email     = $_LS['email'];
        @$oldPass   = $_LS['oldPass'];
        @$newPass_1 = $_LS['newPass_1'];
        @$newPass_2 = $_LS['newPass_2'];
        if ($accNumber && $email && $oldPass && $newPass_1 && $newPass_2) {
            if ($this->verifyPass($oldPass,$user)) {                                    // 旧密码不为空且正确
                if ($this->verifyEmail($email,$user)) {                                 // 邮箱正确
                    if ($oldPass != $newPass_1) {                                       //修改前后密码不一致
                        if ($newPass_1 == $newPass_2) {                                 //修改密码与确认密码一样
                            if (verifyLen($newPass_1, 6, 15)) {                         //修改密码长度符合规定
                                $newPass        = myMd5($newPass_1);                    //密码加密
                                $where['email'] = $email;
                                $res = $this->updatePass($table,$where, $newPass);
                                if($res > 0){
                                    $_SESSION['user']['password'] = $newPass;           //更新sessio中的秘密
                                }
                                $data = parent::formatResponse($res);
                            } else {
                                $data['status'] = 7;
                                $data['msg'] = '密码长度不符合';
                            }
                        } else {
                            $data['status'] = 5;
                            $data['msg'] = '新密码与确认密码不一致';
                        }
                    } else {
                        $data['status'] = 4;
                        $data['msg'] = '新密码与旧密码一样，不用修改';
                    }
                } else {
                    $data['status'] = 8;
                    $data['msg']    = '邮箱错误';
                }
            } else {
                $data['status'] = 3;
                $data['msg'] = '旧密码错误';
            }
        } else{
            $data['status'] = 6;
            $data['msg']    = 'post传参不能为空';
        }
        return $data;
    }
    
    /**
     * 验证密码是否正确
     * @param string $password
     * @param array $user 用户信息
     * @return boolean
     */
    public function verifyPass($password,$user)
    {
        $res      = false;
        $password = myMd5($password);
        if(isset($user) && !empty($user) && $user['password'] == $password){
            $res = true;
        }
        return $res;
    }
    
    /**
     * 验证邮箱
     * @param string $email
     * @param array $user
     * @return boolean
     */
    public function verifyEmail($email,$user)
    {
        $res      = false;
        if(isset($user) && !empty($user) && $user['email'] == $email){
            $res = true;
        }
        return $res;
    }
    
    /**
     * 修改密码
     * @param string $table
     * @param array $where
     * @param string $newPass_1
     * @return int
     */
    public function updatePass($table,$where,$newPass_1)
    {
        $res            = '';
        $newPass        = myMd5($newPass_1);
        $arr            = array("password" => $newPass);
        $res            = parent::update($table,$arr,$where);
        return $res;
    }
    /**
     * 修改对象基本信息
     * @param array $_LS 提交的信息组
     * @param array $verifyArr 白名单数组
     * @param array|string $table 更改的数据表
     * @param array $where 更改条件数组 
     */
    public function setObjectBase($_LS,$verifyArr,$table,$where)
    {
        $data = array();
        $arr  = array_diff($_LS,array("accNumber"=>$_LS["accNumber"]));         //获得要操作的信息
        if (count($arr) > 0 ) {
            if (parent::verifyCount($arr,$verifyArr) == 0) {                    //验证操作信息是否安全
                @$mobile = $_LS['mobile'];
                @$email  = $_LS['email'];
                if ($mobile && verifyModel::verifyMobile($mobile)) {            //验证手机号是否注册
                    $data['status'] = 8;
                    $data['msg']    = '手机号已注册';
                } else {
                    if ($email && verifyModel::verifyEmail($email)) {           //验证邮箱是否注册
                        $data['status'] = 9;
                        $data['msg']    = '邮箱已注册';
                    } else {
                        $data  = parent::update($table,$arr,$where);            //更新数据
                        $data  = parent::formatResponse($data);                 //格式化结果集
                    }
                }
            } else {
                $data['status'] = 5;
            }
        } else {
            $data['status'] = 4;
        }
        return $data;
    }
    
    /**
     * 上传图片，并生成149x185大小的缩略图
     * @param string $table        用户表
     * @param array $where         更新url条件
     * @param string $destination  图片文件路径
     * @return array
     */
    public function uploadPic($table,$where,$destination,$urlName=null)
    {
        $obj = new uploadFileModel();
        $msg = $obj->uploadFileImg();
        if (is_array($msg)) {
            if (count($msg) > 0 ){
                $fileName    = './static/admin/images/uploads/'.$msg[0]['name'];
                $destination = $destination.$msg[0]['name'];
                $resource    = $obj->thumb($fileName,$destination,149,185,false);
                $des         = preg_replace('/^[\.]/',' ',$destination);
                $url         = 'http://'.$_SERVER['HTTP_HOST'].'/leading'.$des;
                $urlName     = is_null($urlName)?'picUrl':$urlName;
                $arr         = array("{$urlName}" => $url);
                $res         = parent::update($table,$arr,$where); 
                $data        = parent::formatResponse($res);
            } else {
                $data['status'] = 5;
                $data['msg']    = '上传失败';
            }
        } else {
            $data['status'] = 4;
            $data['msg']    = $msg;
        }
        return $data;
    }
    
    
    
    /**
     * 验证角色号
     * @param string $accNumber
     * @return int
     */
    public function verifyCaseId($accNumber)
    {
        $caseId = 0;
        if (isMobile($accNumber)) {                                 //手机号
            return 6;
        }
        if ($this->verifyStuId($accNumber)) {                       //学号
            return 1;
        }
        if ($this->verifyTeacherId($accNumber)) {                   //教师号
            return 2;
        }
        if ($this->verifyStaffId($accNumber)) {                     //员工号
            return 3;
        }
        if ($this->verifyCompId($accNumber)) {                      //企业号
            return 4;
        }
        if ($this->verifyTmpId($accNumber)) {                       //临时号
            return 5;
        }
        return $caseId;
    }
    
    /**
     * 验证学号
     * @param string $accNumber
     * @return number
     */
    public function verifyStuId($accNumber)
    {
        $res = 0;
        $len = is_string($accNumber)?strlen(trim($accNumber)):0;
        if ($len == 10) {
            $res = preg_match('/^[\d]{10}$/',trim($accNumber)); 
        }
        return $res;
    }
    
    /**
     * 验证企业号
     * @param string $accNumber
     * @return int 0|1
     */
    public function verifycompId($accNumber)
    {
        $res = 0;
        $len = is_string($accNumber)?strlen(trim($accNumber)):0;
        if ($len == 10) {
            $res = preg_match('/^(com)([\d]{7})$/',trim($accNumber));
        }
        return $res;
    }
    
    /**
     * 验证员工号
     * @param string $accNumber
     * @return int 0|1
     */
    public function verifyStaffId($accNumber)
    {
        $res = 0;
        $len = is_string($accNumber)?strlen(trim($accNumber)):0;
        if ($len == 9) {
            $res = preg_match('/^(ls)([\d]{7})$/',trim($accNumber));
        }
        return $res;
    }
    
    
    /**
     * 验证临时账号
     * @param string $accNumber
     * @return number 1|0
     */
    public function veiryTmpId($accNumber)
    {
        $res = 0;
        $len = is_string($accNumber)?strlen(trim($accNumber)):0;
        if ($len == 10) {
            $res = preg_match('/^(tmp)([\d]{7})$/',trim($accNumber));
        }
        return $res;
    }
    
    /**
     * 验证教师号
     * @param string $accNumber
     * @return int 0|1
     */
    public function verifyTeacherId($accNumber)
    {
        $res = 0;
        $len = is_string($accNumber)?strlen(trim($accNumber)):0;
        if ($len = 6) {
            $res = preg_match('/^(t)([\d]{5})$/',trim($accNumber));
        }
        return $res;
    }
    
    
    
}