<?php
namespace App\admin\Model;

class infoModel
{
    private $talbe = 'leading_student';
    
    public function query($sql,$table){
        $table = is_null($table)?$this->table:$table;
        $obj   = M("{$table}");
        return $obj->query($sql);
    }
    public function insert($table,$arr)
    {
        $obj = M("{$table}");
        return $obj->insert($table,$arr);
    }
    public function insertSql($table,$sql)
    {
        $obj = M("{$table}");
        return $obj->insertSql($sql);
    }
    public function formatResponse($res)
    {
        $data['status'] = 1;
        $data['msg']    = 'failed';
        if (is_array($res) && count($res) > 0) {
            $data['info']   = $res;
            $data['status'] = 0;
            $data['msg']    = 'success';
        }else{
            if($res > 0){
                $data['status'] = 0;
                $data['msg']    = 'success';
            }
        }
        return $data;
    }
    public function deleteRow($table,$where)
    {
        $obj = M("$table");
        return $obj->deleteRow($table,$where);
    }
    public function deleteRowSql($sql)
    {
        $obj = M("$this->table");
        return $obj->deleteRowSql($sql);
    }
    public function update($table,$arr,$where,$tableArr=null)
    {
        if(is_array($table)){
            $obj = M("{$table[0]}");
        }else{
            $obj = M("{$table}");
        }
        return $obj->update($table,$arr,$where,$tableArr);
    }
    public function updateSql($sql)
    {
        $obj = M("$this->table");
        return $this->obj->updateSql($sql);
    }
    public function fetchOne_bySql($sql,$table = null)
    {
        $table = is_null($table)?$this->table:$table;
        $obj   = M("{$table}");
        return $obj->fetchOne($sql);
    }
    public function fetchOne_byArr($table,$arr,$where)
    {
        $obj = M("{$table}");
        return $obj->fetchOne_byArr($table,$arr,$where);
    }
    public function fetchAll_bySql($sql)
    {
        $obj  = M("$this->table");
        return $obj->fetchAll($sql);
    }
    public function fetchAll_byArr($table,$arr,$where)
    {
        $obj = M("{$table}");
        return $obj->fetchAll_byArr($table,$arr,$where);
    }
    public function fetchOne_byArrJoin($table,$arr,$where,$tableArr = null)
    {
        $obj = M("{$table[0]}");
        return $obj->fetchOne_byArrJoin($table,$arr,$where,$tableArr);
    }
    public function fetchAll_byArrJoin($table,$arr,$where,$tableArr = null)
    {
        $obj = M("{$table[0]}");
        return $obj->fetchAll_byArrJoin($table,$arr,$where,$tableArr);
    }
    public function getNum($table,$arr,$where)
    {
        $obj = M("{$table}");
        return $obj->getNum($table,$arr,$where);
    }
    
    public function verifyCount($arr,$all)
    {
        $count = 0;
        $count = count((array_diff_key($arr,array_flip($all))));
        return $count;
    }
    
}