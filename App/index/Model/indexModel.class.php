<?php
namespace  App\index\Model;
use App\admin\Model\infoModel;

class indexModel extends infoModel
{
    
    const SIGNEXPTIME = 604800;                 //单位秒，7天
    const COURSEALL   = 56800;
    
    
    public function registerCourse()
    {
        global $_LS;
        $data = array();
        
        @$name       = $_LS['name'];
        @$mobile     = $_LS['mobile'];
        @$courseId   = $_LS['courseId'];
        @$listenTime = $_LS['listenTime'];
        
        if ($name && $mobile && $courseId && $listenTime) {
            
            //1.课程号是否真实存在
            $res = $this->verifyCourse($courseId);
            if (count($res) > 0) {
                
                if (isMobile($mobile)) {
                    //2.该手机号是否在1周内报过名
                    $resp = $this->verifyMobileSign($mobile);
                    if (count($resp) == 0) {
                    
                        //3.插入报名数据表
                        $count = parent::verifyCount($_LS,array('name','mobile','courseId','listenTime','qq'));
                        if ($count == 0) {
                            $table = 'leading_sign';
                            $arr   = $_LS;
                            $arr['signTime'] = time();
                            $res   = parent::insert($table,$arr);
                            $data  = parent::formatResponse($res);
                        } else {
                            $data['status'] = 5;
                        }
                    } else {
                        $data['status'] = 6;
                        $data['msg']    = '1周内不能重复报名';
                    }
                } else {
                    $data['status'] = 7;
                    $data['msg'] = '手机号不符合格式';
                }
            } else {
                $data['status'] = 3;
                $data['msg']    = '课程号不存在';
            }
            
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
    /**
     * 根据课程号获得课程名
     * @param int $courseId
     * @return array
     */
    public function verifyCourse($courseId)
    {
        $table = 'course';
        $arr   = array('courseName');
        $where = array('courseId' => $courseId);
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    /**
     * 验证手机号/qq/wechat一周内是否注册，可以完善
     * @param unknown $mobile
     */
    public function verifyMobileSign($mobile)
    {
        $table   = 'leading_sign';
        $arr     = array('id','name');
        $expTime = time() - self::SIGNEXPTIME; 
        $where   = array('mobile' => $mobile,'sign_type' => 1,'where2' => " AND signTime > {$expTime}");
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    
    public function getCourse()
    {
        $arr = array('courseId','courseName','status');
        $where = array();
        $table = 'course';
        $data['info'] = parent::fetchAll_byArr($table,$arr,$where);
        return parent::formatResponse($data['info']);
    }
    
    /**
     * 获得轮播图|推荐图相关信息
     */
    public function getCarousel()
    {
        global $_LS;
        @$type = $_LS['type'];
        $type  = empty($type)?1:$type;
        $where = array('pic_type' => $type,' ORDER BY id DESC LIMIT 8 ');
        $arr   = array('id','picUrl','top','url');
        $table = 'carousel_figure';
        $res['info'] = parent::fetchAll_byArr($table,$arr,$where);
        return parent::formatResponse($res['info']);
    }
    
    public function getWorkInfo()
    {
        global $_LS;
        @$courseId = $_LS['courseId'];
        @$page     = intval($_LS['page']);
        $page      = empty($page)?10:$page;
        if ($courseId && $page) {
            $arr = array('');
        } else {
            $data['status'] = 2;
        }
    }
    
    public function getClassInfo()
    {
        global $_LS;
        @$courseId  = intval($_LS['courseId']);
        @$addressId = intval($_LS['addressId']);
        $addressId  = empty($addressId)?1:$addressId;
        if ($courseId) {
            if ($courseId == self::COURSEALL) {
                $courses = $this->getCourse();
                $courses = $courses['info'];
                if (count($courses) > 0 ) {
                    foreach ($courses as $val) {
                        $courseId = $val['courseId'];
                        $data['info']["{$courseId}"] = $this->getClassInfos($courseId);
                    }
                } else {
                    $data['status'] = 3;
                    $data['msg']    = '没有课程信息';
                }
            } else {           
                $data['info']["{$courseId}"] = $this->getClassInfos($courseId,$addressId);
            }
        } else {
            $data['status'] = 2;
        }
        if ($data['info'] && !empty($data['info'])) {
            $data = parent::formatResponse($data['info']);
        }
        return $data;
    }
    
    public function getClassInfos($courseId,$addressId = 1)
    {
        $where = array('courseId' => $courseId);
        $arr   = array('courseName');
        $table = 'course';
        $res   = parent::fetchOne_byArr($table,$arr,$where);
        $res_2 = $this->getAddressId($courseId,true);
        if (count($res_2)) {
            $res['addressId'] = $res_2;
        }
        $res_3 = $this->getClass($courseId,$addressId);
        if (count($res_3) > 0) {
            $res['class'] = $res_3;
        }
        return $res;
    }
    
    public function getAddressId($courseId)
    {
        $where = array('courseId' => $courseId);
        $arr   = array('addressId');
        $table = 'leading_class';
        return parent::fetchAll_byArr($table,$arr,$where);
    }
    
    public function getClass($courseId,$addressId = 1)
    {
        $where = array('courseId' => $courseId,'addressId' => $addressId,'where2' => ' ORDER BY startClassTime DESC LIMIT 4');
        $arr   = array('classType','startClassTime','classId');
        $table = 'leading_class';
        return parent::fetchAll_byArr($table,$arr,$where);
    }
    
    /**
     * 根据课程和地址id获得开班信息
     * @return multitype:array
     */
    public function getClassByAddress()
    {
        global $_LS;
        $data = array();
        @$courseId  = intval($_LS['courseId']);
        @$addressId = intval($_LS['addressId']);
        if ($courseId && $addressId) {
            $data['info'] = $this->getClass($courseId,$addressId);
            $data         = parent::formatResponse($data['info']);
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
}