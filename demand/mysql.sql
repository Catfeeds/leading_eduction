SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `leading_education`
--

CREATE DATABASE IF NOT EXISTS `leading_education` CHARACTER SET utf8;



--
-- 表的结构 `course`
--
CREATE TABLE IF NOT EXISTS `course` (
  `courseId` int unsigned NOT NULL COMMENT '课程id',
  `courseName` varchar(40) NOT NULL COMMENT '课程名',
  `description` text DEFAULT NULL COMMENT '课程简介',
  `status` int unsigned DEFAULT 1 COMMENT '课程状态',
  PRIMARY KEY (`courseId`),
  UNIQUE KEY `courseName` (`courseName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 表的结构 `second_course`
--
CREATE TABLE IF NOT EXISTS `sec_course`(
	`id` int unsigned NOT NULL COMMENT '子课程id',
	`courseId` int unsigned NOT NULL COMMENT '课程id',
	`secCourseName` varchar(40) NOT NULL COMMENT '子课程名',
	`description` text DEFAULT NULL COMMENT '子课程介绍',
	`content` text DEFAULT NULL COMMENT '子课程内容',
	`status` int(2) unsigned DEFAULT 1 COMMENT '课程状态',
	`startDate` int(10) unsigned DEFAULT NULL COMMENT '开课时间',
	`picUrl` varchar(200) COMMENT '课程图片地址',
	`vedioUrl` varchar(200) COMMENT '课程视频地址',
	PRIMARY KEY (`id`),
	UNIQUE KEY `picUrl` (`picUrl`),
	UNIQUE KEY `vedioUrl` (`vedioUrl`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 表的结构 `second_course_sign`子课程报名
--
CREATE TABLE IF NOT EXISTS `sec_course_sign`(
	`id` int unsigned NOT NULL COMMENT AUTO_INCREMENT '子课程报名表id',
	`secCourseId` int unsigned NOT NULL COMMENT '子课程id',
	`signId` varchar(20) DEFAULT NULL COMMENT '报名账号',
	`dateinto` int(10) unsigned DEFAULT NULL COMMENT '报名时间',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 学习阶段表`course_stage`
--
CREATE TABLE IF NOT EXISTS `course_stage`(
	`stageId` int unsigned NOT NULL COMMENT '学习阶段号',
	`courseId` int unsigned NOT NULL COMMENT '课程id',
	`stageName` varchar(60) DEFAULT NULL COMMENT '阶段名',
	PRIMARY KEY(`stageId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 学习内容表`course_content`
--
CREATE TABLE IF NOT EXISTS `course_content`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '学习内容id',
	`courseId` int unsigned NOT NULL COMMENT '课程id',
	`stageId` int unsigned NOT NULL COMMENT '学习阶段号',
	`content` text DEFAULT NULL COMMENT '主讲内容',
	`foucus` text DEFAULT NULL COMMENT '技术要点',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 学习目标_能力表`course_goal_ablity`
--
CREATE TABLE IF NOT EXISTS `course_goal_ablity`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表编号',
	`courseId` int unsigned NOT NULL COMMENT '课程id',
	`stageId` int unsigned NOT NULL COMMENT '学习阶段号',
	`ablity` text DEFAULT NULL COMMENT '可掌握的能力',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 学习目标_解决问题表`course_goal_problem`
--
CREATE TABLE IF NOT EXISTS `course_goal_problem`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '解决问题编号',
	`courseId` int unsigned NOT NULL COMMENT '课程id',
	`stageId` int unsigned NOT NULL COMMENT '学习阶段号',
	`problem` text DEFAULT NULL COMMENT '可解决的问题',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 学习目标_价值表`course_goal_value`
--
CREATE TABLE IF NOT EXISTS `course_goal_value`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '解决问题编号',
	`courseId` int unsigned NOT NULL COMMENT '课程id',
	`stageId` int unsigned NOT NULL COMMENT '学习阶段号',
	`value` text DEFAULT NULL COMMENT '可实现的价值',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 学费价格表`tuition`
--

CREATE TABLE IF NOT EXISTS `tuition`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '学费id',
	`courseId` int unsigned NOT NULL COMMENT '课程id',
	`caseId` int(2) unsigned DEFAULT 1 COMMENT '班级类别1-基础|2-就业',
	`teaching` int(2) unsigned DEFAULT 1 COMMENT '授课方式1-面授|2-双元',
	`tuitionCase` int(2) unsigned DEFAULT 1 COMMENT '缴费方式1-先|2-后',
	`tuitionMoney` int(8) unsigned DEFAULT NULL COMMENT '学费',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 备注表`note`
--

CREATE TABLE IF NOT EXISTS `note`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`noteId` int unsigned NOT NULL COMMENT '需要备注的id',
	`note` varchar(300) DEFAULT NULL COMMENT '备注内容',
	`caseId` int(2) unsigned DEFAULT 1 COMMENT '备注形式1-行|2-表',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 学生表`leading_student`
--
CREATE TABLE IF NOT EXISTS `leading_student`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`stuId` char(10) NOT NULL COMMENT '学号',
	`name` varchar(30) NOT NULL COMMENT '姓名',
	`mobile` char(11) DEFAULT NULL COMMENT '手机号',
	`email` varchar(30) DEFAULT NULL COMMENT '邮箱',
	`password` char(32) DEFAULT NULL COMMENT '密码',
	`status` tinyint unsigned DEFAULT 1 COMMENT '激活状态|1-激活',
	`caseId` tinyint unsigned DEFAULT 1 COMMENT '角色id',
	`dateinto` int unsigned DEFAULT NULL COMMENT '入学时间',
	`token` varchar(50) DEFAULT NULL COMMENT '激活码',
	`token_exptime` int DEFAULT NULL COMMENT '激活码有效期',
	PRIMARY KEY (`id`),
	UNIQUE KEY (`stuId`),
	UNIQUE KEY (`mobile`),
	UNIQUE KEY (`email`),
	INDEX(`dateinto`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 学生信息表`leading_student_info`
--
CREATE TABLE IF NOT EXISTS `leading_student_info`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`stuId` char(10) NOT NULL COMMENT '学号',
	`sex` int(1) unsigned DEFAULT 1 COMMENT '性别1-男|2-女',
	`age` int(10) unsigned DEFAULT NULL COMMENT '出生年月',
	`otherMobile` char(11) DEFAULT NULL COMMENT '紧急联系人联系方式',
	`classId` int unsigned DEFAULT NULL COMMENT '班级号',
	`status` int(1) DEFAULT 1 COMMENT '1-在读|2-毕业',
	`eduBacId` int(2) DEFAULT 1 COMMENT '最高学历',
	`ecardId` varchar(20) DEFAULT NULL COMMENT '身份证号',
	`bloodType` varchar(20) DEFAULT NULL COMMENT '血型',
	`homeAddress` varchar(200) DEFAULT NULL COMMENT '家庭地址',
	`picUrl` varchar(200) DEFAULT NULL COMMENT '照片地址',
	`qq` varchar(15) DEFAULT NULL COMMENT 'qq号',
	`wechat` varchar(30) DEFAULT NULL COMMENT '微信号',
	`provinceId` int(3) unsigned DEFAULT NULL COMMENT '省份编号',
	`description` text DEFAULT NULL COMMENT '个人简介',
	PRIMARY KEY (`id`),
	UNIQUE KEY (`stuId`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* CREATE TABLE IF NOT EXISTS `student`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`stuId` char(10) COMMENT '学号',
	`name` varchar(30) NOT NULL COMMENT '姓名',
	`sex` int(1) unsigned DEFAULT 1 COMMENT '性别1-男|2-女',
	`age` int(10) unsigned DEFAULT NULL COMMENT '出生年月',
	`mobile` char(11) COMMENT '手机号',
	`otherMobile` char(11) DEFAULT NULL COMMENT '紧急联系人联系方式',
	`dateinto` int(10) unsigned DEFAULT NULL COMMENT '入学时间',
	`classId` int unsigned DEFAULT NULL COMMENT '班级号',
	`email` varchar(30) DEFAULT NULL COMMENT '邮箱',
	`status` int(1) DEFAULT 1 COMMENT '1-在读|2-毕业',
	`eduBacId` int(2) DEFAULT 1 COMMENT '最高学历',
	`password` char(32) DEFAULT NULL COMMENT '登陆密码',
	`ecardId` varchar(20) DEFAULT NULL COMMENT '身份证号',
	`bloodType` varchar(20) DEFAULT NULL COMMENT '血型',
	`homeAddress` varchar(200) DEFAULT NULL COMMENT '家庭地址',
	`picUrl` varchar(200) DEFAULT NULL COMMENT '照片地址',
	`qq` varchar(15) DEFAULT NULL COMMENT 'qq号',
	`wechat` varchar(30) DEFAULT NULL COMMENT '微信号',
	`provinceId` int(3) unsigned DEFAULT NULL COMMENT '省份编号',
	`description` text DEFAULT NULL COMMENT '个人简介',
	`caseId` int(1) unsigned DEFAULT 1 COMMENT '1-学生',
	`token` varchar(50) DEFAULT NULL COMMENT '账号激活码',
	`token_exptime` int(10) DEFAULT NULL COMMENT '激活码有效期',
	PRIMARY KEY(`id`),
	UNIQUE KEY `stuId` (`stuId`),
	UNIQUE KEY `mobile` (`mobile`),
	UNIQUE KEY `email` (`email`),
	INDEX(`dateinto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;  */


--
-- 学生教育经历表`student_education`
--

CREATE TABLE IF NOT EXISTS `student_education`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`stuId` char(10) NOT NULL COMMENT '学号',
	`major` varchar(30) DEFAULT NULL COMMENT '专业',
	`eduSchool` varchar(30) DEFAULT NULL COMMENT '最高毕业院校',
	`dateOut` int(10) DEFAULT NULL COMMENT '毕业时间',
	`caseId` int(1) DEFAULT 0 COMMENT '1-最高|0-其他',
	PRIMARY KEY(`id`),
	INDEX(`dateOut`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 学习课程表`study_course`
--

CREATE TABLE IF NOT EXISTS `study_course`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`courseId` int unsigned NOT NULL COMMENT '课程号',
	`secCourseId` int unsigned DEFAULT NULL COMMENT '子课程号' ,
	`stuId` char(10) DEFAULT NULL COMMENT '学号',
	`dateinto` int(10) unsigned DEFAULT NULL COMMENT '开始学习时间',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 


--
-- 班级表`leading_class`
--

CREATE TABLE IF NOT EXISTS `lingsi_class`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`courseId` int unsigned DEFAULT NULL COMMENT '课程号',
	`className` varchar(40) NOT NULL COMMENT '班级名|第12期',
	`startTime` int(10) unsigned DEFAULT NULL COMMENT '开班时间',
	`teacherId` varchar(11) DEFAULT NULL COMMENT '授课老师编号',
	`masterId` varchar(11) DEFAULT NULL COMMENT '班主任标识',
	`classType` int(1) unsigned DEFAULT 1 COMMENT '1-基础2-就业',
	`addressId` int(3) unsigned DEFAULT NULL COMMENT '授课地点编号',
	PRIMARY KEY(`id`),
	INDEX(`startTime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 学校地点表 `school_address`
--

--CREATE TABLE IF NOT EXISTS `school_address`(
--	`addressId` int(3) unsigned COMMENT '授课地点编号',
--	`address` varchar(50) NOT NULL COMMENT '授课地点',
--	PRIMARY KEY(`addressId`)
--) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 作品表`project`
--

CREATE TABLE IF NOT EXISTS `project`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`courseId` int unsigned NOT NULL COMMENT '课程号',
	`projectName` varchar(40) NOT NULL COMMENT '项目名',
	`teacherId` char(10) DEFAULT NULL COMMENT '教师号',
	`accNumber` varchar(10) DEFAULT NULL COMMENT '项目经理编号',
	`description` text DEFAULT NULL COMMENT '作品简介',
	`status` int(1) DEFAULT 1 COMMENT '1-进行中2-已完成',
	`startTime` int(10) DEFAULT NULL COMMENT '开始时间',
	`endTime` int(10) DEFAULT NULL COMMENT '完成时间',
	`picUrl` varchar(200) DEFAULT NULL COMMENT '照片存放路径',
	`url` varchar(200) DEFAULT NULL COMMENT '作品展示路径',
	PRIMARY KEY(`id`),
	INDEX(`courseId`),
	INDEX(`startTime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 学生作品表`student_project`
--

CREATE TABLE IF NOT EXISTS `student_project`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`projectId` int unsigned NOT NULL COMMENT '作品编号',
	`stuId` char(10) NOT NULL COMMENT '学号',
	`assess` varchar(100) DEFAULT NULL COMMENT '教师评语',
	`description` varchar(100) DEFAULT NULL COMMENT '负责功能',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 关注表`concern`
--

CREATE TABLE IF NOT EXISTS `concern`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`concern` varchar(20) NOT NULL COMMENT '关注者账号',
	`concerned` varchar(20) NOT NULL COMMENT '被关注者账号',
	`conTime` int(10) DEFAULT NULL COMMENT '关注时间',
	PRIMARY KEY(`id`),
	INDEX(`concern`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 推荐表`recommend`
--

CREATE TABLE IF NOT EXISTS `recommend`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`recommendId` varchar(15) NOT NULL COMMENT '推荐人账号',
	`stuId` char(10) UNIQUE COMMENT '推荐入学的新学员',
	`dateinto` int(10) DEFAULT NULL COMMENT '推荐时间',
	PRIMARY KEY(`id`),
	INDEX (`recommendId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 就业工作表`student_work`
--

CREATE TABLE IF NOT EXISTS `student_work`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`stuId` char(10) NOT NULL COMMENT '学号',
	`compName` varchar(60) NOT NULL COMMENT '公司名',
	`comPAddress` varchar(200) DEFAULT NULL COMMENT '公司地址',
	`jobName` varchar(30) DEFAULT NULL COMMENT '职位名',
	`salary` int(8) unsigned DEFAULT NULL COMMENT '薪水',
	`treatment` varchar(60) DEFAULT NULL COMMENT '福利待遇',
	`dateOut` int(10) unsigned DEFAULT NULL COMMENT '毕业时间',
	`dateWork` int(10) unsigned NOT NULL COMMENT '工作时间',
	`assess` varchar(100) DEFAULT NULL COMMENT '就业评价',
	PRIMARY KEY(`id`),
	INDEX (`dateWork`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 教师表`leading_teacher`
--
CREATE TABLE IF NOT EXISTS `leading_teacher`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`teacherId` varchar(10) NOT NULL COMMENT '教师号',
	`name` varchar(30) NOT NULL COMMENT '姓名',
	`password` char(32) DEFAULT NULL COMMENT '密码',
	`caseId` tinyint unsigned DEFAULT 2 COMMENT '2-教师|3-班主任|10-both',
	`status` tinyint unsigned DEFAULT 0 COMMENT '激活状态|1-激活',
	`mobile` char(11) NOT NULL COMMENT '手机号',
	`email` char(30) DEFAULT NULL COMMENT '邮箱',
	`datainto` int unsigned DEFAULT NULL COMMENT '入职时间',
	`token` varchar(50) DEFAULT NULL COMMENT '激活码',
	`token_exptime` int unsigned DEFAULT NULL COMMENT '激活码有效期',
	PRIMARY KEY (`id`),
	UNIQUE KEY (`teacherId`),
	UNIQUE KEY (`mobile`),
	UNIQUE KEY (`email`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 教师信息表`leading_teacher_info`
--
CREATE TABLE IF NOT EXISTS `leading_teacher_info`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`teacherId` varchar(10) NOT NULL COMMENT '教师号',
	`sex` int(1) unsigned DEFAULT 1 COMMENT '1-男|2-女',
	`title` int(2) unsigned DEFAULT 1 COMMENT '1-高级讲师',
	`description` text DEFAULT NULL COMMENT '教师简介',
	`picUrl` varchar(200) DEFAULT NULL COMMENT '照片地址',
	PRIMARY KEY (`id`),
	UNIQUE KEY (`teacherId`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* CREATE TABLE IF NOT EXISTS `teacher`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`teacherId` varchar(10) NOT NULL COMMENT '教师号',
	`mobile` char(11) NOT NULL COMMENT '手机号',
	`name` varchar(30) NOT NULL COMMENT '姓名',
	`sex` int(1) unsigned DEFAULT 1 COMMENT '1-男|2-女',
	`title` int(2) unsigned DEFAULT 1 COMMENT '1-高级讲师',
	`description` text DEFAULT NULL COMMENT '教师简介',
	`email` varchar(30) DEFAULT NULL COMMENT '邮箱',
	`picUrl` varchar(200) DEFAULT NULL COMMENT '照片地址',
	`dateinto` int(10) unsigned DEFAULT NULL COMMENT '入职时间',
	`password` varchar(32) DEFAULT NULL COMMENT '登陆密码',
	`caseId` int(1) unsigned DEFAULT 2 COMMENT '2-教师|3-班主任|9-both',
	`token` varchar(50) DEFAULT NULL COMMENT '账号激活码',
	`token_exptime` int(10) unsigned DEFAULT NULL COMMENT '激活码有效期',
	PRIMARY KEY(`id`),
	UNIQUE KEY `teacherId` (`teacherId`),
	UNIQUE KEY `mobile` (`mobile`),
	UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;  */


--
-- 教师课件表`teacher_courseware`
--

CREATE TABLE IF NOT EXISTS `teacher_courseware`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`teacherId` varchar(10) NOT NULL COMMENT '教师号',
	`secCourseId` int unsigned DEFAULT NULL COMMENT '子课程id',
	`description` text DEFAULT NULL COMMENT '课件简介',
	`url` varchar(200) DEFAULT NULL COMMENT '课件地址',
	`caseId` int(1) unsigned DEFAULT 1 COMMENT '1-PPT|2-vedio',
	`dateinto` int(10) unsigned DEFAULT NULL COMMENT '上传时间',
	PRIMARY KEY(`id`),
	INDEX (`dateinto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 班主任管理表`teacher_class`
--

/* CREATE TABLE IF NOT EXISTS `teacher_class`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`teacherId` varchar(10) NOT NULL COMMENT '教师号',
	`classId` int unsigned NOT NULL COMMENT '班级编号',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;  */

--
-- 教师授课表`teaching_course`
--

CREATE TABLE IF NOT EXISTS `teaching_course`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`teacherId` varchar(10) NOT NULL COMMENT '教师号',
	`courseId` int unsigned NOT NULL COMMENT '课程号',
	`secCourseId` int unsigned DEFAULT NULL COMMENT '子课程id',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 视频表`vedio`
--

CREATE TABLE IF NOT EXISTS `vedio`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`vedioId` varchar(10) NOT NULL COMMENT '视频编号',
	`vedioName` varchar(50)	NOT NULL COMMENT '视频名',
	`description` varchar(200) DEFAULT NULL COMMENT '视频简介',
	`vedioUrl` varchar(200) NOT NULL COMMENT '视频地址',
	`courseId` int unsigned NOT NULL COMMENT '课程号',
	`secCourseId` int unsigned DEFAULT NULL COMMENT '子课程id',
	`status` int(1) unsigned DEFAULT 1 COMMENT '1-公开|2-私用',
	`dateinto` int(10) unsigned DEFAULT NULL COMMENT '上传时间',
	`author` varchar(10) NOT NULL COMMENT '上传者id',
	PRIMARY KEY(`id`),
	UNIQUE KEY `vedioId` (`vedioId`),
	INDEX (`dateinto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 


--
-- 视频下载记录表`vedio_download`
--

CREATE TABLE IF NOT EXISTS `vedio_download`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`vedioId` varchar(10) NOT NULL COMMENT '视频编号',
	`accNumber` varchar(20) NOT NULL COMMENT '下载账号',
	`caseId` int(2) unsigned DEFAULT NULL COMMENT '账号类型',
	`downTime` int(10) unsigned DEFAULT NULL COMMENT '下载时间',
	PRIMARY KEY(`id`),
	INDEX (`vedioId`),
	INDEX (`downTime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 


--
-- 轮播图片表`carousel_figure`
--

CREATE TABLE IF NOT EXISTS `carousel_figure`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`picId` varchar(10) NOT NULL COMMENT '图id',
	`picName` varchar(50) DEFAULT NULL COMMENT '图片名',
	`description` varchar(200) DEFAULT NULL COMMENT '图片简介',
	`picUrl` varchar(200) DEFAULT NULL COMMENT '图片路径',
	`courseId` int unsigned DEFAULT NULL COMMENT '课程号',
	`status` int(1) unsigned DEFAULT 1 COMMENT '1-显示|2-隐藏',
	`pic_type` int(1) unsigned DEFAULT 1 COMMENT '1-轮播|2-推荐|3-其他',
	`top` int(1) unsigned DEFAULT 0 COMMENT '1-置顶',
	PRIMARY KEY(`id`),
	UNIQUE KEY `picId` (`picId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 新闻`leading_news`
--

CREATE TABLE IF NOT EXISTS `leading_news`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`title` varchar(50) NOT NULL COMMENT '新闻名',
	`description` varchar(200) DEFAULT NULL COMMENT '简介',
	`content` text DEFAULT NULL COMMENT '内容',
	`news_data` int(10) unsigned DEFAULT NULL COMMENT '发布时间',
	`courseId` int unsigned DEFAULT NULL COMMENT '课程号',
	`top` int(1) unsigned DEFAULT 0 COMMENT '1-置顶',
	PRIMARY KEY(`id`),
	INDEX (`news_data`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 报名表`leading_sign`
--

CREATE TABLE IF NOT EXISTS `leading_sign`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`name` varchar(20) NOT NULL COMMENT '姓名',
	`addressId` int(3) unsigned DEFAULT 1 COMMENT '上课地址编号',
	`courseId` int unsigned DEFAULT NULL COMMENT '课程号',
	`mobile` char(11) DEFAULT NULL COMMENT '手机号',
	`qq` varchar(13) DEFAULT NULL COMMENT 'qq号',
	`wechat` varchar(30) DEFAULT NULL COMMENT '微信号',
	`sign_type` int(1) DEFAULT 1 COMMENT '1-报名|2-咨询',
	`signTime` int(10) DEFAULT NULL COMMENT '报名时间',
	PRIMARY KEY(`id`),
	UNIQUE KEY `mobile` (`mobile`),
	INDEX (`signTime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 登陆记录表 `login_log`
--

CREATE TABLE IF NOT EXISTS `login_log`(
	`id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`accNumber` varchar(20) NOT NULL COMMENT '登陆账号',
	`caseId` int(2) unsigned DEFAULT 1 COMMENT '账号类型',
	`loginTime` int(10) unsigned DEFAULT NULL COMMENT '登陆时间',
	PRIMARY KEY(`id`),
	INDEX (`loginTime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 招聘公司表`leading_company`
--
CREATE TABLE IF NOT EXISTS `leading_company` (
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`comId` varchar(10) NOT NULL COMMENT '公司id号',
	`comName` varchar(60) NOT NULL COMMENT '公司名',
	`mobile` char(11) NOT NULL COMMENT '手机号',
	`email` varchar(30) DEFAULT NULL COMMENT '邮箱',
	`password` char(32) DEFAULT NULL COMMENT '登陆密码',
	`caseId` tinyint unsigned DEFAULT 9 COMMENT '账号类型',
	`status` tinyint unsigned DEFAULT 0 COMMENT '激活状态|1-激活',
	`token` varchar(50) DEFAULT NULL COMMENT '激活码',
	`token_exptime` int DEFAULT NULL COMMENT '激活码有效期',
	`dateinto` int unsigned DEFAULT NULL COMMENT '注册时间',
	PRIMARY KEY (`id`),
	UNIQUE KEY (`comId`),
	UNIQUE KEY (`mobile`),
	UNIQUE KEY (`email`)
)ENGINE=InnoDB DEFAULT CHARSET = utf8;

--
-- 招聘公司表信息表`leading_company_info`
--
CREATE TABLE IF NOT EXISTS `leading_company_info` (
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`comId` varchar(10) NOT NULL COMMENT '公司id号',
	`unionCode` int(1) unsigned DEFAULT 0 COMMENT '1-会员|0-普通',
	`description` text DEFAULT NULL COMMENT '公司简介',
	`startTime` int(10) unsigned DEFAULT NULL COMMENT '合作开始日期',
	`unionTime` int(10) unsigned DEFAULT NULL COMMENT '合作有效期',
	`address` varchar(200) DEFAULT NULL COMMENT '公司地址',
	`picUrl` varchar(200) DEFAULT NULL COMMENT '公司logo图',
	`licenseUrl` varchar(200) DEFAULT NULL COMMENT '公司营业执照图',
	`tel` varchar(13) DEFAULT NULL COMMENT '公司电话号码',
	PRIMARY KEY (`id`),
	UNIQUE KEY (`comId`)
)ENGINE=InnoDB DEFAULT CHARSET = utf8;
--
-- 招聘公司表`leading_company`
--

/* --CREATE TABLE IF NOT EXISTS `leading_company`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`compId` varchar(10) NOT NULL COMMENT '公司id',
	`compName` varchar(60) NOT NULL COMMENT '公司名',
	`status` int(1) unsigned DEFAULT 1 COMMENT '1-账号激活|0-未激活',
	`unionCode` int(1) unsigned DEFAULT 0 COMMENT '1-会员|0-普通',
	`description` text DEFAULT NULL COMMENT '公司简介',
	`startTime` int(10) unsigned DEFAULT NULL COMMENT '合作开始日期',
	`unionTime` int(10) unsigned DEFAULT NULL COMMENT '合作有效期',
	`address` varchar(200) DEFAULT NULL COMMENT '公司地址',
	`picUrl` varchar(200) DEFAULT NULL COMMENT '公司logo图',
	`licenseUrl` varchar(200) DEFAULT NULL COMMENT '公司营业执照图',
	`email` varchar(30) DEFAULT NULL COMMENT '邮箱',
	`mobile` char(11) DEFAULT NULL COMMENT '手机号',
	`tel` varchar(13) DEFAULT NULL COMMENT '公司电话号码',
	`password` char(32) DEFAULT NULL COMMENT '登陆密码',
	`caseId` int(2) unsigned DEFAULT 9 COMMENT '企业',
	`token` varchar(50) DEFAULT NULL COMMENT '账号激活码',
	`token_exptime` int(10) unsigned DEFAULT NULL COMMENT '激活码有效期',
	PRIMARY KEY(`id`),
	UNIQUE KEY `compId` (`compId`),
	UNIQUE KEY `email` (`email`),
	UNIQUE KEY `mobile` (`mobile`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;  */

--
-- 招聘职位表`leading_job`
--

CREATE TABLE IF NOT EXISTS `leading_job`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`compId` varchar(10) NOT NULL COMMENT '公司id',
	`jobName` varchar(30) DEFAULT NULL COMMENT '职位名',
	`status` int(1) unsigned DEFAULT 0 COMMENT '0-未招满|1-已招满',
	`people` int(5) unsigned DEFAULT 0 COMMENT '招聘人数',
	`duty` text DEFAULT NULL COMMENT '岗位职责',
	`demand` text DEFAULT NULL COMMENT '岗位需求',
	`treatment` text DEFAULT NULL COMMENT '福利待遇',
	`workAddress` varchar(200) DEFAULT NULL COMMENT '招聘地址',
	`jobDate` int(10) unsigned DEFAULT NULL COMMENT '招聘日期',
	PRIMARY KEY(`id`),
	INDEX (`compId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 投简历表`leading_resume_log`
--

--CREATE TABLE IF NOT EXISTS `leading_resume_log`(
--	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
--	`compId` varchar(10) NOT NULL COMMENT '公司号',
--	`jobName` varchar(30) NOT NULL COMMENT '职位名',
--	`stuId` char(10) DEFAULT NULL COMMENT '学号',
--	`otherAcc` varchar(30) DEFAULT NULL COMMENT '其他登陆账号',
--	`resumeDate` int(10) unsigned DEFAULT NULL COMMENT '投递时间',
--	PRIMARY KEY(`id`),
--	INDEX (`compId`),
--	INDEX (`resumeDate`)
--) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 
CREATE TABLE IF NOT EXISTS `leading_resume_log`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`compId` varchar(10) NOT NULL COMMENT '公司号',
	`jobName` varchar(30) NOT NULL COMMENT '职位名',
	`accNumber` varchar(30) NOT NULL COMMENT '投递账号',
	`caseId` tinyint DEFAULT 1 COMMENT '账号类型',
	`resumeDate` int(10) unsigned DEFAULT NULL COMMENT '投递时间',
	PRIMARY KEY(`id`),
	INDEX (`compId`),
	INDEX (`resumeDate`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 领思公司个人信息表`leading_staff_info`
--

CREATE TABLE IF NOT EXISTS `leading_staff_info`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`accNumber` varchar(10) NOT NULL COMMENT '员工号',
	`name` varchar(30) NOT NULL COMMENT '姓名',
	`mobile` char(11) NOT NULL COMMENT '手机号',
	`otherTel` varchar(13) DEFAULT NULL COMMENT '紧急联系人联系方式',
	`password` char(32) DEFAULT NULL COMMENT '登陆密码',
	`workTime` int(10) unsigned DEFAULT NULL COMMENT '入职时间',
	`email` varchar(32) DEFAULT NULL COMMENT '邮箱',
	`caseId` int(2) unsigned DEFAULT 0 COMMENT '公司角色',
	`status` int(1) unsigned DEFAULT 1 COMMENT '1-有效|0-无效',
	`rangeId` int(1) unsigned DEFAULT 0 COMMENT '0-无|1-普通|2-所有',
	`token` varchar(50) DEFAULT NULL COMMENT '账号激活码',
	`token_exptime` int(10) unsigned DEFAULT 0 COMMENT '激活码有效期',
	PRIMARY KEY(`id`),
	UNIQUE KEY `accNumber` (`accNumber`),
	UNIQUE KEY `mobile` (`mobile`),
	UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 领思公司人角色表`leading_staff_case`
--

CREATE TABLE IF NOT EXISTS `leading_staff_case`(
	`caseId` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
	`caseName` varchar(20) DEFAULT NULL COMMENT '角色名',
	PRIMARY KEY(`caseId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 



--
-- 临时注册表`temp_register`
--

CREATE TABLE IF NOT EXISTS `temp_register`(
	`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`recommendId` varchar(10) DEFAULT NULL COMMENT '推荐账号',
	`caseId` int(3) unsigned NOT NULL DEFAULT 0 COMMENT '角色id',
	`mobile` char(11) DEFAULT NULL COMMENT '手机号',
	`qq` varchar(13) DEFAULT NULL COMMENT 'qq号',
	`wechat` varchar(30) DEFAULT NULL COMMENT '微信号',
	`email` varchar(30) DEFAULT NULL COMMENT '邮箱',
	`name` varchar(30) DEFAULT NULL COMMENT '账号名',
	`password` char(32) DEFAULT NULL COMMENT '密码',
	`dateinto` int(10) unsigned DEFAULT NULL COMMENT '注册时间',
	`status` int(1) unsigned DEFAULT 0 COMMENT '0-未通过验证|通过',
	`token` varchar(50) DEFAULT NULL COMMENT '账号激活码',
	`token_exptime` int(10) unsigned DEFAULT 0 COMMENT '激活码有效期',
	PRIMARY KEY(`id`),
	UNIQUE KEY `mobile` (`mobile`),
	UNIQUE KEY `qq` (`qq`),
	UNIQUE KEY `wechat` (`wechat`),
	UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 


--
-- 开课地点表`leading_address`
--

CREATE TABLE IF NOT EXISTS `leading_address`(
	`addressId` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '开课地点id',
	`address` varchar(20) DEFAULT NULL COMMENT '地点',
	PRIMARY KEY(`addressId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

--
-- 省份表`province`
--

CREATE TABLE IF NOT EXISTS `province`(
	`provinceId` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '省份编号',
	`province` varchar(40) DEFAULT NULL COMMENT '省份',
	PRIMARY KEY(`provinceId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

