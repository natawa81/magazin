<?php

namespace app\models;


use vendor\core\Model;

class Order extends Model
{
    public $table = 'orders';

    public $user_id = 0;

    public function GetItems () {
        $array = $this->findBySql("SELECT O.id as order_id, O.*, I.*, O.count as need_count FROM orders O, items I WHERE O.user_id = ? AND O.item_id = I.id",  [$this->user_id]);
        $arr = [];
        foreach ($array as $item) {
            if (time() > $item['time'] + (24 * 60 * 60) && $item['type'] == 'wait') {
                $this->delete($item['order_id']);
                $this->query("UPDATE `items` SET `count` = `count` + ? WHERE `id` = ?", [$item['need_count'], $item['item_id']]);
            } else {
                $arr[$item['order_id']] = $item;
            }
        }
        return $arr;
    }

    public function Pay($order_id) {


        if(strpos($order_id,',') == false) {
            $params = [
                time(),
                $order_id,
                $this->user_id
            ];
            $this->query("UPDATE `{$this->table}` SET `type` = 'payed', `pay_time` = ? WHERE `{$this->pk}` = ? AND `user_id` = ?", $params);
        } else {
            foreach (explode(",", $order_id) as $id) {
                $params = [ time(), $id, $this->user_id ];
                $this->query("UPDATE `{$this->table}` SET `type` = 'payed', `pay_time` = ? WHERE `{$this->pk}` = ? AND `user_id` = ?", $params);

            }
        }
    }

    public function GetOrder($account) {
        $array = [];

        if(strpos($account,',') == false){
            $item = $this->findOne($account);
            $dataOrder = unserialize($item['data']);
            $array['sum'] = $dataOrder['price'] * $item['count'];
            $array['type'] = $item['type'];
        } else {
            $array['sum'] = 0;
            foreach (explode(",", $account) as $id) {
                $item = $this->findOne($id);
                if ($item['type'] == 'wait') {
                    $dataOrder = unserialize($item['data']);
                    $array['sum'] += (float)$dataOrder['price'] * $item['count'];
                    $array['type'] = $item['type'];
                }
            }
        }
        return $array;
    }
}