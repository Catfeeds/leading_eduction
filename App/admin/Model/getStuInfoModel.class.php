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
        $objStu = M('student');
        if(isMobile($accNumber)){//是手机号
            $where['mobile'] = $accNumber;
        }else{//学号
            $where['stuId'] = $accNumber;
        }
        switch($param){
            case 'base'://基础数据
                $arr = array('sex','age','bloodType','provinceId','homeAddress','description');
                $data = $objStu->getInfo_byArr($arr,$where);
                if(isset($data['provinceId']) && !empty($data['provinceId'])){
                    $data['province'] = verifyModel::privince($data['provinceId']);
                }
                break;
            case 'course'://课程信息
                break;
            case 'project'://作品或项目信息
                break;
            case 'concern'://关注信息
                break;
            case 'concerned'://被关注信息
                break;
            case 'recommend': // 推荐信息
                break;
            case '':
            case 'center': // 核心数据
            default:
                $arr = array('stuId','mobile','name','picUrl');
                $data = $objStu->getInfo_byArr($arr,$where);
                break;
        }
        return $data;
    }
    
    
    
    
    
    
}