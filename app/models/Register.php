<?php

namespace app\models;


use vendor\core\Model;

class Register extends Model
{
    public  $table = 'users';

    private $profile_keys = ['user_id','land','region','address','phone-code','phone-number','mail-index'];

    public function register ($arr) {

        $query = $this->findOne($arr['email'],'email');
        if (!$query) {
            $password = \vendor\core\User::HASH(['password' => $arr['password']]);

            $this->table = 'groups';
            $start = $this->findOne('1', 'startgroup');
            $this->table = 'users';

            $this->query("INSERT INTO `users` (`email`,`password`, `user_group`) VALUES (?, ?, ?)", [$arr['email'], $password, $start['id']]);
            $arr ['user_id'] = $this->LastID();
            $values = [];
            foreach ($this->profile_keys as $key) $values[] = $arr[$key];

            $this->query("INSERT INTO `profiles` (`".implode("`,`", $this->profile_keys)."`) VALUES (?,?,?,?,?,?,?)", $values);
            return 1;
        } else return -1;
    }
}