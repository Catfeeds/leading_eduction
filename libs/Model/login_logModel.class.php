<?php
namespace libs\Model;
use framework\libs\core\DB;

class login_logModel extends tableModel
{
    private static $table = 'login_log';
    
    /**
    * 写入一条登陆记录
    * @date: 2017年5月12日 上午11:02:06
    * @author: lenovo2013
    * @param: accNumber 登陆账号 caseId 账号类型 loginTime 登陆时间
    * @return:
    */
    public function writeOne($accNumber,$caseId)
    {
        $arr['accNumber'] = $accNumber;
        $arr['caseId'] = $caseId;
        $arr['loginTime'] = time();
        return DB::insert(self::$table,$arr);
    }
}