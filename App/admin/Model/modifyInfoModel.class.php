<?php
namespace App\admin\Model;

class modifyInfoModel
{
    private $user = array();
    
    /**
     * 构造函数
     */
    public function __construct()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
            $this->user = $_SESSION['user'];
        }
    }
    
    /**
     * 修改信息
     */
    public function modifyInfo()
    {
        //获得post中的值
        $arr = array();
        @$arr['mobile'] = strval(daddslashes($_POST['mobile']));
        @$arr['caseId'] = intval(daddslashes($_POST['caseId']));
        @$arr['param']  = strval(daddslashes($_POST['param']));
        @$arr['info']   = $_POST['info'];
        if($arr['mobile'] && $arr['caseId'] && $arr['param'] && count($arr['info']) > 0 ){
            if(!empty($this->user) && $this->user['mobile'] == $arr['mobile']){//与登陆信息相符
                $data = $this->setInfo($arr);//处理修改信息
            }else{
                $data['status'] = 2;
                $data['msg']    = '与登录信息不符，请重新登陆';
                $obj            = new checkModel();//注销登录信息
                $obj->logout();
            }
        }else{
            $data['status'] = 1;
            $data['msg']    = '提交信息不全';
        }
        return $data;
    }
    /**
     * 处理修改信息
     * @param array arr
     * return array
     */
    public function setInfo($arr)
    {
        $data = array();
        switch($arr['caseId'])
        {
            case 1://学生
                $data = $this->modifyStuInfo($arr);
                break;
            case 2://教师
                $data = $this->modifyTeacherInfo($arr);
                break;
            case 3://班主任
                $data = $this->modifyMasterInfo($arr);
                break;
            case 5://超级管理员
                $data = $this->modifyAdmin($arr);
                break;
            case 6://编辑员
                $data = $this->modifyEditor($arr);
                break;
            case 9://企业
                $data = $this->modifyCompany($arr);
                break;
            default:
                $data['status'] = 8;
                $data['msg'] = 'caseId传参不正确';
                break;
        }
        return $data;
    }
    /**
     * 调用修改学生信息模型
     * @param array
     * return array
     */
    public function modifyStuInfo($arr)
    {
        $obj = new modifyStuInfoModel();
        return $obj->modifyStuInfo($arr);
    }
}