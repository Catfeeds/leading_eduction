<?php
namespace App\admin\Model;

class setCompanyModel extends infoModel
{
    const JOBNUM            = 15;                                               //企业可以发布职位招聘次数
    const HEAD_DESTINATION  = './static/admin/images/uploads/image_149/company/';
    const LIC_DESTINATION   = './static/admin/images/uploads/image_149/company/license';
    
    private $user = array();
    private $obj;
    public function __construct()
    {
        $obj        = new getCompanyModel();
        $this->user = $obj->getUser();
        $this->obj  = $obj;
    }
    
    /**
     * 验证是否与登录信息相符
     * @param string $accNumber 企业号
     * return boolean
     */
    public function verifyUser($accNumber)
    {
        return $this->obj->verifyUser($accNumber);
    }
    
    /**
     * 获得相关信息数组
     * @param string $arr 数组名
     */
    public function getArr($arr)
    {
        return $this->obj->getArr($arr);
    }
    //获得数据表
    public function getTable($table)
    {
        return $this->obj->getTable($table);
    }
    
    /**
     * 添加一条招聘信息
     * @return array
     */
    public function addARecruitedInfo()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        if (isset($accNumber)) {
            if ($this->verifyUser($accNumber)) {
                $arr = array_diff($_LS,array("accNumber" => $accNumber));                               //组成插入数据数组
                if (count($arr) > 0) {                                                                  //是否有插入的数据
                    $arr['compId'] = $accNumber;
                    $arr['status'] = 0;
                    if (parent::verifyCount($arr,$this->getArr('jobArr')) == 0) {                       //验证信息是否安全
                        $table           = $this->getTable('jobTab');
                        $where['compId'] = $accNumber;
                        $count           = parent::getNum($table,array('compId'),$where);               //查看已有简历记录条数
                        if ($count < self::JOBNUM) {
                            $data = parent::insert($table,$arr);                                        //插入一条记录
                            $data = parent::formatResponse($data);                                      //格式化结果集
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
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 修改招聘信息
     * @return multitype:number string
     */
    public function setResumeInfo()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        @$jobId     = $_LS['jobId'];
        if ($accNumber && $jobId) {
            if ($this->verifyUser($accNumber)) {                                                //符合登陆信息    
                $arr = array_diff($_LS,array("accNumber"=>$accNumber,"jobId"=>$jobId));         //组成更改信息数组
                if (count($arr) > 0) {
                    if (parent::verifyCount($arr,$this->getArr('jobArr')) == 0) {               //验证信息是否安全
                        $where   = array("compId"=>$accNumber,"jobId"=>$jobId);
                        $table   = $this->getTable('jobTab');
                        /*查看是否重复修改**/
                        $where_2 = array_merge($arr,array('jobId' => $jobId));      
                        $res_2   = parent::fetchOne_byArr($table,array('jobId'),$where_2);
                        if (count($res_2) == 0) {
                            /*更新数据**/
                            $data    = parent::update($table,$arr,$where);                          //更新数据
                            $data    = parent::formatResponse($data);                               //格式化结果集
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
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 修改企业登录密码
     * @return array:
     */
    public function setCompPass()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        if (isset($accNumber)) {                                //存在账号信息
            if ($this->verifyUser($accNumber)) {                //符合登陆信息
                $obj   = new doActionModel();
                $where = array("compId"=>$accNumber);           //修改条件
                $data  = $obj->setPass($_LS,$this->getTable('companyTab'),$this->user,$where);  //修改密码
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 修改公司基本信息
     * @return multitype:number
     */
    public function setCompBase()
    {
        global $_LS;
        $data       = array();
        @$accNumber = $_LS['accNumber'];
        if (isset($accNumber)) {
            if ($this->verifyUser($accNumber)) {
                $table = array($this->getTable('companyTab'),$this->getTable('companyInfoTab'));
                $obj   = new doActionModel();
                $data  = $obj->setObjectBase($_LS,$this->getArr('baseArr'),$table,array("compId"=>$accNumber));     //修改基本信息
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 修改简历状态
     * @return array
     */
    public function setResumeStatus()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$l_id      = $_LS['l_id'];
        @$r_status  = $_LS['r_status'];
        if ($accNumber && $l_id && $r_status) {
            if ($this->verifyUser($accNumber)) {                                    //符合登陆信息
                $res = $this->verifyCompAndLId($accNumber,$l_id);                   //获得已有的状态
                if (count($res) > 0) {                                              //信息安全
                    if ($res['r_status'] != $r_status) {
                        $data = $this->modifyRStatus($l_id,$r_status);              //修改状态
                        $data = parent::formatResponse($data);
                    } else {
                        $data['status'] = 16;
                        //$data['msg']    = '当前状态不用修改';
                    }
                } else {
                    $data['status'] = 5;
                }
            } else {
                $data['status'] = 3;
            }
        } else {
            $status['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 验证该简历记录是否属于该公司
     * @param string $accNumber 企业号
     * @param int $l_id         投递记录表id
    **/
    public function verifyCompAndLId($accNumber,$l_id)
    {
        $table = array($this->getTable('jobTab'),$this->getTable('resumeLogTab'));
        $arr   = array('r_status','compId');
        $where = array('compId' => $accNumber,'l_id' => $l_id,'where2' => ' AND s.`jobId` = f.`jobId` ' );
        return parent::fetchOne_byArrJoin($table,$arr,$where);
    }
    /**
     * 修改简历查看状态
     * @param number $l_id
     * @param number $r_status
     * @return number
    **/
    public function modifyRStatus($l_id,$r_status)
    {
        $arr   = array('r_status' => $r_status);
        $where = array('l_id' => $l_id);
        return parent::update($this->getTable('resumeLogTab'),$arr,$where);
    }
    
    //上传头像
    public function uploadImg()
    {
        $data = array();
        @$compId  = $this->user['compId'];
        if (!empty($compId)) {
            $table = $this->getTable('companyInfoTab');
            $where = array('compId' => $compId);
            $obj   = new doActionModel();
            $data  = $obj->uploadPic($table,$where,self::HEAD_DESTINATION);
        } else {
            $data['status'] = 3;
        }
        return $data;
    }
    //上传营业执照
    public function uploadLicenseUrl()
    {
        $data     = array();
        @$compId  = $this->user['compId'];
        if (!empty($compId)) {
            $table = $this->getTable('companyInfoTab');
            $where = array('compId' => $compId);
            $obj   = new doActionModel();
            $arr   = array('');
            $data  = $obj->uploadPic($table,$where,self::LIC_DESTINATION,'licenseUrl',null,false);
        } else {
            $data['status'] = 3;
        }
        return $data;
    }
    
}