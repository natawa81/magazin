<?php

namespace vendor\core;

use vendor\core\Db;

/**
 * Description of Model
 *
 */
abstract class Model {
    /**
     *
     * @var Db 
     */
    protected $pdo;
    protected $table;
    protected $pk = 'id';
    
    public function __construct() {
        $this->pdo = Db::instance();
    }
    
    public function query($sql,$params = []){
        return $this->pdo->execute($sql,$params);
    }
    
    public function findAll($order="ASC", $select = "*", $where = "", $execute = []){
        if (!empty($where)) $where = "WHERE ".$where;
        $sql = "SELECT {$select} FROM {$this->table} {$where} ORDER BY {$this->pk} ".(strtolower($order)=="asc"?"ASC":"DESC");
        return $this->pdo->query($sql,$execute);
    }

    public function findAllByPK($order="ASC", $select = "*", $where = "", $execute = []) {
        $arr = $this->findAll($order,$select,$where,$execute);
        $return = [];
        foreach ($arr as $item) {
            $return[$item[$this->pk]] = $item;
        }
        return $return;
    }
    
    public function findOne($id, $field = ''){
        $field = $field ?: $this->pk;
        $sql = "SELECT * FROM {$this->table} WHERE $field = ? LIMIT 1";
        $row = $this->pdo->query($sql, [$id]);
        if ($row)
            return $row[0];
        else return false;
    }
    
    public function findOneValues ($select = "*", $where = "", $execute = []) {
        if (!empty($where)) $where = "WHERE ".$where;
        $sql = "SELECT {$select} FROM {$this->table} {$where}";
        return $this->pdo->query($sql,$execute);

    }
    
    public function Insert($params) {
        $sql = "INSERT INTO `{$this->table}` ";
        $values = [];
        $insert = [];
        foreach ($params as $key => $value) {
            $insert[$key] = "?";
            $values[] = $value;
        }
        $sql .= "( ".implode(",", array_keys($insert))." ) VALUES ( ".implode(",",$insert)." )";
        return $this->pdo->execute($sql,$values);
    }
    public function LastID () {
        return $this->pdo->lastInsertID();
    }
    
    public function Update ($set, $where = "", $params = []) {
        $sql = "UPDATE `{$this->table}` SET {$set} ".(!empty($where)?"AND ".$where:"");
        return $this->pdo->execute($sql,$params);
    }
    
    public function delete($id,$field = '') {
        $field = $field ?: $this->pk;
        $sql = "DELETE FROM `{$this->table}` WHERE `{$field}` = ?";
        return $this->pdo->execute($sql,[$id]);
    }


    /**
     * sql - строка запроса,bindValues - массив из элементов типа: [value,PDO::PARAM]
     * @param type string
     * @param type array
     */
    public function BindSQL($sql,$bindValues,$return = false) {
        return $this->pdo->bind($sql,$bindValues,$return);
    }
    
    public function getCount($where = "", $params = []) {
        $sql = "SELECT * FROM `{$this->table}`";
        if(!empty($where)) $sql .= " WHERE ".$where;
        return $this->pdo->rowCount($sql, $params);
    }


    public function findBySql($sql, $params = []){
        return $this->pdo->query($sql, $params);
    }
    
    public function findLike($str, $field, $table = ''){
        $table = $table ?: $this->table;
        $sql = "SELECT * FROM $table WHERE $field LIKE ?";
        return $this->pdo->query($sql, ['%' . $str . '%']);
    }
    
}
