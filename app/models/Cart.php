<?php


namespace app\models;

class Cart extends \vendor\core\Model {
    public $table = 'items';
    public  $pk = 'id';

    public function GetAllItems ($cart) {
        $execute[] = [
            implode(",", array_keys($cart)),
            \PDO::PARAM_STR
        ];
        $posts = $this->BindSQL("SELECT * FROM `{$this->table}` WHERE FIND_IN_SET({$this->pk}, ?) ORDER by `id` DESC", $execute, true);
        foreach ($posts as $index => $post) {
            $post['need_count'] = intval($cart[$post['id']]);
            $posts[$index] = $post;
        }
        return $posts;
    }
}
