<?php
namespace App\admin\Model;

class doActionModel
{
    /**
    * 重置密码
    * @date: 2017年5月12日 下午1:21:02
    * @author: lenovo2013
    * @return:array
    */
    public function resetPassword()
    {
        $res = '';
        // 获得post中的数据
        @$caseId = intval(daddslashes($_POST['caseId']));
        @$accNumber = strval(daddslashes($_POST['accNumber']));
        @$resetPass = myMd5(strval(daddslashes($_POST['password'])));//md5密钥加密
        if ($caseId && $accNumber && $resetPass) { // 数据都存在
            $arr = array( "password" => $resetPass  ); // 要更新的字段和值
            $where = '';
            if (isMobile($accNumber) == 1) {
                $where = " mobile = '{$accNumber}' ";
            }
            switch ($caseId) {
                case 1: // 学生
                    $table = 'leading_student';
                    $where = empty($where) ? " stuId = '{$accNumber}' " : $where;
                    break;
                case 2:
                case 3: // 教师
                    $table = 'leading_teacher';
                    $where = empty($where) ? " teacherId = '{$accNumber}' " : $where;
                    break;
                case 4:
                case 5:
                case 6:
                case 7: // 员工
                    $table = 'leading_staff_info';
                    $where = empty($where) ? " accNumber = '{$accNumber}' " : $where;
                    break;
                case 9: // 企业
                    $table = 'leading_company';
                    break;
                default: // 临时表
                    $table = 'temp_register';
                    break;
            }
            if (!empty($where)) {
                $res = $this->updateInfo_byArr($table,$arr,$where);
                if ($res > 0) {
                    $data['status'] = 0;
                    $data['msg'] = 'reset successed';
                } else {
                    $data['status'] = 1;
                    $data['msg'] = '重置密码失败，可能的原因是前后两个密码一样，请换个密码试试';
                }
            } else {
                $data['status'] = 2;
                $data['msg'] = 'caseId传参不正确';
            }
        } else {
            $data['status'] = 3;
            $data['msg'] = 'post中数据不齐全';
        }
        return $data;
    }
    /**
    * 根据不同的模型更新数据
    * @date: 2017年5月12日 下午1:52:05
    * @author: lenovo2013
    * @param: $table 表模型名
    * @param $arr array 更新的字段和值组成
    * @param $where string 更新条件
    * @return:int 记录受影响的条数
    */
    public function updateInfo_byArr($table,$arr,$where)
    {
        $obj = M("{$table}");
        return $obj->updateInfo_byArr($arr,$where);
    }
    /* if(isMobile($accNumber)){
     $caseId = 4;
     }
     switch($caseId){//判断是”谁“要重置密码
     case 1://学生
     $table = 'student';
     $where = " stuId = '{$accNumber}' ";
     break;
     case 2://教师
     $table = 'teacher';
     $where = " teacherId = '{$accNumber}' ";
     break;
     case 3://员工
     $table = 'leading_staff_info';
     $where = " accNumber = '{$accNumber}' ";
     break;
     case 4:
     default://默认手机号用户
     $table = array('student','teacher','leading_staff_info','leading_company','temp_register');
     $where = " mobile = '{$accNumber}' ";
     break;
     }
     if(is_array($table)){//手机号用户
     foreach ($table as $val){
     $res = $this->updateInfo_byArr($val,$arr,$where);
     if($res > 0){//更新成功
     $data['status'] = 0;
     $data['msg'] = 'reset success';
     break;
     }
     }
     }else{//学号、工号、教师号登录
     $res = $this->updateInfo_byArr($table,$arr,$where);
     if($res > 0){//更新成功
     $data['status'] = 0;
     $data['msg'] = 'reset success';
     }
     }
     if(!(isset($data) && is_array($data))){//更新失败
     $data['status'] = 1;
     $data['msg'] = 'reset failed,可能前后修改的密码一样，请换个密码试试';
     }
     }else{//post中信息不齐全
     $data['status'] = 2;
     $data['msg'] = '信息不全';
     } */
}