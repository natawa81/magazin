<?php

namespace app\controllers\admin;


class OrderController extends AppController {

    protected $types = [
        'Ожидает отправки',
        'Принят в обработку',
        'Был отправлен',
        'Был получен'
    ];

    public function indexAction() {

        if (!$this->user->access('order')) {
            RefreshPage(url('/admin'));
        }
        $vars = [];

        $execute = [];
        $num = 10;
        $page = isset($_GET['page']) ? abs(intval($_GET['page'])) : 1;
        $result = $this->DB()->BindSQL("SELECT COUNT(*) as `count` FROM `orders` ORDER BY `id` DESC", $execute, true);
        $posts = $result[0]['count'];
        $total = intval(($posts - 1) / $num) + 1;
        $page = min($page, $total);
        $start = $page * $num - $num;


        $execute[] = [$start, \PDO::PARAM_INT];
        $execute[] = [$num, \PDO::PARAM_INT];

        $vars ['orders'] = $this->DB()->BindSQL("SELECT O.*, U.email FROM orders O, users U WHERE O.user_id = U.user_id ORDER BY O.id DESC LIMIT ?, ?", $execute, true);

        $vars ['pgn'] = createPagination($page,1,$total,2,url('/admin/order?page={page}'));


//        $vars['orders'] = $this->DB()->findBySql("SELECT O.*, U.email FROM orders O, users U WHERE O.user_id = U.user_id");
        $vars['types'] = $this->types;
        if (isset($_POST['set'])) {
            $id = intval($_POST['set']);
            $order = $this->DB()->findOne($id);
            $type = isset($_POST['type']) ? intval($_POST['type']) : $order['admintype'];
            $type = isset($this->types[$type]) ? $type : 0;
            if ($order) {
                if ($order['curator'] == '0' || $order['curator'] == $this->user->id()) {
                    $this->DB()->query("UPDATE `orders` SET `curator` = ?, `admintype` = ? WHERE `id` = ?",
                        [$this->user->id(), $type, $order['id']]);

                    RefreshPage();
                } else {
                    $this->ShowMessage('Ошибка!', 'Данный заказ уже закреплён!');
                }
            }
        }
        $this->set($vars);
    }
}