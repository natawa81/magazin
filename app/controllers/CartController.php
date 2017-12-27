<?php
namespace app\controllers;


use app\models\Cart;

class CartController extends AppController
{

    public function  __construct($route)
    {
        parent::__construct($route);
        $this->setModel(new Cart());
    }

    public function indexAction () {
        $this->title('Корзина');
        $data = $this->cart;
        $posts = $this->model->GetAllItems($this->cart);

        if ($this->isAjax()) {
            $id = intval($_GET['id']);
            if (isset($this->cart[$id]))
                unset($this->cart[$id]);
            $this->saveCart();
            $arr = [];
            $arr['msg'] = 'Товар успешно удалён из корзины!';
            $arr['in_cart'] = 0;
            foreach ($this->cart as $i) $arr['in_cart'] += $i;
            die(json_encode($arr));
        }

        if (isset($_POST['buy'])) {
            $values = $_POST['values'];
            foreach ($posts as $item) {
                $need = intval($values[$item['id']]);
                $need = max($need, 1);
                if ($need != $item['need_count'])
                    $item['need_count'] = $need;
                if ($item['need_count'] <= $item['count']) {
                    $this->model->query("UPDATE `items` SET `count` = `count` - ? WHERE `id` = ?", [$item['need_count'],$item['id']]);
                    $this->model->query("INSERT INTO `orders` (`user_id`, `item_id`,`count`,`data`,`time`) VALUES (?,?,?,?,?)",
                        [$this->user->id(), $item['id'],$item['need_count'], serialize($item), time()]);
                    unset($this->cart[$item['id']]);
                } else {
                    $this->cart[$item['id']] = $need;
                }
            }
            $this->saveCart();
        }

        $this->set(compact('posts'));
    }
}