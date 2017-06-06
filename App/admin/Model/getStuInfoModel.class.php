<?php
namespace App\admin\Model;

class getStuInfoModel extends infoModel 
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
        if(isMobile($accNumber)){//是手机号
            $data = $this->getStuId_byMobile($accNumber);
            if(count($data) > 0){
                $accNumber = $data['stuId'];
            }
        }
        switch($param){
            case 'base'://基础数据
                $data = $this->getStuBase($accNumber);
                break;
            case 'course'://课程信息
                $data = $this->getCourse_byStuId($accNumber);//没有完成,还需改善
                break;
            case 'project'://简历、作品或项目信息
                $data = $this->getStuProject($accNumber);//完成
                break;
            case 'concern'://关注信息
                $data = $this->getStuConcern($accNumber);//完成
                break;
            case 'concerned'://被关注信息
                $data = $this->getStuConcerned($accNumber);//完成
                break;
            case 'recommend': //推荐信息
                $data = $this->getStuRecommend($accNumber);//完成
                break;
            case '':
            case 'center': //核心数据
            default:
                $data = $this->getStuCenter($accNumber);//完成
                break;
        }
        return $data;
    }
    /**
     * 获得学员基本信息
     */
    public function getStuBase($stuId)
    {
        $arr = array('sex','age','bloodType','provinceId','homeAddress','description');
        $where['stuId'] = $stuId;
        $data = parent::getInfo_byArr('leading_student_info',$arr,$where);
        if(isset($data['provinceId']) && !empty($data['provinceId'])){
            $res = verifyModel::province($data['provinceId']);
            if(count($res) > 0){//如果存在信息
                $data['province'] = $res['province'];
            }
        }
        return $data;
    }
    /**
     * 获得学生核心信息
     */
    public function getStuCenter($stuId)
    {
        $arr = array('mobile','name','picUrl');
        $where = " s.stuId = f.stuId and s.stuId = '{$stuId}' ";
        return parent::getInfo_byArrJoin($arr,$where,'leading_student','leading_student_info');
    }
    /**
     * 根据手机号获得学号
     */
    public function getStuId_byMobile($mobile)
    {
        $where['mobile'] = $mobile;
        $arr = array('stuId');
        return parent::getInfo_byArr('leading_student',$arr,$where);
    }
    /**
    * 根据学号获得该学生学习课程信息
    * @date: 2017年5月16日 上午9:41:39
    * @author: lenovo2013
    * @param: string $stuId 学号
    * @return:array
    */
    public function getCourse_byStuId($stuId)
    {
        $data = $this->getCourseAll_byStuId($stuId);//通过学号获得该学生学过的所有的大课
        $count = count($data);
        if($count > 0){
            for($i=0;$i<$count;$i++){
                $res['courseId'] = $this->getSecCourse($data[$i]['courseId']);//获得所有的子课程id和课程名
            }
        }
        foreach($res as &$val){
            $count = count($val);
            for($i=0;$i<$count;$i++){
                $resp[$i] = $this->getCoursewareAll($val[$i]['id']);
                $val[$i] = array_merge($val[$i],$resp[$i]);
            }
        }
        return $res;
    }
    /**
     * 通过学号获得其所学过的所有的大课
     */
    public function  getCourseAll_byStuId($stuId)
    {
        $arr = array('courseId');
        $where['stuId'] = $stuId;
        return $this->getInfoAll_byArr('study_course',$arr,$where);
    }
    /**
     * 根据课程号获得其下的所有子课程号
     */
    public function getSecCourse($courseId)
    {
        $arr = array('id','content','stageId');
        $where['courseId'] = $courseId;
        return $this->getInfoAll_byArr('course_content',$arr,$where);
    }
    /**
     * 根据课程号获得相关的课件如ppt或视频资料
     */
    public function getCoursewareAll($courseId)
    {
        $arr = array('id','url','dateinto','caseId');
        $where['secCourseId'] = $courseId;
        $where2 = 'order by dateinto desc';
        return $this->getInfoAll_byArr('teacher_courseware',$arr,$where,$where2);
    }
    /**
    * 处理课程信息，
    * @date: 2017年5月16日 上午9:50:53
    * @author: lenovo2013
    * @param: array $data 多维数组 课程信息
    * @return:array 多维数组
    */
    public function handleCourseMsg($data)
    {
        foreach($data as $key=>$val){
            $res['coursId'] = $key;
            $res['secCourseName'] = $this->getCourseName_byCId($val['secCourseId']);
        }
    }
    
    public function getCourseName_byCId($secCourseId)
    {
        //$obj = M('');
    }
    /**
    * 获得学生个人简历信息及作品信息
    * @date: 2017年5月16日 上午10:56:55
    * @author: lenovo2013
    * @param: string 学号
    * @return:
    */
    public function getStuProject($stuId)
    {
        $data = $this->getStuProjectBase($stuId);//获得个人基本信息
        if(count($data) > 0){
            $res  = verifyModel::verifyEducation($stuId);//获得教育背景信息
            $resp = $this->getStuWork($stuId);//获得该学员的工作信息
            $response = $this->getProject_byStuId($stuId);//获得该学员参与的作品信息
            @$data['major']     = $res['major'];
            @$data['eduSchool'] = $res['eduSchool'];
            @$data['compName']  = $resp['compName'];
            @$data['jobName']   = $resp['jobName'];
            @$data['salary']    = $resp['salary'];
            @$data['project'] = $response;
        }
        return $data;
    }
    /**
     * 获得作品页基本信息基本信息
     */
    public function getStuProjectBase($stuId)
    {
        $arr = array('name','sex','age','homeAddress','ecardId','mobile','email','eduBacId','bloodType','description','picUrl');
        $where = " s.stuId = f.stuId and s.stuId = '{$stuId}' ";
        return parent::getInfo_byArrJoin($arr,$where,'leading_student','leading_student_info');
    }
    /**
     * 根据学号获得学生基本工作信息
     */
    public function getStuWork($stuId)
    {
        $arr = array('id','compName','jobName','salary');
        $where['stuId'] = $stuId;
        return parent::getInfo_byArr('student_work',$arr,$where);
    }
    /**
     * 通过学号获得学生参加作品的id及评价
     */
    public function getStuProject_byStuId($stuId)
    {
        $arr = array('id','projectId','assess','stuDescription');
        $where['stuId'] = $stuId;
        $where2 = " order by id ";
        return $this->getInfoAll_byArr('student_project',$arr,$where,$where2);
    }
    /**
     * 根据学号获得学生参加作品的所有详细信息
     */
    public function getProject_byStuId($stuId)
    {
        $data = $this->getStuProject_byStuId($stuId);//获得学员参加的作品id及评价
        $count = count($data);
        if($count > 0 ){
             for($i=0;$i<$count;$i++){
                $res = $this->getProject_byId($data[$i]['projectId']);//获得该作品的详细信息
                $data[$i] = array_merge($data[$i],$res);//合并两个数组
             }
        }
        return $data;
    }
    /**
     * 通过作品id获得作品的所有信息
     */
    public function getProject_byId($projectId)
    {
        $arr = array('*');
        $where['id'] = $projectId;
        $where2 = ' order by id ';
        return parent::getInfo_byArr('project',$arr,$where,$where2);
    }
    /**
     * 根据学号获得该学生关注的所有公司名和邮箱
     */
    public function getStuConcern($stuId)
    {
        $data = $this->getStuConcernAll($stuId);
        $count = count($data);
        if($count > 0){
            for($i=0;$i<$count;$i++){
                $res = $this->getStuConcernCom($data[$i]['concerned']);
                $data[$i] = array_merge($data[$i],$res);
            }
        }
        return $data;
    }
    /**
     * 根据学号获得所有其关注的信息，通过关注者获得所有被关注的信息
     */
    public function getStuConcernAll($stuId)
    {
        $arr = array('id','concerned','conTime');
        $where['concern'] = $stuId;
        $where2 = ' order by conTime desc ';
        return $this->getInfoAll_byArr('concern',$arr,$where,$where2);
    }
    /**
     * 根据公司号或手机号获得公司的邮箱和公司名信息
     */
    public function getStuConcernCom($accNumber)
    {
        $arr = array('compName','email','compId');
        if(isMobile($accNumber)){//若搜索的账号是手机号
            $where['mobile'] = $accNumber;
        }else{//公司号
            $where['compId'] = $accNumber;
        }
        $where2 = '';
        return parent::getInfo_byArr('leading_company',$arr,$where,$where2);
    }
    /**
     * 获得关注此学生的所有公司信息
     */
    public function getStuConcerned($stuId)
    {
        $data = $this->getStuConcernedAll($stuId);
        $count = count($data);
        if($count > 0){
            for($i=0;$i<$count;$i++){
                $res = $this->getStuConcernCom($data[$i]['concern']);
                $data[$i] = array_merge($data[$i],$res);
            }
        }
        return $data;
    }
    /**
     * 通过被关注信息获得关注者信息
     */
    public function getStuConcernedAll($stuId)
    {
        $arr = array('id','concern','conTime');
        $where['concerned'] = $stuId;
        $where2 = ' order by conTime desc ';
        return $this->getInfoAll_byArr('concern',$arr,$where,$where2);
    }
    /**
     * 获得所推荐人的详细信息，如学号，姓名等
     */
    public function getStuRecommend($stuId)
    {
        $data = $this->getStuRecommendId($stuId);//获得所有推荐人的账号
        $count = count($data);
        if($count > 0){
            for($i=0;$i<$count;$i++){
                $res = $this->getStuCenter($data[$i]['stuId']);
                $res = !empty($res)?$res:array();
                $data[$i] = array_merge($data[$i],$res);
            }
        }
        return $data;
    }
    /**
     * 获得所有推荐人的账号及推荐时间
     */
    public function getStuRecommendId($stuId)
    {
        $arr = array('id','stuId','dateinto');
        $where['recommendId'] = $stuId;
        $where2 = ' order by dateinto desc ';
        return $this->getInfoAll_byArr('recommend',$arr,$where,$where2);
    }
}