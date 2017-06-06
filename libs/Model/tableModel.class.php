<?php
namespace libs\Model;
use framework\libs\core\DB;
class tableModel
{
    public function query($sql)
    {
        return DB::query($sql);
    }
    public function insert($table,$arr)
    {
        return DB::insert($table,$arr);
    }
    
    public function insertSql($sql)
    {
        return DB::insertSql($sql);
    }
    
    public function deleteRow($table,$where)
    {
        return DB::deleteRow($table,$where);
    }
    
    public function deleteRowSql($sql)
    {
        return DB::deleteRowSql($sql);
    }
    
    public function update($table,$arr,$where,$tableArr)
    {
        return DB::update($table,$arr,$where,$tableArr);
    }
    
    public function updateSql($sql)
    {
        return DB::updateSql($sql);
    }
    
    public function fetchOne($sql)
    {
        return DB::fetchOne($sql);
    }
    
    public function fetchAll($sql)
    {
        return DB::fetchAll($sql);
    }
    
    public function fetchOne_byArr($table,$arr,$where)
    {
        return DB::fetchOne_byArr($table,$arr,$where);
    }
    
    public function fetchAll_byArr($table,$arr,$where)
    {
        return DB::fetchAll_byArr($table,$arr,$where);
    }
    
    public function fetchOne_byArrJoin($table,$arr,$where,$tableArr)
    {
        return DB::fetchOne_byArrJoin($table,$arr,$where,$tableArr);
    }
    
    public function fetchAll_byArrJoin($table,$arr,$where,$tableArr)
    {
        return DB::fetchAll_byArrJoin($table,$arr,$where,$tableArr);
    }
    
    public function getNums($sql)
    {
        return DB::getNums($sql);
    }
   
}