<?php
namespace  App\admin\Model;
use App\admin\Model\getStuInfoModel;
class getInfoModel
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
    /**
     * 析构函数，销毁user信息
     */
    public function __destruct()
    {
        if($this->user){
            unset($this->user);
        }
    }
    
    /**
    * 获得登陆者的基本信息
    * @date: 2017年5月12日 下午5:51:33
    * @author: lenovo2013
    * @return:array
    */
    public function getLoginedBase()
    {
        if($this->checkLogined()){
            @$caseId = intval(daddslashes($_POST['caseId']));
            @$accNumber = strval(daddslashes($_POST['accNumber']));
            @$param = !empty($_POST['param'])?strval(daddslashes($_POST['param'])):'';
            if(!(empty($caseId && $accNumber))){//存在角色参数
                switch ($caseId) {
                    case 1://学生
                        $obj = new getStuInfoModel();
                        $data['info'] = $obj->getStuInfo($accNumber,$param);
                        break;
                    case 2://教师
                        break;
                    case 3://班主任
                        break;
                    case 9://企业
                        break;
                    case 5://超级管理员
                        break;
                    case 6://编辑员
                        break;
                    case 4://项目经理
                        break;
                    case 7://内部员工
                        break;
                    default:
                        break;;
                }
            }else{
                $data['status'] = 2;
                $data['msg'] = '没有传caseId或accNumber的值';
            }
        }else{
            $data['status'] = 1;
            $data['msg'] = '没有登录，请先登录';
        }
        return myMerge($data);
    }
    /**
     * 检查登录
     * return boolean
     */
    public function checkLogined()
    {
        if($this->user && is_array($this->user) && count($this->user)>0){//登陆
            $res  = true;
        }else{
            $res = false;
        }
        return $res;
    }
}