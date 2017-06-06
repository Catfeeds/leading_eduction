<?php
namespace App\admin\Model;

class modifyStuInfoModel extends infoModel
{
    //定义好修改内容
    private $passwordArr  = array('email','oldPassword','newPassword','newPassword2');
    private $baseArr      = array('mobile','email','sex','age','bloodType','provinceId','homeAddress','description');
    private $workArr      = array('compName','compAdress','jobName','salary');
    private $educationArr = array('major','eduSchool','dateinto','dateout','highest');
    private $projectArr   = array('projectName','description','stuDescription','professional','startTime','endTime','url');
   
    /**
     * 修改学生信息
     * return array 
     */
    public function modifyStuInfo( array $arr)
    {
        $data = array();
        switch($arr['param'])
        {
            case 'password': //修改密码
                $data = $this->modifyPass($arr['mobile'],$arr['info']);
                break;
            case 'base':     //修改基本信息
                $data = $this->modifyBase($arr['mobile'],$arr['info']);
                break;
            case 'work':     //修改工作经验
                break;
            case 'education'://修改教育经历
                break;
            case 'project':  //修改项目经验
                break;
        }
        return $data;
    }
    /**
     * 修改密码，注意，不是重置密码
     * @param string $mobile 手机号
     * @param array $infoArr 修改信息数组
     * @return array
     */
    public function modifyPass($mobile,array $infoArr)
    {
        $data    = array();
        $res     = '';
        //先验证修改信息数组$infoArr信息是否集全
        $infoArr = array_filter($infoArr);//去掉数组的空值
        if($this->verifyArr($infoArr,$this->passwordArr,true)){
            //修改信息
            if($this->verifyPassword($infoArr['oldPassword'])){//旧密码和数据表中一致
                if($infoArr['newPassword'] === $infoArr['newPassword2']){//新密码和确认密码一致
                    if($infoArr['oldPassword'] != $infoArr['newPassword']){//新密码和旧密码不一致
                        $arr['password'] = myMd5($infoArr['newPassword']);
                        $where['mobile'] = $mobile; 
                        $where['email']  = $infoArr['email']; 
                        $res             = parent::update('leading_student',$arr,$where);//更新密码
                        if($res > 0){//更新成功
                            $data['status'] = 0;
                            $data['msg']    = 'success';
                            $_SESSION['user']['password'] = $arr['password'];//更新session中的密码
                        }else{
                            $data['status'] = 6;
                            $data['msg']    = '修改失败';
                        }
                    }else{
                        $data['status'] = 7;
                        $data['msg']    = '和旧密码一致，不用修改';
                    }
                }else{
                    $data['status'] = 4;
                    $data['msg']    = '新密码和确认密码不一致，不能修改';
                }
            }else{
                $data['status'] = 3;
                $data['msg']    = '旧密码不对';
            }
        }else{//不全
            $data['status'] = 5;
            $data['msg']    = '修改信息不全';
        }
        return $data;
    }
    /**
     * 修改基本信息
     * @param $mobile string 手机号
     * @param $infoArr array 需要修改信息的数组
     * @return array 
     */
    public function modifyBase($mobile,$infoArr)
    {
        $data    = array();
        $infoArr = array_filter($infoArr);//去除数组中为空的项
        if($this->verifyArr($infoArr,$this->baseArr)){//验证修改信息数组是否合格
            //$where
            $res = parent::update($table,$arr,$where,$tableArr=null);
        }else{
            $data['status'] = 1;
            $data['msg']    = '修改信息字段不正确';
        }
        $data['sessionId'] = session_id();
        return $data;
    }
    /**
     * 比较两个数组
     * @param array $infoArr 
     * @param array $compareArr
     * @equal boolean 是否完全一样
     * return boolean
     */
    public function verifyArr($infoArr,$compareArr,$equal = false)
    {
        $res    = false;
        $count1 = count($infoArr);
        $count2 = count($compareArr);
        $keys   = array_keys($infoArr);//取出信息数组的键值
        if($equal ){                   //需要key值完全一样
            if($count1 === $count2){
                $compare = array_diff($keys,$compareArr);
                if(count($compare) == 0){
                    $res = true;
                }
            }
        }else{                     //不需要key值完全一样
            if($count1  <= $count2){//数组的长度不大于比较数组
                $compare = array_diff($keys,$compareArr);
                if(count($compare) == 0){
                    $res = true;
                }
            }
        }
        return $res;
    }
    /**
     * 验证密码是否和数据表中的一致
     * @param string $password
     * return boolean
     */
    public function verifyPassword($password)
    {
        $res         = false;
        $oldPassword = myMd5($password);//加密
        if($oldPassword == $_SESSION['user']['password']){
            $res = true;
        }
        return $res;
    }
}