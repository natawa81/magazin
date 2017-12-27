<?php
namespace app\models\admin;


use vendor\core\Model;

class Category extends Model
{
    public $table = 'category';

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
}