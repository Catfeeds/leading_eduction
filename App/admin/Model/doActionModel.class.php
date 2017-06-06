<?php
namespace App\admin\Model;

class doActionModel extends infoModel
{
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
            if(verifyModel::verifyMobile($mobile)){
                $resetPass = myMd5($password_1);
                if ($caseId && $mobile && $resetPass) {
                    if(isMobile($mobile)){
                        $arr             = array( "password" => $resetPass  ); // 要更新的字段和值
                        $where['mobile'] = $mobile;
                        switch ($caseId){
                            case 1: // 学生
                                $table = 'leading_student';
                                break;
                            case 2:
                            case 3: // 教师
                                $table = 'leading_teacher';
                                break;
                            case 4:
                            case 5:
                            case 6:
                            case 7: // 员工
                                $table = 'leading_staff_info';
                                break;
                            case 9: // 企业
                                $table = 'leading_company';
                                break;
                            default: // 临时表
                                $table = 'temp_register';
                                break;
                        }
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
    public function productStuId($classId,$addressId)
    {
        //获得1，2位
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
        $where['caseId'] = 8;
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
    
    /**
     * 修改密码
     * @param array $_LS post中的数据
     * @param string $table
     * @param array $user
     * @return array
     */
    public function setPass($_LS,$table,$user)
    {
        $data       = array();
        //获得post中的数据
        @$accNumber = $_LS['accNumber'];
        @$email     = $_LS['email'];
        @$oldPass   = $_LS['oldPass'];
        @$newPass_1 = $_LS['newPass_1'];
        @$newPass_2 = $_LS['newPass_2'];
        if ($accNumber && $email && $oldPass && $newPass_1 && $newPass_2) {
            if (!empty($oldPass) && $this->verifyPass($oldPass,$user)) { // 旧密码不为空且正确
                if ($oldPass != $newPass_1) {
                    if ($newPass_1 == $newPass_2) {
                        if (verifyLen($newPass_1, 6, 15)) {
                            $newPass = myMd5($newPass_1);
                            $res = $this->updatePass($table,$accNumber, $email, $newPass);
                            if($res > 0){
                                $_SESSION['user']['password'] = $newPass;
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
     * 
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
     * 修改密码
     * @param string $table
     * @param string $accNumber
     * @param string $email
     * @param string $newPass_1
     * @return int
     */
    public function updatePass($table,$accNumber,$email,$newPass_1)
    {
        $res            = '';
        $newPass        = myMd5($newPass_1);
        $arr            = array("password" => $newPass);
        $where['stuId'] = $accNumber;
        $where['email'] = $email;
        $res            = parent::update($table,$arr,$where);
        return $res;
    }
}