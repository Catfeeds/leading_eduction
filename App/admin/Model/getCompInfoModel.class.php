<?php
namespace App\admin\Model;
//use App\admin\Model\getStuInfoModel;
class getCompInfoModel
{
    /**
    * 获得公司模块信息，根据param值来区分
    * @date: 2017年5月17日 下午1:31:27
    * @author: lenovo2013
    * @param: string $accNumber身份标识符，手机号，或则是公司号
    * @param sting param 模块标识符
    * @return:array
    */
    public function getcompInfo($accNumber,$param)
    {
        if(isMobile($accNumber)){//手机号
            $res = $this->getcompId_byMobile($accNumber);//通过手机号获得该公司的公司编号
            if(count($res) > 0){
                $accNumber = $res['compId'];
                $where['compId'] = $accNumber;
            }
        }else{
            $where['compId'] = $accNumber;
        }
        switch($param)
        {
            case 'base'://基本信息
                $data = $this->getCompBase($accNumber,$where);
                break;
            case 'recruit'://招聘信息
                $data = $this->getCompRecruit($accNumber,$where);
                break;
            case 'care'://公司关注的学员信息
                $data = $this->getCompCare($accNumber,$where);
                break;
            case 'center'://默认是核心信息
            default:
                $data = $this->getCompCenter($accNumber,$where);
                break;
        }
        return $data;
    }
    /**
     * 实例化不同的模型，调用该模型的getInfo_byArr方法,只获得一条数据
     * return array
     */
    public function getInfo_byArr($table,$arr,$where,$where2 = '')
    {
        $obj = M("{$table}");
        return $obj->getInfo_byArr($arr,$where,$where2);
    }
    /**
     * 实例化不同的模型，调用该模型的getInfoAll_byArr方法,获得多条数据
     * retrun 多维数组
     */
    public function getInfoAll_byArr($table,$arr,$where,$where2 = '')
    {
        $obj = M("{$table}");
        return $obj->getInfoAll_byArr($arr,$where,$where2);
    }
    /**
     * 通过手机号获得该公司的公司编号
     * return array
     */
    public function getcompId_byMobile($mobile)
    {
        $arr = array('compId');
        $where['mobile'] = $mobile;
        return $this->getInfo_byArr('leading_company',$arr,$where);
    }
    /**
     * 根据公司号获得公司基本信息
     * return array
     */
    public function getCompBase($accNumber,$where)
    {
        $res  = $this->getCompCenter($accNumber,$where);
        $resp = $this->getCompBaseInfo($accNumber,$where);
        return array_merge($res,$resp);
    }
    /**
     * 获得公司的核心信息
     * return array
     */
    public function getCompCenter($accNumber,$where)
    {
        $arr = array('id','compName','mobile','email');
        return $this->getInfo_byArr('leading_company',$arr,$where);
    }
    /**
     * 获得公司的基本信息
     * return array
     */
    public function getCompBaseInfo($accNumber,$where)
    {
        $arr = array('id','licenseUrl','description','address','legalPerson','tel');
        return $this->getInfo_byArr('leading_company_info',$arr,$where);
    }
    /**
     * 获得公司的招聘信息
     * return array
     */
    public function getCompRecruit($accNumber,$where)
    {
        $arr = array('jobName','eduBacId','people','duty','demand','treatment');
        return $this->getInfoAll_byArr('leading_job',$arr,$where);
    }
    /**
     * 获得公司关注的所有的学生名单
     */
    public function getCompCare($accNumber)
    {
        $obj = new getStuInfoModel();
        $res = $obj->getStuConcernAll($accNumber);
        $count = count($res);
        for($i=0;$i<$count;$i++){
            $res[$i] = array_merge($res[$i],$obj->getStuCenter($res[$i]['concerned']));
        }
        return $res;
    }
}