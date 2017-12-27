<?php

namespace vendor\core;

/**
 * Description of Db
 *
 */
class Db {

    protected $pdo;
    protected static $instance;
    public static $countSql = 0;
    public static $queries = [];
    
    
    public function __construct() {
        $db = require ROOT . '/config/config_db.php';
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,

        ];
        $this->pdo = new \PDO($db['dsn'], $db['user'], $db['pass'], $options);
    }
    
    public static function instance() {
        if(self::$instance === null){
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    public function execute($sql, $params = []){
        self::$countSql++;
        self::$queries[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function lastInsertID () {
        return $this->pdo->lastInsertId();
    }

    public function gExecute ($sql, $params = []) {
        self::$countSql++;
        self::$queries[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function rowCount($sql,$params = []) {
        self::$countSql++;
        self::$queries[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    public function query($sql, $params = []) {
        self::$countSql++;
        self::$queries[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($params);
        if($res !== false){
            return $stmt->fetchAll();
        }
        return [];
    }
    
    public function bind($sql,$bindValues,$return) {
        self::$countSql++;
        self::$queries[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        foreach ($bindValues as $key => $value) {
            $stmt->bindValue($key+1,$value[0],isset($value[1])?$value[1]:\PDO::PARAM_STR);
        }
        $res = $stmt->execute();
        if ($return) {
            if($res !== false){
                return $stmt->fetchAll();
            }
            return [];
        }
        return $res;
    }
    
    public function queryRow($sql, $params = []) {
        self::$countSql++;
        self::$queries[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($params);
        if($res !== false){
            return $stmt->fetch();
        }
        return [];
    }
    
}
