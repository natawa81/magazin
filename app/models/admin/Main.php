<?php

namespace app\models\admin;


use vendor\core\Model;

class Main extends Model
{
    public $table = 'items';

    public function updateAttributes($params, $id) {
        $update = [];
        $values = [];

        foreach ($params as $key => $param) {
            $update[] = "`{$key}` = ?";
            $values[] = $param;
        }

        $values[] = $id;

        $this->query("UPDATE `{$this->table}` SET ".implode(", ",$update)." WHERE `id` = ?", $values);
    }

    public function GetCategoryList( ){
        $list = $this->pdo->query("SELECT * FROM `category`");
        $category = [];
        foreach ($list as $item) $category[$item['id']] = $item;

        return $category;
    }
}