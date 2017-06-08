<?php
namespace App\admin\Model;

class setStudentModel extends infoModel
{
    const WORKNUM         = 10;
    const EDUNUM          = 5;
    const PRONUM          = 10;
    private $user         = array();
    private $obj;
    //表名
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
        $this->obj  = $obj;
    }
    
    /**
     * 验证登录者身份
     * @param string $accNumber 公司号
     * return boolean
     */
    public function verifyUser($accNumber)
    {
        return $this->obj->verifyUser($accNumber);
    }
    public function getTable($table)
    {
        return $this->obj->getTable($table);
    }
    public function getArr($arr)
    {
        return $this->obj->getArr($arr);
    }
    /**
     * 修改学生密码
     */
    public function setStuPass()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        if (!empty($accNumber) && $this->verifyUser($accNumber)) {
            $obj   = new doActionModel();
            $where = array("stuId"=>$accNumber);
            $data  = $obj->setPass($_LS,$this->stuTable,$this->user);
        } else {
            $data['status'] = 2;
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
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        if (isset($accNumber)) {
            if ($this->verifyUser($accNumber)) {
                $table = array($this->stuTable,$this->stuInfoTable);
                $obj   = new doActionModel();
                $data  = $obj->setObjectBase($_LS,$this->getArr('baseArr'),$table,array("stuId"=>$accNumber));     //修改基本信息
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
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
                }
            }else{
                $data['status'] = 3;
            }
        }else{
            $data['status'] = 2;
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
                }
            }else{
                $data['status'] = 3;
                $data['msg']    = '该项目不能修改';
            }
            
        }else{
            $data['status'] = 2;
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
                }
            }else{
                $data['status'] = 3;
            }
        }else{
            $data['status'] = 2;
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
            if ( empty(array_diff_key($arr,$arrFlip))) {                                                  //插入信息安全
                if (count(array_diff_key($arrFlip,$arr)) == 1) {                                          //插入信息集全
                    $table        = $this->stuEduTable;
                    $count = parent::getNum($table,array('id'),array('stuId'=>$accNumber));               //获得数据库中已有的记录数目
                    if ($count < self::EDUNUM) {                                                          //少于5条时可以插入
                        $res          = parent::insert($table,$arr);
                        $data         = parent::formatResponse($res);
                    } else {
                        $data['status'] = 6;
                    }
                } else {
                    $data['status'] = 9;
                }
            } else {
                $data['status'] = 5;
            }
        } else {
            $data['status'] = 2;
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
            if ( empty(array_diff_key($arr,$arrFlip))){                                               //插入信息安全
                if (count(array_diff_key($arrFlip,$arr)) == 1) {                                      //插入信息集全
                    $table        = $this->stuWorkTable;
                    $count = parent::getNum($table,array('stuId'),array("stuId"=>$accNumber));        //获得数据库中已有的记录数目
                    if ($count < self::WORKNUM) {                                                     //少于5条时可以插入
                        $res          = parent::insert($table,$arr);
                        $data         = parent::formatResponse($res);
                    } else {
                        $data['status'] = 6;
                    }
                } else {
                    $data['status'] = 9;
                }
            
            }else{
                $data['status'] = 5;
            }
        }else{
            $data['status'] = 2;
        }
        return $data;
    }
    
    //添加学员项目经验
    public function addStuPorject()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        if (isset($accNumber) && $this->verifyUser($accNumber)) {
            $arr            = array_diff_assoc($_LS,array("accNumber"=>$accNumber));
            $arr['stuId']   = $accNumber;
            $data['status'] = 3;
            $data['msg']    = '添加信息不全';
            if (count(array_diff_key(array_flip($this->addStuProArr),$arr)) == 0 ) {                //信息安全
                $num = parent::getNum($this->stuProTable,array('id'),array("stuId"=>$accNumber));   //获取到已有的数据条数
                //先插入project表，获得插入id        
                if ($num < self::PRONUM) {                                                          //确保没有超过数额限制
                    foreach ($this->addStuProArr as $val){
                        $arr_2[$val] = $arr[$val];
                    }
                    if (count(array_diff_key(array_flip($this->addProArr),$arr)) == 0 ) {           //信息安全
                        $arr_3         = array_diff_assoc($arr,$arr_2);
                        $arr_3['type'] = 2;
                        $res           = parent::insert($this->projectTable,$arr_3);                //插入project表
                        if ($res > 0) {
                            $arr_2['projectId'] = $res;
                            $resp               = parent::insert($this->stuProTable,$arr_2);        //插入student_project表
                            $data               = parent::formatResponse($resp);                    //格式化结果集
                        } else {                                                                    //需要回滚
                    
                        }
                    } else {
                        $data['status'] = 5;
                    }
                } else {
                    $data['status'] = 6;
                }
            }
        } else {
            $data['status'] = 2;
        }
        
        return $data;
    }
}