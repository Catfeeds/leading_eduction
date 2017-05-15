<?php
namespace App\admin\Model;
use framework\libs\core\DB;
class getStuInfoModel
{
    /**
    * 根据不同的参数获得不同的值
    * @date: 2017年5月12日 下午6:16:37
    * @author: lenovo2013
    * @param $accNumber 可能是学号或手机号
    * @param: $param string 可能是 ''、center、base、course、project、concern、concerned、recommend
    * @return:array
    */
    public function getStuInfo($accNumber,$param)
    {
        $objStu = M('leading_student');
        //$arr = array('mobile','sex','email','bloodType','description','homeAddress');
        //$where = " s.stuId = f.stuId and s.stuId = '".$accNumber."'";
        if(isMobile($accNumber)){//是手机号
           /*  $where['mobile'] = $accNumber;
            $arr = array('stuId');
            $data = $objStu->getInfo_byArr($arr,$where); */
            $data = $this->getStuId_byMobile($accNumber);
            if(count($data) > 0){
                $where['stuId'] = $data['stuId'];
            }
        }else{//学号
            $where['stuId'] = $accNumber;
        }
        switch($param){
            case 'base'://基础数据
                $arr = array('sex','age','bloodType','provinceId','homeAddress','description');
                $data = $objStu->getInfo_byArr($arr,$where,$table='leading_student_info');
                if(isset($data['provinceId']) && !empty($data['provinceId'])){
                    $res = verifyModel::province($data['provinceId']);
                    if(count($res) > 0){//如果存在信息
                        $data['province'] = $res['province'];
                    }
                }
                break;
            case 'course'://课程信息
                break;
            case 'project'://作品或项目信息
                break;
            case 'concern'://关注信息
                $obj = M('concern');
                $data = $obj->getCon($where['stuId']);
                break;
            case 'concerned'://被关注信息
                $obj = M('concern');
                $data = $obj->getConed($where['stuId']);
                break;
            case 'recommend': //推荐信息
                break;
            case '':
            case 'center': //核心数据
            default:
                $arr = array('mobile','name','picUrl');
                $where = " s.stuId = f.stuId and s.stuId = '{$where['stuId']}' ";
                $data = $objStu->getInfo_byArrJoin($arr,$where);
                break;
        }
        return $data;
    }
    
    public function getStuId_byMobile($obj,$mobile)
    {
        $where['mobile'] = $mobile;
        $arr = array('stuId');
        $data = $obj->getInfo_byArr($arr,$where);
        return $data;
    }
    
    
    
    
}