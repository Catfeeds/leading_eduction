<?php
namespace App\admin\Model;

class getTeacherInfoModel extends infoModel
{
    /**
     * 获得教师的相关信息
     */
    public function getTeacher($accNumber,$param)
    {
        $accNumber = $this->formatTeacher($accNumber);//格式化搜索字段
        $where['teacherId'] = $accNumber;
        switch ($param) {
            case 'mooc':
                break;
            case 'project':
                $data = $this->getTeacherProject($accNumber,$where);
                break;
            case 'recommend':
                break;
            case 'homework':
            case 'base':
            default:
                break;
        }
        return $data;
    }
    /**
     * 获得班主任的信息
     */
    public function getTeacherMaster($accNumber,$param)
    {
        
    }
    /**
     * 格式化教师号，若搜索字段为手机号，换成学号
     * return string
     */
    public function formatTeacher($accNumber)
    {
        if(isMobile($accNumber)){
            $data = $this->getTeacherId_byMobile($accNumber);
            if(count($data) > 0){
                $accNumber = $data['teacherId'];
            }
        }
        return $accNumber;
    }
    /**
     * 通过手机号获得教师号
     * return array
     */
    public function getTeacherId_byMobile($accNumber)
    {
        $arr = array('teacherId');
        $where['mobile'] = $accNumber;
        return parent::getInfo_byArr('leading_teacher',$arr,$where);
    }
    /**
     * 获得教师下的所有项目
     * return array
     */
    public function getTeacherProject($accNumber,$where)
    {
        $data = $this->getTeacherProjectInfo($accNumber,$where);//获得教师下的所有项目信息
        $count = count($data);
        for($i=0;$i<$count;$i++){
            $data[$i] = array_merge($data[$i],$this->getProjectStuInfo($data[$i]['id']));
        }
        return $data;
    }
    /**
     * 获得教师下的所有项目信息
     * @param $accNumber 教师号
     * @param $where array 搜索条件
     * return array
     */
    public function getTeacherProjectInfo($accNumber,$where)
    {
        $arr = array('id','projectName','startTime','status','courseId','description','url','people');
        return parent::getInfoAll_byArr('project',$arr,$where);
    }
    /**
     * 获得项目下的所有学员大致信息
     * param $accNumber 项目id
     * return array
     */
    public function getProjectStu($accNumber)
    {
        $arr = array('stuId','assess','stuDescription');
        $where['projectId'] =$accNumber;
        return parent::getInfoAll_byArr('student_project',$arr,$where);
    }
    /**
     * 获得项目下学员的详细信息
     * @param $accNumber 项目id
     */
    public function getProjectStuInfo($accNumber)
    {
        $data = $this->getProjectStu($accNumber);//获得项目下的所有学员信息
        $count = count($data);
        for($i=0;$i<$count;$i++){
            $data[$i] = array_merge($data[$i],$this->getStuInfo_byStuId($data[$i]['stuId']));//合并所有的学员信息
        }
        return $data;
    }
    /**
     * 通过学号获得所需的学员信息
     */
    public function getStuInfo_byStuId($accNumber)
    {
        $arr = array('name','sex','classId','mobile','dateinto');
        $where = " s.stuId = f.stuId and s.stuId = {$accNumber} ";
        return parent::getInfo_byArrJoin($arr,$where,'leading_student','leadint_student_info');
    }
}