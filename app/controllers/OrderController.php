<?php

namespace app\controllers;


use app\models\Order;

class OrderController extends AppController
{

    public function __construct($route)
    {
        parent::__construct($route);
        $this->setModel(new Order());
    }

    public function indexAction(){

        $is_logged = $this->user->login();

        if ($is_logged) {
            $this->DB()->user_id = $this->user->id();

            $items = $this->DB()->GetItems();

            if (isset($_POST['payOne'])) {
                $id = intval($_POST['payOne']);
                if (isset($items[$id])) {

                    $dataOrder = unserialize($items[$id]['data']);
                    if ($items[$id]['type'] == 'payed') {
                        $this->ShowMessage("Ошибка!","Данный товар уже оплачен!");
                    } else {
                        $order = $items[$id]['order_id'];
                        $data = GetConfig('payment');
                        $desc = 'Оплата товара на сайте';
                        $sum = $dataOrder['price'] * (int)$items[$id]['need_count'];
                        $link = 'https://unitpay.ru/pay/' . $data['public_key'] . '?sum=' . $sum . '&account=' . $order . '&desc=' . $desc . '&signature=' . $this->getUrlSing($order, $sum, $desc, $data['secret_key']);
                        header('Location: ' . $link);
                    }
                }
            } else if (isset($_POST['payAll'])) {
                $idList = [];
                $sum = 0;
                $errors = [];
                foreach ($_POST['payItem'] as $id) {
                    if (isset($items[$id])) {
                        $item = $items[$id];
                        $dataOrder = unserialize($item['data']);
                        if ($item['type'] == 'payed') {
                            $errors[] = 'Заказ `'.$dataOrder['title'].'` уже оплачен!';
                        } else {
                            $idList[] = $item['order_id'];
                            $sum += (float)$dataOrder['price'] * (int)$item['need_count'];
                        }
                    }
                }
                if (count($errors) == 0) {
                    $data = GetConfig('payment');
                    $desc = 'Оплата товара на сайте';
                    $order = implode(",", $idList);
                    $link = 'https://unitpay.ru/pay/' . $data['public_key'] . '?sum=' . $sum . '&account=' . $order . '&desc=' . $desc . '&signature=' . $this->getUrlSing($order, $sum, $desc, $data['secret_key']);
                    header('Location: ' . $link);
                } else {
                    $this->ShowMessage("Ошибка!", $errors);
                }
            }
        }

        $this->set(compact('items', 'is_logged'));
    }


    public function payAction () {

        $data = GetConfig('payment');

        $method = '';
        $params = array();
        if ((isset($_GET['params'])) && (isset($_GET['method'])) && (isset($_GET['params']['signature']))) {
            $params = $_GET['params'];
            $method = $_GET['method'];
            $signature = $params['signature'];

            $secret_key = $data['secret_key'];

            if (empty($signature)) {
                $status_sign = false;
            } else {
                $status_sign = $this->verifySignature($params, $method, $secret_key);
            }
        } else {
            $status_sign = false;
        }
        if ($status_sign) {
            switch ($method) {
                case 'check':
                    $result = $this->check($params);
                    break;
                case 'pay':
                    $result = $this->pay($params);
                    break;
                case 'error':
                    $result = $this->error($params);
                    break;
                default:
                    $result = array('error' =>
                        array('message' => 'неверный метод')
                    );
                    break;
            }
        } else {
            $result = array('error' =>
                array('message' => 'неверная сигнатура')
            );
        }

        $this->hardReturnJson($result);
    }

    function check ($params) {

        $order_id = $params['account'];
        $order = $this->DB()->GetOrder($order_id);
        if ($order) {
            $total = (float)$order['sum'];
            $ISOCode = 'RUB';
            if ((float)$total != (float)$params['orderSum']) {
                $result = array('error' =>
                    array('message' => 'не совпадает сумма заказа')
                );
            }elseif ($ISOCode != $params['orderCurrency']) {
                $result = array('error' =>
                    array('message' => 'не совпадает валюта заказа')
                );
            } elseif ($order['type'] == 'payed') {
                $result = array(
                    'error' => array (
                        'message' => 'Товар уже оплачен'
                    )
                );
            }
            else{

                $result = array('result' =>
                    array('message' => 'Запрос успешно обработан')
                );
            }

        } else {
            $result = array('error' =>
                array('message' => 'заказа не существует')
            );
        }

        return $result;
    }
    function pay ($params) {
        $order_id = $params['account'];
        $order = $this->DB()->GetOrder($order_id);
        if ($order) {
            $total = (float)$order['sum'];
            $ISOCode = 'RUB';
            if ((float)$total != (float)$params['orderSum']) {
                $result = array('error' =>
                    array('message' => 'не совпадает сумма заказа')
                );
            }elseif ($ISOCode != $params['orderCurrency']) {
                $result = array('error' =>
                    array('message' => 'не совпадает валюта заказа')
                );
            } elseif ($order['type'] == 'payed') {
                $result = array(
                    'error' => array (
                        'message' => 'Товар уже оплачен'
                    )
                );
            }
            else{

                $result = array('result' =>
                    array('message' => 'Запрос успешно обработан')
                );

                $this->DB()->Pay($order_id);
            }

        } else {
            $result = array('error' =>
                array('message' => 'заказа не существует')
            );
        }

        return $result;
    }
    function error ($params) {}

    function hardReturnJson( $arr )
    {
        debug($arr);
        die;
        header('Content-Type: application/json');
        $result = json_encode($arr);
        die($result);
    }


    function verifySignature($params, $method, $secret)
    {
        return $params['signature'] == $this->getSignature($method, $params, $secret);
    }

    function getUrlSing ($order, $sum, $desc, $secret) {
        $items = [];
        $items['account'] = $order;
        $items['currency'] = 'RUB';
        $items['desc'] = $desc;
        $items['sum'] = $sum;
        $items['secretKey'] = $secret;
        return hash('sha256', join('{up}', $items));
    }

    function getSignature($method, array $params, $secretKey)
    {
        ksort($params);
        unset($params['sign']);
        unset($params['signature']);
        array_push($params, $secretKey);
        array_unshift($params, $method);
        return hash('sha256', join('{up}', $params));
    }

}