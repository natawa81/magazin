<?php

namespace app\controllers;

use app\models\Main;
use vendor\core\Db;

/**
 * Description of Main
 *
 */
class MainController extends AppController{
    
//    public $layout = 'main';

    public  function __construct($route)
    {
        parent::__construct($route);
        $this->setModel(new Main);
    }

    public function indexAction()
    {
        $this->title('Главная');

        if ($this->isAjax()) {
            $id = intval($_GET['id']);
            if (!isset($this->cart[$id])) $this->cart[$id] = 0;
            $this->cart[$id]++;

            if ($this->user->login()) {
                $this->DB()->query("INSERT INTO `cart` (`user_id`, `data`) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE `data` = VALUES (`data`)", [$this->user->id(), serialize($this->cart)]);
                HttpOnlyCookie('cart', serialize($this->cart));
            } else {
                HttpOnlyCookie('cart', serialize($this->cart));
            }
            $arr = [];
            $arr ['msg'] = "Товар успешно добавлен в корзину!";
            $arr ['in_cart'] = 0;
            foreach ($this->cart as $i) $arr['in_cart'] += $i;
            die(json_encode($arr));
        }


        $data_filters = $this->data_filters_value;
        if (! empty($data_filters['query_name'])) {
            $where[] = "`title` LIKE ?";
            $execute[] = ["%{$data_filters['query_name']}%"];
        }
        if (! empty($data_filters['query_author'])) {
            $where[] = "`author` LIKE ?";
            $execute[] = ["%{$data_filters['query_author']}%"];
        }

        $where [] = "`year` >= ?";
        $execute[] = [$data_filters['min_year'], \PDO::PARAM_INT];
        $where [] = "`year` <= ?";
        $execute[] = [$data_filters['max_year'], \PDO::PARAM_INT];
        $where [] = "`pages` >= ?";
        $execute[] = [$data_filters['min_pages'], \PDO::PARAM_INT];
        $where [] = "`pages` <= ?";
        $execute[] = [$data_filters['max_pages'], \PDO::PARAM_INT];

        if (isset($this->route['category'])) {
            $array = [];
            $array = $this->category->GetIDByParent($this->route['category']);
            $array[] = intval($this->route['category']);
            $where[] = "FIND_IN_SET(category, ?)";
            $execute[] = [implode(",", $array)];
        }

        $num = 12;
        $page = isset($_GET['page']) ? abs(intval($_GET['page'])) : 1;
        $result = $this->DB()->BindSQL("SELECT COUNT(*) as `count` FROM `items` " . (count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "") . " ORDER BY `id` DESC", $execute, true);
        $posts = $result[0]['count'];
        $total = intval(($posts - 1) / $num) + 1;
        $page = min($page, $total);
        $start = $page * $num - $num;

        $execute[] = [$start, \PDO::PARAM_INT];
        $execute[] = [$num, \PDO::PARAM_INT];

        $posts = $this->DB()->BindSQL("SELECT * FROM `items` " . (count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "") . " ORDER BY `id` DESC LIMIT ?, ?", $execute, true);
        $url = [];
        $can = ['query_name','query_author','min_year','max_year','min_pages','max_pages'];
        foreach ($_GET as $k => $v) {if (in_array($k, $can)) $url[$k] = $k.'='.$v;}
        $url[] = 'page={page}';

        $pgn = createPagination($page,1,$total,2,(isset($this->route['category'])?'category-'.$this->route['category'] : '/').'?'.implode("&", $url));
        $this->set(['items'=>$posts, 'pgn' => $pgn]);

    }

    public function viewAction () {
        $id = intval($this->route['id']);

        $post = $this->DB()->findBySql("SELECT I.*, C.title as cat_name FROM items I, category C WHERE I.id = ? AND I.category = C.id",[$id]);
        $post = $post[0];
        if ($post) {
            $this->title($post['title']);
        } else {
            $this->title('Главная');
            $this->ShowMessage("Ошибка!", "Товар не был найден!");
        }

        $this->set(['row'=>$post]);
    }
    
}
