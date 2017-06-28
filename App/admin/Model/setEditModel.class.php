<?php
namespace App\admin\Model;

class setEditModel extends infoModel
{
    const DESTINATION_CAROUSEL = './static/index/images/carousel/';
    const DESTINATION_VEDIO    = './static/index/images/vedio/';
    
    private $user = array();
    private $obj;
    public function __construct()
    {
        $obj        = new getEditModel();
        $this->user = $obj->getUser();
        $this->obj  = $obj;
    }
    
    /**
     * 处理临时注册表信息，注册信息是否通过
     */
    public function handleTempInfo()
    {
        global $_LS;
        $data = array();
        //获得提交的信息
        @$accNumber = $_LS['accNumber'];
        @$tmpId     = $_LS['tmpId'];
        @$status    = intval($_LS['status']);
        if($accNumber && $tmpId && $status) {                           //提交信息集全
            if ($this->obj->verifyUser($accNumber)) {                   //符合已登陆信息
                $data = $this->handleInfo($accNumber,$tmpId,$status);   //处理信息，是否通过
                //$data = parent::formatResponse($res);                   //格式化结果集
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 处理信息，是否通过
     */
    public function handleInfo($accNumber,$tmpId,$status)
    {
        $data = array();
        //tmpId账号信息是否存在
        $resp = $this->getTmpInfo($tmpId);                      //通过tempId获得所有注册信息            
        if (count($resp) > 0) {
            if ($status == 1) {
                @$mobile = $resp['mobile'];
                if ($mobile) {
                    if (!verifyModel::verifyMobile($mobile,$resp['caseId']) && !verifyModel::verifyEmail($resp['email'],$resp['caseId'])) {
                        $data = $this->handleCheck($tmpId,$resp);       //处理通过的信息
                        if ($data['status'] == 0) {
                            $this->handleChecked($tmpId,1);
                        }
                    } else {
                        $data['status'] = 20;
                        //$data['msg']    = '手机号或邮箱已注册';
                    }
                } else {
                    $data['status'] = 9;
                    //$data['msg']    = '信息不集全';
                }
            } else {                                            //处理未通过的信息
                $data = $this->handleChecked($tmpId);           //改写账号状态，status=2
            }
        } else {
            $data['status'] = 14;
            //$data['msg']    = '该账号错误，不存在相关信息';
        }
        return $data;
    }
    
    /**
     * 通过tmpId获得所有记录信息
     * @param string $tmpId 
     * @return array
     */
    public function getTmpInfo($tmpId)
    {
        $arr   = array('*');                                //所有信息
        $where = array('tmpId' => $tmpId);                  //where tmpId = 'xxx'
        $table = $this->obj->getTable('tempTab');           //temp_register表
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    /**
     * 处理未通过操作，把status设置为2
     * @param string $tmpId
     * @return int 0 failed
     */
    public function handleChecked($tmpId,$status=0)
    {
        $arr   = array('status' => $status);                      //set status = 0
        $where = array('tmpId' => $tmpId);
        $table = $this->obj->getTable('tempTab');
        return parent::update($table,$arr,$where);
    }
    
    /**
     * 处理通过操作，需要把相关信息重写写入申请的角色表中，成功后需要删除此记录
     * @param string $tmpId
     * @param array $resp 已有的信息
     * @return array
     */
    public function handleCheck($tmpId,$resp)
    {
        $data   = array();
        $caseId = intval($resp['caseId']);
        switch ($caseId) {
            case 1:
                $data = $this->handleCheckStu($resp);               //处理注册学员
                break;
            case 2:
            case 3:
                $data = $this->handleCheckTeac($resp);              //处理注册教师or班主任
                break;
            case 4:
                break;
            case 7:
                $data = $this->handleCheckStaff($resp);             //处理内部员工注册信息
                break;
            case 9:
                $data = $this->handleCheckComp($resp);              //处理公司注册信息
                break;
        }
        return $data;
    }
    
    /**
     * 处理学员注册
     * @param array $resp 已注册信息
     */
    public function handleCheckStu($resp)
    {
        global $_LS;
        @$classId   = $_LS['classId'];
        @$addressId = $_LS['addressId'];
        if ($classId && $addressId) {
            $obj   = new doActionModel();
            $stuId = $obj->productStuId($classId,$addressId);               //根据classId和addressId生成学号
            $arr   = array('stuId' => $stuId,'name' => $resp['name'],'mobile' => $resp['mobile'],'email' => $resp['email'],'password' => $resp['password'],'status' => 1,'caseId' => 1,'dateinto' => time());
            $res   = parent::insert($this->obj->getTable('stuTab'),$arr);   //插入student表
            if ($res > 0) {
                $arr_2     = array('stuId' => $stuId,'qq' => $resp['qq'],'wechat' => $resp['wechat'],'classId' => $classId);
                $response  = parent::insert($this->obj->getTable('stuInfoTab'),$arr_2);     //插入student_info表
                if ($response > 0) {
                    if ($resp['recommendId']) {                               //有推荐人，写入推荐表
                        $dateinto = empty($resp['dateinto'])?time():$resp['dateinto'];
                        $arr_3    = array('recommendId' => $resp['recommendId'],'stuId' => $stuId,'dateinto' => $dateinto);
                        parent::insert($this->obj->getTable('recomTab'),$arr_3);
                    }
                    //删除临时注册表中的数据
                    //$this->deleteTmp($resp['tmpId']);
                    $data['status'] = 0;
                    $data['msg']    = 'success';
                } else {
                    $data['status'] = 1;
                }
            } else {
                $data['status'] = 3;
                $data['msg']    = '系统维修中';
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 根据tmpId删除记录
     * @param unknown $tmpId
     */
    public function deleteTmp($tmpId)
    {
        $where = array('tmpId' => $tmpId);
        $table = $this->obj->getTable('tempTab');
        return parent::deleteRow($table,$where);
    }
    
    /**
     * 处理教师注册
     * @param array $resp
     * @return array
     */
    public function handleCheckTeac($resp)
    {
        $obj       = new doActionModel();
        $teacherId = $obj->productTeacherId();          //生成教师号
        $arr       = array('teacherId' => $teacherId,'name' => $resp['name'],'mobile' => $resp['mobile'],'password' => $resp['password'],'email' => $resp['email'],'status' => 1,'caseId' => $resp['caseId'] ,'dateinto' => time());
        $res       = parent::insert($this->obj->getTable('teachTab'),$arr);
        return parent::formatResponse($res);
    }
    /**
     * 处理企业注册信息
     * @param array $resp
     * @return array
     */
    public function handleCheckComp($resp)
    {
        $obj    = new doActionModel();
        $compId = $obj->productCompId();
        $arr    = array('compId' => $compId,'compName' => $resp['name'],'mobile' => $resp['mobile'],'password' => $resp['password'],'email' => $resp['email'],'status' => 1,'caseId' => $resp['caseId'] ,'dateinto' => time());
        $res    = parent::insert($this->obj->getTable('compTab'),$arr);
        return parent::formatResponse($res);
    }
    
    /**
     * 处理员工注册
     * @param array $resp
     * @return array
     */
    public function handleCheckStaff($resp)
    {
        $obj       = new doActionModel();
        $accNumber = $obj->productStaffId();
        $arr       = array('accNumber' => $accNumber,'name' => $resp['name'],'mobile' => $resp['mobile'],'password' => $resp['password'],'email' => $resp['email'],'status' => 1,'caseId' => 7 ,'workTime' => time());
        $res       = parent::insert($this->obj->getTable('staffInfoTab'),$arr);
        return parent::formatResponse($res);
    }
    
    /**
     * 修改企业状态
     * @return multitype:number string
     */
    public function modifyCompStatus()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];                                            //获得提交的数据
        @$compId    = $_LS['compId'];
        @$status    = intval($_LS['status']);
        if ($accNumber && $compId && isset($status)) {                              //提交信息不为空
            if ($this->obj->verifyUser($accNumber)) {
                $table = $this->obj->getTable('compTab');                           //leading_company表中操作
                $arr   = array('status','id');
                $where = array('compId' => $compId);
                $res   = parent::fetchOne_byArr($table,$arr,$where);                //先获得该企业状态信息
                if (count($res) > 0) {                                              //存在企业信息
                    if (isset($res['status']) && $res['status'] != $status) {       //要修改的状态与之前状态一样时，不用修改
                        $arr_2 = array('status' => $status);
                        $resp  = parent::update($table,$arr_2,$where);              //更新状态
                        $data  = parent::formatResponse($resp);                     //格式化结果集
                    } else {
                        $data['status'] = 16;
                        //$data['msg']    = '已是该状态，不用再修改';
                    }
                } else {
                    $data['status'] = 14;
                    //$data['msg']    = '企业号错误';
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
     * 修改课程信息
     */
    public function modifyCourseInfo()
    {
        global $_LS;
        $data = array();
        @$accNumber   = $_LS['accNumber'];
        @$courseId    = $_LS['courseId'];
        if ($accNumber && $courseId) {
            if ($this->obj->verifyUser($accNumber)) {
                $arr = array_diff($_LS,array('accNumber' => $accNumber,'courseId' => $courseId));           //要操作的信息数组
                if (count($arr) > 0) {
                    $count = parent::verifyCount($arr,$this->obj->getArr('courseInfo'));
                    if ($count == 0) {                                                                      //信息安全
                        $res_2 = $this->verifyCourseId($courseId);
                        if (count($res_2) > 0) {                                                            //课程号正确
                            $table = $this->obj->getTable('courseTab');
                            $where = array('courseId' => $courseId);
                            $data  = parent::update($table,$arr,$where);                                    //修改信息
                            $data  = parent::formatResponse($data);
                        } else {
                            $data['status'] = 14;
                            //$data['msg']    = '课程号不存在';
                        }
                    } else {
                        $data['status'] = 5;
                    }
                } else {
                    $data['status'] = 4;
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
     * 添加课程信息
     */
    public function addCourses()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if ($accNumber) {
            if ($this->obj->verifyUser($accNumber)) {                                       //身份标识符存在且有效
                $arr = array_diff($_LS,array('accNumber' => $accNumber));
                if (count($arr) > 0) {                                                      //存在操作信息
                    $count = parent::verifyCount($arr,$this->obj->getArr('courseInfo'));
                    if ($count == 0) {                                                      //操作的信息安全
                        $doObj           = new doActionModel();
                        $arr['courseId'] = $doObj->productCourseId();
                        $table           = $this->obj->getTable('courseTab');
                        $resp            = $this->getCourseInfo_byName($table,$arr['courseName']);
                        if (count($resp) == 0) {
                            $res             = parent::insert($table,$arr);
                            $res             = ($res === 0)?1:0;
                            $data            = parent::formatResponse($res);
                        } else {
                            $data['status'] = 17;
                            //$data['msg']    = '课程已存在，不用再添加';
                        }
                    } else {
                        $data['status'] = 5;
                    }
                } else {
                    $data['status'] = 4;
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
     * 通过课程名获得课程号
     * @param string $table
     * @param string $courseName
     * @return array
     */
    public function getCourseInfo_byName($table,$courseName)
    {
        $arr   = array('courseId');
        $where = array('courseName' => $courseName);
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    //修改子课程信息
    public function modifySecCourseInfo()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$id        = intval($_LS['secId']);
        if ($accNumber && $id) {
            if ($this->obj->verifyUser($accNumber)) {                                       //身份标识符存在且有效
                $arr   = array_diff($_LS,array('accNumber' => $accNumber,'secId' => $id));
                if (count($arr) > 0) {                                                      //存在需要修改的信息
                    $count = parent::verifyCount($arr,array('content','focus'));            
                    if ($count == 0) {                                                      //要修改的信息安全
                        $where = array('id' => $id);
                        $table = $this->obj->getTable('courseConTab');
                        $res   = parent::update($table,$arr,$where);                        //修改信息
                        $data  = parent::formatResponse($res);                              //格式化结果集
                    } else {
                        $data['status'] = 5;
                    }
                } else {
                    $data['status'] = 4;
                }
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    public function addSecCourse()
    {
        global $_LS;
        $data       = array();
        //获得post中的数据
        @$accNumber = $_LS['accNumber'];
        @$courseId  = $_LS['courseId'];
        @$stageId   = intval($_LS['stageId']);
        @$content   = $_LS['content'];
        @$focus     = $_LS['focus'];
        if ($accNumber) {
            if ($this->obj->verifyUser($accNumber)) {
                if ($courseId && $stageId && $content && $focus) {
                    $arr   = array('courseId' => $courseId,'stageId' => $stageId,'content' => $content,'focus' =>$focus);
                    $table = $this->obj->getTable('courseConTab');
                    $res   = parent::insert($table,$arr);
                    $data  = parent::formatResponse($res);
                } else {
                    $data['status'] = 9;
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
     * 修改编辑员登陆密码
     */
    public function setStaffPass()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        if (isset($accNumber)) {                                //存在账号信息
            if ($this->obj->verifyUser($accNumber)) {           //符合登陆信息
                $obj   = new doActionModel();
                $where = array("accNumber"=>$accNumber);        //修改条件
                $data  = $obj->setPass($_LS,$this->obj->getTable('staffInfoTab'),$this->user,$where);  //修改密码
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 添加班级信息
     * @return array
     */
    public function addClass()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$masterId  = $_LS['masterId'];
        if ($accNumber && $masterId) {
            if ($this->obj->verifyUser($accNumber)) {
                $res = $this->getTeacherName_byId($masterId,3);
                if (isset($res['name']) && $res['name'] == $_LS['name']) {                                  //班主任信息正确
                    $arr = array_diff($_LS,array('accNumber' => $accNumber,'name' => $res['name']));        //需要操作的信息数组
                    if (count($arr) == 7) {
                        $count = parent::verifyCount($arr,$this->obj->getArr('classInfo'));
                        if ($count == 0) {                                                                  //操作信息安全
                            $table = $this->obj->getTable('classTab');
                            $resp  = parent::insert($table,$arr);
                            $data  = parent::formatResponse($resp);
                        } else {
                            $data['status'] = 5;
                        }
                    } else {
                        $data['status'] = 9;
                        //$data['msg']    = '信息不全';
                    }
                } else {
                    $data['status'] = 6;
                    $data['msg']    = '班主任姓名信息不正确';
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
     * 根据班主任号获得其信息
     * @param string $masterId
     * @return array
     */
    public function getTeacherName_byId($masterId,$caseId)
    {
        $where = array('teacherId' => $masterId,'caseId' => $caseId);
        $arr   = array('teacherId','name');
        $table = $this->obj->getTable('teachTab');
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    public function addClassTeacher()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$teacherId = $_LS['teacherId'];
        @$classId   = $_LS['classId'];
        @$name      = $_LS['name'];
        if ($accNumber && $classId) {   
            if ($this->obj->verifyUser($accNumber)) {                                       //身份标识符存在且有效
                if ($teacherId && $name) {
                    $res = $this->getTeacherName_byId($teacherId,2);                        //获得已有的教师信息
                    if (isset($res['name']) && $res['name'] == $name) {                     //教师信息正确
                        if (count($this->getClassTeacherInfo($classId,$teacherId)) == 0) {  //教师先前不存在该班级
                            $table = $this->obj->getTable('teacherClassTab');
                            $arr   = array('classId' => $classId,'teacherId' => $teacherId);
                            $resp  = parent::insert($table,$arr);
                            $data  = parent::formatResponse($resp);
                        } else {
                            $data['status'] = 17;
                            //$data['msg']    = '该教师已存在该班级，不能重复添加';
                        }   
                    } else {
                        $data['status'] = 13;
                        //$data['msg']    = '教师信息不正确';
                    }
                } else {
                    $data['status'] = 4;
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
     * 获得leading_class_teacher表中的信息
     * @param int $classId  班级号
     * @param string $teacherId    教师号
     * return array
     */
    public function getClassTeacherInfo($classId,$teacherId)
    {
        $where = array('classId' => $classId,'teacherId' => $teacherId);
        $arr   = array('classId');
        $table = $this->obj->getTable('teacherClassTab');
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    
    public function modifyClass()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$classId   = $_LS['classId'];
        if ($classId && $accNumber) {                                   //必须存在编辑员账号和班级号 
            if ($this->obj->verifyUser($accNumber)) {                   //账号已登录且有效
                if (count($this->getClassInfo_byId($classId)) > 0) {    //该班级号是确实存在的
                    @$name = $_LS['name'];
                    if ($name) {
                        $arr = array_diff($_LS,array('accNumber' => $accNumber,'classId' => $classId,'name' => $_LS['name']));
                    } else {
                        $arr = array_diff($_LS,array('accNumber' => $accNumber,'classId' => $classId));
                    }
                    if (count($arr) > 0) {                              //存在需要修改的信息
                        $count = parent::verifyCount($arr,array('className','courseId','masterId','startClassTime','endClassTime','classType','addressId',));
                        if ($count == 0) {                              //要修改的信息是安全的
                            //@$teacherId = $_LS['teacherId'];
                            @$masterId  = $_LS['masterId'];
                            //如果存在班级任号，确保班主任号有效
                            if (isset($masterId) && count($this->getTeacherName_byId($masterId,3)) == 0) {
                                $data['status'] = 8;
                                $data['msg']    = '该班主任信息存在问题';
                            } else {
                                $table = array($this->obj->getTable('classTab'),$this->obj->getTable('teacherClassTab'));
                                $where = array('classId' => $classId);
                                $resp  = parent::update($table,$arr,$where);
                                $data  = parent::formatResponse($resp);
                            }
                            //如果存在教师号，要确保教师号有效，且不在该班级
                            /* if (isset($teacherId) && count($this->getClassTeacherInfo($classId,$teacherId)) > 0 && count($this->getTeacherName_byId($teacherId,2)) > 0) {
                                $data['status'] = 7;
                                $data['msg']    = '该教师不能被添加';
                            } else {
                                
                            } */
                        } else {
                            $data['status'] = 5;
                        }
                    } else {
                        $data['status'] = 4;
                    }
                } else {
                    $data['status'] = 14;
                    //$data['msg']    = '不存在该班级信息';
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
     * 根据班级号获得班级信息，班级名
     * @param int $classId  班级号 
     * @return array
     */
    public function getClassInfo_byId($classId)
    {
        $arr   = array('className');
        $where = array('classId' => $classId);
        $table = $this->obj->getTable('classTab');
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    
    public function addProject()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if ($accNumber) {
            if ($this->obj->verifyUser($accNumber)) {
                $arr = array_diff($_LS,array('accNumber' => $accNumber));
                if (count($arr) > 0) {
                    $count = parent::verifyCount(array('projectName','description','startTime','endTime'),$arr,false);
                    if ($count == 0) {                                                          //必须要有上述字段
                        $count_2 = parent::verifyCount($arr,$this->obj->getArr('projectInfo')); //操作信息必须要安全
                        if ($count_2 == 0) {
                            $arr['type'] = 1;                                                   //教学项目
                            $table       = $this->obj->getTable('projectTab');
                            $res         = parent::insert($table,$arr);
                            $data        = parent::formatResponse($res);
                        } else {
                            $data['status'] = 5;
                        }
                    } else {
                        $data['status'] = 9;
                        //$data['msg']    = '信息不集全，不能添加';
                    }
                } else {
                    $data['status'] = 4;
                }
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    
    public function addProjectTeacher()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$projectId = $_LS['projectId'];
        if ($accNumber && $projectId) {
            if ($this->obj->verifyUser($accNumber)) {
                $arr   = array_diff($_LS,array('accNumber' => $accNumber));
                $count = parent::verifyCount($arr,array('teacherId','courseId','projectId'));
                if (count($arr) == 3 && $count == 0) {
                    //验证之前的没有改教师
                    $resp  = $this->getProTeacherInfo_byMore($projectId,$arr['teacherId'],$arr['courseId']);
                    if (count($resp) == 0 ) {
                        $table = $this->obj->getTable('courseProTab');
                        $res   = parent::insert($table,$arr);
                        $data  = parent::formatResponse($res);
                    } else {
                        $data['status'] = 17;
                        //$data['msg']    = '一个教师不能重复被添加';
                    }
                } else {
                    $data['status'] = 5;
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
     * 根据项目号获得教师信息
     * @param unknown $projectId
     */
    public function getProTeacherInfo_byMore($projectId,$teacherId,$courseId)
    {
        $where = array('projectId' => $projectId,'teacherId' => $teacherId,'courseId' => $courseId);
        $arr   = array('id');
        $table = $this->obj->getTable('courseProTab');
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    /**
     * 由图片信息id上传相应的图片
     */
    public function uploadPhoto()
    {
        @$id   = intval($_GET['id']);
        if (count($this->user) > 0 && isset($this->user['accNumber']) && $this->obj->verifyUser($this->user['accNumber'])) {
            if ($id) {
                $where = array('id' => $id);
                $table = 'carousel_figure';
                $obj   = new doActionModel();
                $data  = $obj->uploadPic($table,$where,self::DESTINATION_CAROUSEL,null,null,false);
            } else {
                $data['status'] = 2;
            }
        } else {
            $data['status'] = 3;
        }
        
        return $data;
    }
    
    /**
     * 添加图片信息
     */
    public function addPhotoFigure()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if (isset($accNumber) && $this->obj->verifyUser($accNumber)) {
            $arr = array_diff($_LS,array('accNumber' => $accNumber));
            if (count($arr) > 0) {
                $count = parent::verifyCount($arr,array('picName','url','picUrl','description','courseId','status','top','pic_type','addTime'));
                if ($count == 0) {
                    $table = 'carousel_figure';
                    $arr['addTime']      = time();
                    $res['info']['id']   = parent::insert($table,$arr);
                    $data  = parent::formatResponse($res['info']);
                } else {
                    $data['status'] = 5;
                }
            } else {
                $data['status'] = 4;
            }
        } else {
            $data['status'] = 3;
        }
        return $data;
    }
    
    /**
     * 修改图片显示状态
     */
    public function modifyPhotoStatus()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$id        = $_LS['id'];
        @$status    = $_LS['status'];
        
        if ($accNumber && $id && $status) {                                     //post参数集权
            if ($this->obj->verifyUser($accNumber)) {                           //帐号登录且信息有效
                $table = $this->obj->getTable('carouselFigureTab');             //数据表
                $res = $this->checkPhotoInfo_byId($id,$table,array('status'));  //根据传入的id获得相关信息
                if (count($res) > 0) {                                          //存在信息
                    if ($res['status'] != $status) {                            //要修改的状态与已有的状态不一致
                        $where = array('id' => $id);                            //修改条件
                        $arr   = array('status' => $status);                    //修改内容
                        $resp  = parent::update($table,$arr,$where);            //修改数据
                        $data  = parent::formatResponse($resp);                 //格式化结果集
                    } else {
                        $data['status'] = 5;
                        $data['msg']    = '已是该状态，不用修改';
                    }
                } else {
                    $data['status'] = 14;
                    //$data['msg']    = '该id错误，不存在该信息';
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
     * 根据id获得相关信息
     * @param int $id           
     * @param array $append   追加数组，用于查询
     */
    public function checkPhotoInfo_byId($id,$table,$append = array())
    {
        $arr   = array('id');
        if (!empty($append) && is_array($append)) {         //如果存在追加数组
            $arr = array_merge($arr,$append);
        }
        $where = array('id' => $id);                        //查询条件
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    /**
     * 录入课程学费信息
     * @return multitype:array
     */
    public function addTuition()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$courseId  = $_LS['courseId'];
        if ($accNumber && $courseId) {                                                              //存在身份标识符
            if ($this->obj->verifyUser($accNumber)) {                                               //身份标识符有效
                $res   = $this->verifyCourseId($courseId);                                          //验证课程号是否有效
                if (count($res) > 0) {                                                              //课程号有效
                    $arr = array_diff($_LS,array('accNumber' => $accNumber));                       //获得录入信息
                    if (count($arr) == 5) {                                                 
                        $count = parent::verifyCount($arr,$this->obj->getArr('tuitionInfo'));       //验证录入信息是否安全
                        if ($count == 0) {
                            $res_2 = $this->veiryTuitionNum($courseId);                             //获得该课程已有的记录数目
                            if (count($res_2) < 8) {                                                //每个课程不能超过8条
                                $res_3 = $this->verifyTuition($arr);
                                if (count($res_3) == 0) {
                                    $table = $this->obj->getTable('tuitionTab');                    //数据表
                                    $res_3 = parent::insert($table,$arr);                           //插入数据
                                    $data  = parent::formatResponse($res_3);                        //格式化结果集
                                } else {
                                    $data['status'] = 16;
                                }
                            } else {
                                $data['status'] = 6;
                            }
                        } else {
                            $data['status'] = 5;
                        }
                    } else {
                        $data['status'] = 4;
                    }
                } else {
                    $data['status'] = 14;
                    //$data['msg']    = '课程号不存在';
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
     * 验证课程号是否存在
     * @param int $courseId
     * @return array
     */
    public function verifyCourseId($courseId)
    {
        $table = $this->obj->getTable('courseTab');
        $arr   = array('courseName');
        $where = array('courseId' => $courseId);
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    /**
     * 获得所有该课程号的学费
     * @param int $courseId
     * @return array
     */
    public function veiryTuitionNum($courseId)
    {
        $table = $this->obj->getTable('tuitionTab');
        $arr   = array('id','tuitionMoney');
        $where = array('courseId' => $courseId);
        return parent::fetchAll_byArr($table,$arr,$where);
    }
    
    /**
     * 通过搜索信息字段获得相关信息
     * @param array $arr
     */
    public function verifyTuition($arr)
    {
        $where = array_diff($arr,array('tuitonMoney' => $arr['tuitionMoney']));
        $arr_2 = array('id','tuitionMoney');
        $table = $this->obj->getTable('tuitionTab');
        return parent::fetchOne_byArr($table,$arr_2,$where);
    }
    
    /**
     * 通过学费id修改学费相关信息
     * @return array
     */
    public function modifyTuitonById()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$id        = intval($_LS['id']);
        if ($accNumber && $id) {                                                                //存在身份和唯一标识符
            if ($this->obj->verifyUser($accNumber)) {                                           //符合登陆信息
                $res = $this->verifyTuitionId($id);                                             //唯一标识符正确？
                if (count($res) > 0) {
                    $arr = array_diff($_LS,array('accNumber' => $accNumber,'id' => $id));       //获得要修改的信息
                    if (count($arr) > 0) {                                                      
                        $count = parent::verifyCount($arr,array('caseId','tuitionCase','teaching','tuitionMoney')); //验证操作信息是否安全
                        if ($count == 0) {                                                      //操作信息安全
                            $where   = array('id' => $id);
                            $table   = $this->obj->getTable('tuitionTab');                      //数据表
                            $where_2 = array_merge($arr,$where);
                            $res_3   = $this->verifyTuition($where_2);                          //若修改的信息没有变动，则不用修改
                            if (count($res_3) == 0) {                                           //有信息变动
                                $res_2 = parent::update($table,$arr,$where);                    //修改信息
                                $data  = parent::formatResponse($res_2);                        //格式化结果集
                            } else {
                                $data['status'] = 16;
                            }
                        } else {
                            $data['status'] = 5;
                        }
                    } else {
                        $data['status'] = 4;
                    }
                } else {
                    $data['status'] = 14;
                    //$data['msg']    = '课程号不存在';
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
     * 验证学费id是否正确，若正确，就获得其相关学费
     * @param int $id
     * @return array
     */
    public function verifyTuitionId($id)
    {
        $table = $this->obj->getTable('tuitionTab');
        $arr   = array('id','tuitionMoney');
        $where = array('id' => $id);
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    /**
     * 添加视频信息
     * @return multitype:array
     */
    public function addVedioInfo()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        if ($accNumber) {                                                                   //存在身份标识符
           if ($this->obj->verifyUser($accNumber)) {                                        //身份标识符有效
               $arr = array_diff($_LS,array('accNumber' => $accNumber));                    //获得需要添加的视频信息
               @$arr = array_filter($arr);                                                  //去掉所有等价为false的属性
               if (count($arr) > 0) {                                                       //如果存在操作信息
                   $count = parent::verifyCount($arr,$this->obj->getArr('vedioInfo'));      //操作信息是安全的
                   if ($count == 0) {
                       $table = $this->obj->getTable('vedioTab');                           //数据表
                       $res   = parent::fetchOne_byArr($table,array('id','dateinto'),$arr); 
                       if (count($res) == 0) {                                              //该条是唯一的
                           $arr['dateinto'] = time();                                       //插入信息录入时间
                           $res_2 = parent::insert($table,$arr);                            //录入视频信息
                           $data  = parent::formatResponse($res_2);                         //格式化结果集
                       } else {
                           $data['status'] = 17;
                       }
                   } else {
                       $data['status'] = 5;
                   }
               } else {
                   $data['status'] = 4;
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
     * 添加视频图片信息
     * @return array
     */
    public function addVedioPicUrl()
    {
        @$id   = intval($_GET['id']);
        //账号是登陆状态
        if (count($this->user) > 0 && isset($this->user['accNumber']) && $this->obj->verifyUser($this->user['accNumber'])) {
            if ($id) {
                $where = array('id' => $id);
                $table = $this->obj->getTable('vedioTab');                                      //视频表
                $res   = parent::fetchOne_byArr($table,array('id','dateinto'),$where);          //id存在且有效
                if (count($res) > 0) {
                    $obj   = new doActionModel();                                               //生成一个150x150的缩略图
                    $data  = $obj->uploadPic($table,$where,self::DESTINATION_VEDIO,null,null,true,150,150,false);
                } else {
                    $data['status'] = 14;
                }
            } else {
                $data['status'] = 2;
            }
        } else {
            $data['status'] = 3;
        }
        return $data;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}