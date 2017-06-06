<?php
namespace App\admin\Model;

class insertInfoModel extends infoModel
{
    public function insertCourseContent()
    {
        $obj = new doActionModel();
        $j = 0;
        $data = $this->getInsertContent();
        $count = count($data);
        for($i=0;$i<$count;$i++){
            $data[$i]['stuId'] =  $obj->productTeacherId();
            try {
                $res[$i] = parent::insert('leading_teacher',$data[$i]);
                $j++;
            } catch (Exception $e) {
                $res[$i] = parent::insert('leading_teacher',$data[++$i]);
                $j++;
            }
        }
        $resp['info'] = $res;
        return $resp;
    }
    public function insertStuContent()
    {
        $obj = new doActionModel();
        $j = 0;
        $data = $this->getInsertContent();
        $count = count($data);
        $arr = array('id','addressId');
        for($i=0;$i<$count;$i++){
            if($data[$i]['classId']){
                $where['id'] = $data[$i]['classId'];
                $res = parent::getInfo_byArr('leading_class',$arr,$where);
                if(count($res) > 0){
                    $data[$i]['stuId'] =  $obj->productStuId($data[$i]['classId'],$res['addressId']);
                    try {
                        $res[$i] = parent::insert('leading_student',$data[$i]);
                        $j++;
                    } catch (Exception $e) {
                        $res[$i] = parent::insert('leading_student',$data[++$i]);
                        $j++;
                    }
                }else{
                    $response[$i] = '班级信息错误'; 
                }
            }else{
                $response[$i] = '没有班级信息，不能插入';
            }
        }
        $resp['info'] = $res;
        return $resp;
    }
    //获得插入的数据
    public function getInsertContent()
    {
        if($_POST){
            $data = $_POST;
        }else{
            $data = array(
                0=>array('name'=>'胡说啥','mobile'=>'15111911089','email'=>'15111911089@163.com','password'=>'student','status'=>1,'caseId'=>1,'dateinto'=>time(),'sex'=>1,'classId'=>1),
                1=>array('name'=>'胡啥三','mobile'=>'15111911189','email'=>'15111911189@163.com','password'=>'student','status'=>1,'caseId'=>1,'dateinto'=>time(),'sex'=>1,'classId'=>1),
                2=>array('name'=>'胡的啥','mobile'=>'15111911289','email'=>'15111911289@163.com','password'=>'student','status'=>1,'caseId'=>1,'dateinto'=>time(),'sex'=>1,'classId'=>1),
                3=>array('name'=>'胡啊啥','mobile'=>'15111911389','email'=>'15111911389@163.com','password'=>'student','status'=>1,'caseId'=>1,'dateinto'=>time(),'sex'=>1,'classId'=>1),
                4=>array('name'=>'胡规啥','mobile'=>'15111911489','email'=>'15111911489@163.com','password'=>'student','status'=>1,'caseId'=>1,'dateinto'=>time(),'sex'=>1,'classId'=>1),
                5=>array('name'=>'胡发啥','mobile'=>'15111911589','email'=>'15111911589@163.com','password'=>'student','status'=>1,'caseId'=>1,'dateinto'=>time(),'sex'=>2,'classId'=>2),
                6=>array('name'=>'胡噶啥','mobile'=>'15111911689','email'=>'15111911689@163.com','password'=>'student','status'=>1,'caseId'=>1,'dateinto'=>time(),'sex'=>2,'classId'=>2),
                7=>array('name'=>'胡才啥','mobile'=>'15111911789','email'=>'15111911789@163.com','password'=>'student','status'=>1,'caseId'=>1,'dateinto'=>time(),'sex'=>2,'classId'=>2),
                8=>array('name'=>'胡吖啥','mobile'=>'15111911889','email'=>'15111911889@163.com','password'=>'student','status'=>1,'caseId'=>1,'dateinto'=>time(),'sex'=>2,'classId'=>2),
            );
        }
        return $data;
    }
}