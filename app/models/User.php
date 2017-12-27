<?php

namespace app\models;


use vendor\core\Model;

class User extends Model {
    public  $table = 'profiles';
    public  $pk = 'user_id';


    public function Profile ($id) {
        $arr = $this->findOne($id);
        if (!$arr) {
            $this->query("INSERT INTO `{$this->table}` (`{$this->pk}`) VALUES (?)", [$id]);
            $arr = $this->findOne($id);;
        }
        return $arr;
    }

    public function Set($arr) {
        $set = [];
        foreach ($arr as $key => $item) {
            $set[] = "`{$key}` = ?";
        }
        $this->Update(implode(",", $set), "", array_values($arr));
    }
}