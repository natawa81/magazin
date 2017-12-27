<?php
namespace app\models\admin;


use vendor\core\Model;

class Users extends Model
{
    public $table = 'users';
    public $pk = 'user_id';

    public function GetAllGroups () {
        $query = $this->findBySql("SELECT * FROM `groups`");
        $arr = [];
        foreach ($query as $v) {
            $arr[$v['id']] = $v;
        }
        return $arr;
    }

    public function UpdateUser($arr, $id) {
        $updates = [];
        foreach ($arr as $key => $item) {
            $updates[] = "`{$key}` = ?";
        }
        $values = array_values($arr);
        $values[] = $id;
        $this->query("UPDATE `{$this->table}` SET ".implode(",", $updates)." WHERE `{$this->pk}` = ?", $values);
    }
}