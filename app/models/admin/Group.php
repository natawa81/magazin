<?php
namespace app\models\admin;


use vendor\core\Model;

class Group extends Model
{
    public $table = 'groups';
    public $pk = 'id';
    public function InsertRights($arr)
    {
        if ($arr['startgroup']) {
            $this->Update("`startgroup` = '0'", "`startgroup` = '1'");
        }
        $this->query("INSERT INTO `{$this->table}` (`".implode("`,`", array_keys($arr))."`) VALUES (?,?,?)", array_values($arr));
    }

    public function DeleteRights ($id) {
        $this->delete($id);
        $start = $this->findOne('1', 'startgroup');

        $this->query("UPDATE `users` SET `user_group` = ? WHERE `user_group` = ?", [$start['id'], $id]);
    }

    public function UpdateRights ($arr, $id) {
        if ($arr['startgroup']) {
            $this->Update("`startgroup` = '0'", "`startgroup` = '1'");
        }
        $update = [];
        foreach($arr as $k => $v) {
            $update[] = "`{$k}` = ?";
        }

        $values = array_values($arr);
        $values[] = $id;
        $this->query("UPDATE `{$this->table}` SET ".implode(",", $update)." WHERE `{$this->pk}` = ?", $values);

    }
}