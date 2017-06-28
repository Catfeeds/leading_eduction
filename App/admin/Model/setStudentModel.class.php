<?php
namespace App\admin\Model;

class setStudentModel extends infoModel
{
    const WORKNUM         = 10;                             //最多添加工作经验的数量
    const EDUNUM          = 5;                              //最多添加教育的数量
    const PRONUM          = 10;                             //每个学员最多能添加项目的数量
    const SENDMNUM        = 30;                             //每月最多投简历的数量
    const SENDDNUM        = 10;                             //每天最多投简历的数量
    const IMGURL          = '';
    const DESTINATION     = './static/admin/images/uploads/image_149/';
    
    private $user         = array();
    private $obj;
    //表名
    private $stuTable     = 'leading_student';
    private $stuInfoTable = 'leading_student_info';
    private $stuWorkTable = 'student_work';
    private $stuProTable  = 'student_project';
    private $projectTable = 'project';
    private $stuEduTable  = 'student_education';
    private $jobTab       = 'leading_job';
    private $resumeLogTab = 'leading_resume_log';
    
    //相关属性
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
            $data  = $obj->setPass($_LS,$this->stuTable,$this->user,$where);
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
            } else {
                $data['status'] = 5;
            }
        } else {
            $data['status'] = 2;
        }
        
        return $data;
    }
    
    /**
     * 投递简历
     * return array
     */
    public function sentResume()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];                                //学号
        @$jobId     = $_LS['jobId'];                                    //职位号
        if ($accNumber && $jobId) {
            if ($this->verifyUser($accNumber)) {
                if ($this->verifyJob($jobId)) {                         //存在该职位
                    if ($this->verifyJobStatus($jobId)) {                       //需要招人
                        $data = $this->sendResumeNum($accNumber,$jobId);        //验证或投递简历
                    } else {
                        $data['status'] = 5;
                        $data['msg']    = '职位已招满';
                    }
                } else {
                    $data['status'] = 4;
                    $data['msg']    = '不存在该职位';
                }
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    /**
     * 检查该职位是否存在
     * @param string $jobId
     * @return boolean 存在 返回 true
     */
    public function verifyJob($jobId)
    {
        $res  = false;
        $data = parent::fetchOne_byArr($this->jobTab,array('compId'),array('jobId' => $jobId));
        if (count($data) > 0) {
            $res = true;
        }
        return $res;
    }
    
    /**
     * 检验该职位是否还招人
     * @param int $jobId
     * @return boolean
     */
    public function verifyJobStatus($jobId)
    {
        $res  = false;
        $data = parent::fetchOne_byArr($this->jobTab,array('status'),array('jobId' => intval($jobId)));
        if (count($data) > 0 && isset($data['status'])) {
            if ($data['status'] == 0) {
                $res = true;
            }
        }
        return $res;
    }
    
    /**
     * 查看投递记录，并返回相应的信息，投递要求：30天投递过的职位不能再投，一天不能超过十次，30天不能超过30次
     * 还需补充会员制
     * @param string $accNumber 投递账号
     */
    public function sendResumeNum($accNumber,$jobId)
    {
        $data = array();
        $where_2        = array('jobId' => $jobId, 'accNumber' => $accNumber, 'where2' => ' ORDER BY resumeTime DESC ');
        $arr            = array('resumeTime','d_count','m_count','jobId');
        $table          = $this->resumeLogTab;
        $where          = array('accNumber' => $accNumber,'where2' => ' ORDER BY resumeTime DESC ');  //该账号上一次投递                       
        $res            = parent::fetchOne_byArr($table,$arr,$where);
        $arr_2          = array('jobId' => $jobId,'accNumber' => $accNumber,'resumeTime' => time());
        if (count($res) > 0) {                                                      //之前投递过简历
            if (verifyInMonth($res['resumeTime'])) {                                //与上次投递时间在同一个月中
                if ($res['m_count'] < self::SENDMNUM ) {                            //本月投递次数没超过限制
                    if (verifyInDay($res['resumeTime'])) {                          //与上次投递时间在同一天中
                        if ($res['d_count'] < self::SENDDNUM) {                     //当天投递次数没超过限制
                            $resp = parent::fetchOne_byArr($table,array('resumeTime'),$where_2); //获得之前投递此职位的信息
                            if (count($resp) > 0) {                                              //投递过
                                if (verifyInterVal($resp['resumeTime'],30)) {                    //30天内投过该职位
                                    $data['status'] = 6;
                                    $data['msg']    = '30内不能重复投递相同职位';
                                } else {
                                    $arr_2['d_count'] = intval($res['d_count']) + 1;            //当天投递次数加1
                                    $arr_2['m_count'] = intval($res['m_count']) + 1;            //当月投递次数加1
                                }
                            } else {                                                            //没投过
                                $arr_2['d_count'] = intval($res['d_count']) + 1;                //当天投递次数加1
                                $arr_2['m_count'] = intval($res['m_count']) + 1;                //当月投递次数加1
                            }
                        } else {
                            $data['status'] = 5;
                            $data['msg']    = '今日投递简历已到达'.self::SENDDNUM.'次的限额';
                        }
                    } else {                                                //不在同一天中
                        $arr_2['d_count'] = 1;                              //当天投递次数为1
                        $arr_2['m_count'] = intval($res['m_count']) + 1;    //当月投递次数加1
                    }
                } else {                                                    //不能再投
                    $data['status'] = 4;
                    $data['msg']    = '本月投简历已达到'.self::SENDMNUM.'次的限额';
                }
            } else {                                                        //不在同一个月中
                $arr_2['d_count'] = 1;                                      //当天投递次数为1
                $arr_2['m_count'] = 1;                                      //当月投递次数为1
            }
        } else {                                                            //之前没投简历
            $arr_2['d_count'] = 1;                                          //当天投递次数为1
            $arr_2['m_count'] = 1;                                          //当月投递次数为1
        }
        if (count($data) == 0) {                                            //一切正常，投递简历
            $res  = parent::insert($table,$arr_2);                          //投递简历
            $data = parent::formatResponse($res);                           //格式化结果集
        }
        return $data;
    }
    
    /**
     * 上传头像
     */
    public function uploadImg()
    {
        $data   = array();
        @$stuId = $this->user['stuId'];
        if (!empty($stuId)) {
            $table = $this->stuInfoTable;
            $where = array('stuId' => $stuId);
            $obj   = new doActionModel();
            $data  = $obj->uploadPic($table,$where,self::DESTINATION);
        } else {
            $data['status'] = 3;
        }
        return $data;
    }
    /**
     * 存入缩略图url地址
     * @param 身份标识符 $stuId
     * @param 当前文件所在路径，相对路径 $destination
     */
    public function uploadPhoto($stuId,$destination)
    {
        $des   = preg_replace('/^[\.]/',' ',$destination);
        $url   = 'http://'.$_SERVER['HTTP_HOST'].'/leading'.$des;
        $table = $this->stuInfoTable;
        $arr   = array('picUrl' => $url);
        $where = array('stuId' => $stuId);
        return parent::update($table,$arr,$where);
        
    }
    
}