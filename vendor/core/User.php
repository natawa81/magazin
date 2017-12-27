<?php

namespace vendor\core;

class User {
    
    /**
     * Ключ пользователя
     * @var int
     */
    protected $id = 0;
    
    /**
     * Поля пользователя
     * @var array
     */
    protected $fields = [];
    
    /**
     * Пользователь вошёл или нет
     * @var bool
     */
    protected $isLogged = false;
    
    /**
     * Подключение к бд
     * @var Db
     */
    protected $db;

    protected $user_group = [];

    public function __construct() {
        $this->db = Db::instance();
        if (isset($_SESSION['user_id'])) {
            $id = intval($_SESSION['user_id']);
            $query = $this->db->queryRow("SELECT * FROM `users` WHERE `user_id` = ?",[$id]);
            if (count($query) > 0) {
                $this->isLogged = true;
                $this->id = $query['user_id'];
                $this->fields = $query;
            }
        }
    }
    
    /**
     * Возвращает ключ пользователя
     * @return int
     */
    public function id(){
        return $this->id;
    }
    
    public function name () {
        return $this->login()? $this->fields['name']:'';
    }

    public function EqualPassword ($pass, $hash = null) {
        if ($hash == null) {
            $hash = $this->fields['password'];
        }
        return password_verify($pass, $hash);
    }

    public function SavePassword($pass) {
        $this->db->execute("UPDATE `users` SET `password` = ? WHERE `user_id` = ?", [self::HASH(['password'=>$pass]), $this->id()]);
    }
    
    /**
     * Пользователь вошёл или нет
     * @return bool
     */
    public function login () {
        return $this->isLogged;
    }

    public function field ($field) {
        $can = ['email','user_id', 'vk_uid'];
        if(in_array($field,$can)) {
            return $this->fields[$field];
        } else return '';
    }

    public function group() {
        return $this->login()?$this->fields['user_group'] : -1;
    }
    
    public function isAdmin () {
        if (!$this->user_group) {
            $this->user_group = $this->db->queryRow("SELECT * FROM `groups` WHERE `id` = ?", [$this->group()]);
        }
        if ($this->user_group) {
            $rights = unserialize($this->user_group['rights']);
            return in_array('1', $rights);
        }
        return false;
    }

    public function access ($item) {
        if ($this->login()) {
            $cfg = GetConfig();
            $s_users = explode(",", $cfg['super_user']);
            if (in_array($this->id, $s_users)) return true;

            if (!$this->user_group) {
                $this->user_group = $this->db->queryRow("SELECT * FROM `groups` WHERE `id` = ?", [$this->group()]);
            }
            if ($this->user_group) {
                $rights = unserialize($this->user_group['rights']);
                return isset($rights[$item]) ? ( $rights[$item]=='1' ? true : false ) : false;
            }
        }
        return false;
    }

    public function logout () {
        session_destroy();
    }
    
    /**
     * Вводите массив пользователя из бд
     * @param type array
     * @return type
     */
    public static function HASH ($row) {
        return password_hash($row['password'],PASSWORD_BCRYPT);
    }

    public function CompleteRemember ($link, $password, $re_password) {
        $msg = ['error' => 0, 'success' => 0];

        $row = $this->db->queryRow("SELECT * FROM `users` WHERE `remember_link` = ? AND `remember_time` > UNIX_TIMESTAMP()", [$link]);
        if ($row) {
            if ($password == $re_password) {
                $password = self::HASH(['password' => $password]);
                $this->db->execute("UPDATE `users` SET `password` = ?, `remember_link` = '', `remember_time` = '0'", [$password]);
                $msg['success'] = 3;
            } else $msg['error'] = 3;
        }
        return $msg;
    }

    public function Remember($email) {
        $msg = ['error' => 0, 'success' => 0];
        $row = $this->db->queryRow("SELECT * FROM `users` WHERE `email` = ?", [$email]);
        if ($row) {
            $link = md5(mt_rand(0, 19090) . mt_rand(0, strlen($email))) . substr(sha1(mt_rand(101, 30902)), 2, 10);
            $time = time() + (24 * 60 * 60);
            $this->db->execute("UPDATE `users` SET `remember_link` = ?, `remember_time` = ? WHERE `user_id` = ?", [$link, $time, $row['user_id']]);

            mail($email, "Восстановление пароля", "Ссылка на восстановление пароля:\n".url('/login/remember?link='.$link));
            $msg['success'] = 2;
        } else {
            $msg['error'] = 1;
        }
        return $msg;
    }

    public function LoginByEmail ($email) {
        $row = $this->db->queryRow("SELECT * FROM `users` WHERE `email` = ?", [$email]);
        if ($row) {
            $_SESSION['user_id'] = $row['user_id'];
            return true;
        } else {
            return false;
        }
    }

    public function Authorize ($username, $password) {
        $msg = ['error'=>0,'success'=>0];
        
        $row = $this->db->queryRow("SELECT * FROM `users` WHERE `email` = ?", [$username]);
        if ($row) {
            if ($this->EqualPassword($password,$row['password'])){
                $_SESSION['user_id'] = $row['user_id'];
                $msg['success'] = 1;
            } else $msg['error'] = 2;
        } else $msg['error'] = 1;
        return $msg;
    }
    
}
