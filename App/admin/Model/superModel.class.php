<?php
namespace App\admin\Model;

class superModel extends infoModel
{
    const USEREXPTIME = 21600;
    const HEAD_DESTINATION  = './static/admin/images/uploads/image_149/admin/';
    private $user = array();
    
    private $staff = 'leading_staff_info';
    
    public function __construct()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
             $this->user = $_SESSION['user'];
         }
    }
    
    public function verifyUser($accNumber)
    {
        $res = false;
        if(isset($this->user) && isset($this->user['accNumber']) && $this->user['accNumber'] == $accNumber){
            if(($this->user['user_expTime'] + self::USEREXPTIME) > time()){
                $res = true;
            }
        }
        return $res;
    }
    
    public function modifyCaseId()
    {
        global $_LS;
        $data = array();
        @$accNumber = $_LS['accNumber'];
        @$staffId   = $_LS['staffId'];
        @$rangeId   = intval($_LS['rangeId']);
        @$caseId    = intval($_LS['caseId']);
        if ($accNumber && $staffId && $rangeId) {
            if ($this->verifyUser($accNumber)) {
               if ($accNumber != $staffId) {
                   $res   = $this->getStaffOneInfo_byArr($staffId,array('rangeId'));            //获得已有的权限信息
                   if (intval($res['rangeId']) != $rangeId) {                                   //与已有的权限不想等时才能修改
                       $resp = $this->update_byArr($staffId,array('rangeId' => $rangeId,'caseId' => $caseId));      //修改权限信息
                       $data = parent::formatResponse($resp);                                   //格式化结果集
                   } else { 
                       $data['status'] = 16;
                       //$data['msg']    = '该账号已是该权限，不用再修改';
                   }
               } else {
                   $data['status'] = 71;
                   //$data['msg']    = '不能更改自己的权限';
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
     * 通过关键字及账号id获得相关信息
     * @param string $accNumber
     * @param array $arr
    **/
    public function getStaffOneInfo_byArr($accNumber,$arr)
    {
        $where = array('accNumber' => $accNumber);
        $table = $this->staff;
        return parent::fetchOne_byArr($table,$arr,$where);
    }
    
    /**
     * 通过账号id及关键字修改相关信息
     * @param string $accNumber
     * @param array $arr
    **/
    public function update_byArr($accNumber,$arr)
    {
        $where = array('accNumber' => $accNumber);
        $table = $this->staff;
        return parent::update($table,$arr,$where);
    }
    
    public function addAddress()
    {
        global $_LS;
        $data = array();
        @$accNumber  = $_LS['accNumber'];
        @$addresssId = intval($_LS['addressId']);
        @$addresss   = $_LS['address'];
        if ($accNumber && $addresssId && $addresss) {
            if ($this->verifyUser($accNumber)) {
                 $table = 'leading_address';
                 $where = array('addressId' => $addresssId);
                 $res   = parent::fetchOne_byArr($table,array('address'),$where);
                 if (count($res) == 0) {
                     $resp  = parent::fetchOne_byArr($table,array('addressId'),array('address' => $addresss));
                     if (count($resp) == 0) {
                         $arr   = array('addressId' => $addresssId,'address' => $addresss);
                         $res_2 = parent::insert($table,$arr,$where);
                         $data  = parent::formatResponse($res_2);
                     } else {
                         $data['status'] = 5;
                         $data['msg']    = '已存在该地址';
                     }
                 } else {
                    $data['status'] = 4;
                    $data['msg']    = '地址id已存在';
                 }
            } else {
                $data['status'] = 3;
            }
        } else {
            $data['status'] = 2;
        }
        return $data;
    }
    
}