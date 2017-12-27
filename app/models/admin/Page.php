<?php

namespace app\models\admin;


use vendor\core\Model;

class Page extends Model
{
    protected $table = 'pages';

    public function updatePage ($arr, $id) {
        $execute = array_values($arr);
        $execute[] = $id;
        $set = [];
        foreach ($arr as $key => $val) {
            $set[] = "`{$key}` = ?";
        }
        $this->query("UPDATE `{$this->table}` SET ".implode(",", $set) . " WHERE `{$this->pk}` = ?", $execute);
    }
}