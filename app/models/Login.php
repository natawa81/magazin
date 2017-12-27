<?php

namespace app\models;


use vendor\core\Model;

class Login extends Model
{
    public $table = 'users';
    public $pk = 'id';

    public function link($link)
    {
        $row = $this->findBySql("SELECT * FROM `{$this->table}` WHERE `remember_link` = ? AND `remember_time` > UNIX_TIMESTAMP()", [$link]);
        if (count($row) == 1) {
            return $row[0];
        } else return false;
    }
}