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
                $data = $this->getTeacherCourse($accNumber,$where);//获得教师下的所有课程信息
                break;
            case 'project':
                $data = $this->getTeacherProject($accNumber,$where);//获得教师下的所有项目和学员信息
                break;
            case 'recommend':
                $data = $this->getTeacherRecommend($accNumber);//活动教师推荐的学生信息
                break;
            case 'homework':
            case 'base':
            default:
                $data = $this->getTeacherBaseInfo($accNumber);//获得教师的基本信息
                break;
        }
        return $data;
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
    /**
     * 获得教师推荐的学生信息
     * return array
     */
    public function getTeacherRecommend($accNumber)
    {
        $obj = new getStuInfoModel();
        return $obj->getStuRecommend($accNumber);
    }
    /**
     * 获得教师的基本信息
     * return array
     */
    public function getTeacherBaseInfo($accNumber)
    {
        $arr = array('name','mobile','picUrl');
        $where = " s.teacherId = f.teacherId and s.teacherId = '{$accNumber}' ";
        return parent::getInfo_byArrJoin($arr,$where,'leading_teacher','leading_teacher_info');
    }
    /**
     * 获得教师下的所有课程信息，包括子课程
     */
    public function getTeacherCourse($accNumber,$where)
    {
        $data = $this->getTeacherCouseId($accNumber,$where);//获得教师下的所有大课id
        $count = count($data);
        for($i = 0;$i<$count;$i++ ){
            $data[$i] = array_merge($data[$i],$this->getTeacherCourseSec($data[$i]['courseId']));
        }
        return $data;
    }
    /**
     * 获得教师下的所有大课id
     */
    public function getTeacherCouseId($accNumber,$where)
    {
        $arr = array('courseId');
        return parent::getInfoAll_byArr('teaching_course',$arr,$where);
    }
    public function getTeacherCourseSec($courseId)
    {
        $obj = new getStuInfoModel();
        return $obj->getSecCourse($courseId);
    }
    /**
     * 获得班主任信息
     */
    public function getTeacherMaster($accNumber,$param)
    {
        $accNumber = $this->formatTeacher($accNumber);//格式化搜索字段
        $where['teacherId'] = $accNumber;
        switch ($param) {
            case 'student':
                $data = $this->getClassStudent($accNumber);//获得教师下的所有学员信息，根据班级
                break;
            case 'project':
                $data = $this->getTeacherProject($accNumber,$where);//获得教师下的所有项目和学员信息
                break;
            case 'recommend':
                $data = $this->getTeacherRecommend($accNumber);//获得教师推荐的学生信息
                break;
            case 'homework':
            case 'base':
            default:
                $data = $this->getTeacherBaseInfo($accNumber);//获得教师的基本信息
                break;
        }
        return $data;
    }
    /**
     * 获得班级学生信息
     */
    public function getClassStudent($accNumber)
    {
        $data = $this->getMasterClass($accNumber);//获得班主任管理的所有班级
        $count = count($data);
        for($i=0;$i<$count;$i++){//获得每个班级下的所有学生信息
            $data[$i] = array_merge($data[$i],$this->getStudent_byClassId($data[$i]['id']));//根据班级号获得其下学生
        }
        return $data;
    }
    /**
     * 获得班主任管理的所有班级信息
     * return array
     */
    public function getMasterClass($accNumber)
    {
        $arr = array('id','courseId','className','startTime','classType');
        $where['masterId'] = $accNumber;
        $where2 = ' order by id desc ';
        return parent::getInfoAll_byArr('leading_class',$arr,$where);
    }
    /**
     * 根据班级号获得班级下的所有学生信息
     */
    public function getStudent_byClassId($classId)
    {
        $arr = array('name','sex','age','mobile','email','otherMobile','provincdId','ecardId');
        $where = " s.stuId = f.stuId and f.classId = {$classId} ";
        return parent::getInfoAll_byArrJoin($arr,$where,'leading_student','leading_student_info');
    }
}