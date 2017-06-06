<?php
namespace App\admin\Model;

class setStudentModel extends infoModel
{
    private $user         = array();
    private $stuTable     = 'leading_student';
    private $stuInfoTable = 'leading_student_info';
    private $stuWorkTable = 'student_work';
    private $stuProTable  = 'student_project';
    private $projectTable = 'project';
    private $stuEduTable  = 'student_education';
    private $workArr      = array('id','compName','jobName','salary','dateWork','workOut','description','treatment','compAddress','stuId');
    private $addStuProArr = array('stuId','stuDescription','professional');
    private $addProArr    = array('projectName','description','status','startTime','endTime');
    
    public function __construct()
    {
        $obj        = new getStudentModel();
        $this->user = $obj->getUser();
    }
    
    /**
     * 验证登录者身份
     * @param string $accNumber 公司号
     * return boolean
     */
    public function verifyUser($accNumber)
    {
        $obj = new getStudentModel();
        return $obj->verifyUser($accNumber);
    }
    
    /**
     * 修改学生密码
     */
    public function setStuPass()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if(!empty($accNumber) && $this->verifyUser($accNumber)){
            $obj  = new doActionModel();
            $data = $obj->setPass($_LS,$this->stuTable,$this->user);
        }else{
            $data['status'] = 2;
            $data['msg']    = '账号为空或与登陆信息不符';
        }
        return $data;
    }
    
    /**
     * 修改基本信息
     * @return array
     */
    public function setStuBase()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if(!empty($accNumber) && $this->verifyUser($accNumber)){
            
            //验证手机号
            @$mobile        = $_LS['mobile'];
            if(isset($mobile) && !empty($mobile)){
                if(verifyModel::verifyMobile($mobile)){
                    $data['status'] = 3;
                    $data['msg']    = '手机号已注册';
                    return $data;
                }
            }
            
            //验证邮箱
            @$email = $_LS['email'];
            if(isset($email) && !empty($email)){
                if(verifyModel::verifyEmail($email)){
                    $data['status'] = 4;
                    $data['msg']    = '邮箱已注册';
                    return $data;
                }
            }
            
            //修改信息
            $arr            = array_diff_assoc($_LS,array("accNumber"=>$accNumber));//获得修改信息数组
            $where['stuId'] = $accNumber;
            $table          = array($this->stuTable,$this->stuInfoTable);
            $res            = parent::update($table,$arr,$where);
            $data           = parent::formatResponse($res);
            
        }else{
            $data['status'] = 2;
            $data['msg']    = '账号为空或与登陆信息不符';
        }
        return $data;
    }
    
    /**
     * 修改学生工作经验
     */
    public function setStuWork()
    {
        global $_LS;
        $data = array();
        
        //获得工作id和学号
        @$id        = $_LS['id'];
        @$accNumber = $_LS['accNumber'];
        
        if($id && $accNumber){
            if($this->verifyUser($accNumber)){
                
                //获得要更新的条件和更新值
                $where['id']        = $id;
                $where['stuId']     = $accNumber;
                $arr                = array_diff_assoc($_LS,array("id"=>$id,"accNumber"=>$accNumber));
                
                //更新
                if(count($arr) > 0){
                    $res  = parent::update('student_work',$arr,$where);
                    $data = parent::formatResponse($res);
                }else{
                    $data['status'] = 4;
                    $data['msg']    = '没有要修改的信息';
                }
            }else{
                $data['status'] = 3;
                $data['msg']    = '与登陆信息不符';
            }
        }else{
            $data['status'] = 2;
            $data['msg']    = '没有身份标识符';
        }
        return $data;
    }
    
    /**
     * 修改学生自己的项目经验
     */
    public function setStuProject()
    {
        global $_LS;
        $data = array();
        
        @$accNumber = $_LS['accNumber'];
        @$projectId = $_LS['projectId'];
        if(isset($accNumber) && $this->verifyUser($accNumber)){
            //判断是否是学生自己的项目
            $where['projectId'] = $projectId;
            $arr                = array('type');
            $res                = parent::fetchOne_byArr($this->projectTable,$arr,$where);
            if(isset($res['type']) && $res['type'] == 2){
            
                //修改项目
                $where['stuId']     = $accNumber;
                $table              = array($this->stuProTable,$this->projectTable);
                $arr                = array_diff_assoc($_LS,array("accNumber"=>$accNumber,"projectId"=>$projectId,"type"=>2));
                if(count($arr) > 0){
                    $res                = parent::update($table,$arr,$where);
                    $data               = parent::formatResponse($res);
                }else{
                    $data['status'] = 4;
                    $data['msg']    = '没有要修改的信息';
                }
            }else{
                $data['status'] = 3;
                $data['msg']    = '该项目不能修改';
            }
            
        }else{
            $data['status'] = 2;
            $data['msg']    = '账号为空或与登陆信息不符';
        }
        
        
        return $data;
    }
    /**
     * 修改学生教育经历
     * @return array
     */
    public function setStuEducation()
    {
        global $_LS;
        $data = array();
        
        @$accNumber = $_LS['accNumber'];
        @$id        = $_LS['id'];
        if($accNumber && $id){
            if($this->verifyUser($accNumber)){
                $arr = array_diff_assoc($_LS,array("accNumber"=>$accNumber,"id"=>$id));
                if(count($arr) > 0){
                    //修改信息
                    $table          = $this->stuEduTable;
                    $where['stuId'] = $accNumber;
                    $where['id']    = $id;
                    $res            = parent::update($table,$arr,$where);
                    $data           = parent::formatResponse($res);
                }else{
                    $data['status'] = 4;
                    $data['msg']    = '没有要修改的信息';
                }
            }else{
                $data['status'] = 3;
                $data['msg']    = '账号为空或与登陆信息不符';
            }
        }else{
            $data['status'] = 2;
            $data['msg']    = '没有身份标识符';
        }
        return $data;
    }
    
    /**
     * 添加学生教育经验
     * @return array
     */
    public function addStuEducation()
    {
        global $_LS;
        $data = array();
        
        @$accNumber = $_LS["accNumber"];
        if(isset($accNumber) && $this->verifyUser($accNumber)){
            
            //获得插入的数组
            $arr          = array_diff_assoc($_LS,array("accNumber"=>$accNumber));
            $arr['stuId'] = $accNumber;
            $obj          = new getStudentModel();
            
            //验证数组是否安全
            $arrFlip = array_flip($obj->getArr('educationArr'));
            if( empty(array_diff_key($arr,$arrFlip))){            //插入信息安全
                if(count(array_diff_key($arrFlip,$arr)) == 1){    //插入信息集全
                    $table        = $this->stuEduTable;
                    $count = $this->countStuId($table,$accNumber);//获得数据库中已有的记录数目
                    if(end($count) < 5){                          //少于5条时可以插入
                        $res          = parent::insert($table,$arr);
                        $data         = parent::formatResponse($res);
                    }else{
                        $data['status'] = 4;
                        $data['msg']    = '已超过数额限制，不能再添加';
                    }
                }else{
                    $data['status'] = 5;
                    $data['msg']    = '添加信息不全，不能添加';
                }
                
            }else{
                $data['status'] = 3;
                $data['msg']    = '信息不安全，不能添加';
            }
        }else{
            $data['status'] = 2;
            $data['msg']    = '账号为空或与登陆信息为空';
        }
        return $data;
    }
    
    //添加一条工作经验
    public function addStuWork()
    {
        global $_LS;
        $data = array();
        
        @$accNumber = $_LS['accNumber'];
        if(isset($accNumber) && $this->verifyUser($accNumber)){
            //获得插入的数组
            $arr          = array_diff_assoc($_LS,array("accNumber"=>$accNumber));
            $arr['stuId'] = $accNumber;
            
            //验证数组是否安全
            $arrFlip = array_flip($this->workArr);
            if( empty(array_diff_key($arr,$arrFlip))){            //插入信息安全
                if(count(array_diff_key($arrFlip,$arr)) == 1){    //插入信息集全
                    $table        = $this->stuWorkTable;
                    $count = $this->countStuId($table,$accNumber);//获得数据库中已有的记录数目
                    if(end($count) < 10){                          //少于5条时可以插入
                        $res          = parent::insert($table,$arr);
                        $data         = parent::formatResponse($res);
                    }else{
                        $data['status'] = 4;
                        $data['msg']    = '已超过数额限制，不能再添加';
                    }
                }else{
                    $data['status'] = 5;
                    $data['msg']    = '添加信息不全，不能添加';
                }
            
            }else{
                $data['status'] = 3;
                $data['msg']    = '信息不安全，不能添加';
            }
        }else{
            $data['status'] = 2;
            $data['msg']    = '账号为空或与登陆信息为空';
        }
        return $data;
    }
    
    public function addStuPorject()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if (isset($accNumber) && $this->verifyUser($accNumber)) {
            $arr            = array_diff_assoc($_LS,array("accNumber"=>$accNumber));
            $arr['stuId']   = $accNumber;
            $data['status'] = 3;
            $data['msg']    = '添加信息不全';
            if (count(array_diff_key(array_flip($this->addStuProArr),$arr)) == 0 ) {
                //先插入student_project表，获得插入id
                foreach ($this->addStuProArr as $val){
                    $arr_2[$val] = $arr[$val];
                }
                if (count(array_diff_key(array_flip($this->addProArr),$arr)) == 0 ) {
                    $arr_3         = array_diff_assoc($arr,$arr_2);
                    $arr_3['type'] = 2;
                    $res           = parent::insert($this->projectTable,$arr_3);
                    if($res > 0){
                        $arr_2['projectId'] = $res;
                        $resp               = parent::insert($this->stuProTable,$arr_2);
                        $data               = parent::formatResponse($resp);
                    }else{//需要回滚
                        
                    }
                }
            }
        } else {
            $data['status'] = 2;
            $data['msg']    = '账号为空或与登陆信息为空';
        }
        
        return $data;
    }
    
    
    public function countStuId($table,$stuId)
    {
        $sql = " SELECT COUNT(*) FROM {$table} WHERE stuId = '{$stuId}' ";
        return parent::fetchOne_bySql($sql,$table);
    }
}