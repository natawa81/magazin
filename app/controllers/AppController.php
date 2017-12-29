<?php

namespace app\controllers;

use app\models\Page;
use \vendor\core\Db;
use \vendor\modules\SiteMapGenerator;

/**
 * Description of App
 *
 */
class AppController extends \vendor\core\Controller
{
    public $category = null;
//    public $user = null;
    public $cart = [];

    public function __construct($route)
    {
        parent::__construct($route);
        $this->CompleteCategory();

        $this->user = new \vendor\core\User;
        $this->filters_link = isset($route['category']) ? '/category-' . $route['category'] : '/';


        $this->loadFilters();
        $this->loadCart();

        $this->in_cart = 0;
        foreach ($this->cart as $i) {
            $this->in_cart += $i;
        }
        $sitemap = ROOT . '/public/sitemap.xml';
        $change = true;
        if (file_exists($sitemap)) {
            $ftime = filemtime($sitemap);
            if ($ftime > time() - 3600) {
                $change = false;
            }
        }
        if ($change) {
            $map = new SiteMapGenerator;
            $cfg = GetConfig();
            $map->set_last_mod_static(date('Y-m-d'));
            $map->set_base_url(rtrim($cfg['url']) . '/');
            $dir = diff_scandir(APP . '/controllers', '', ['php']);
            $dir = array_diff($dir, ['AppController.php', 'admin', 'PageController.php']);
            foreach ($dir as $val) {
                $classname = pathinfo($val, PATHINFO_FILENAME);
                $name = str_replace('controller', '', mb_strtolower($classname));
                $map->addSite($name, ['changefreq' => 'never']);
            }
            $model = new Page();
            $pages = $model->findAll("DESC", "`url`");
            foreach ($pages as $page) {
                $map->addSite('page/'.$page['url']);
            }
            file_put_contents($sitemap, $map->generate());
        }
    }

    public function loadFilters()
    {
        $db = Db::instance();
        $data = $db->queryRow("SELECT MIN(`year`) as `min_year`, MAX(`year`) as `max_year`, MIN(`pages`) as `min_pages`, MAX(`pages`) as `max_pages` FROM `items`");
        $this->data_filters = $data;

        $min_year = isset($_GET['min_year']) ? max(intval($_GET['min_year']), $data['min_year']) : $data['min_year'];
        $max_year = isset($_GET['max_year']) ? min(intval($_GET['max_year']), $data['max_year']) : $data['max_year'];

        $min_pages = isset($_GET['min_pages']) ? max(intval($_GET['min_pages']), $data['min_pages']) : $data['min_pages'];
        $max_pages = isset($_GET['max_pages']) ? min(intval($_GET['max_pages']), $data['max_pages']) : $data['max_pages'];


        $this->data_filters_value = [
            'query_name' => isset($_GET['query_name']) ? trim(strip_tags($_GET['query_name'])) : '',
            'query_author' => isset($_GET['query_author']) ? trim(strip_tags($_GET['query_author'])) : '',
            'min_year' => $min_year,
            'max_year' => $max_year,
            'min_pages' => $min_pages,
            'max_pages' => $max_pages,
        ];

    }

    public function saveCart()
    {
        if ($this->user->login()) {
            $this->DB()->query("INSERT INTO `cart` (`user_id`, `data`) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE `data` = VALUES (`data`)", [$this->user->id(), serialize($this->cart)]);
            HttpOnlyCookie('cart', serialize($this->cart));
        } else {
            HttpOnlyCookie('cart', serialize($this->cart));
        }
    }

    public function loadCart()
    {
        $db = Db::instance();
        if ($this->user->login()) {
            $data = $db->queryRow("SELECT * FROM `cart` WHERE `user_id` = ?", [$this->user->id()]);
            if ($data) {
                if (empty($data['data'])) $this->cart = [];
                else $this->cart = unserialize($data['data']);
            }
        } else {
            if (!isset($_COOKIE['cart'])) {
                $this->cart = [];
            } else {
                $this->cart = unserialize($_COOKIE['cart']);
            }
        }
    }

    public function CompleteCategory()
    {
        global $app;
        $db = Db::instance();
        $cache = $app->cache->get('main_category_menu');
        if ($cache == false) {
            $rows = $db->query("SELECT * FROM `category`");
            $this->category = new \vendor\modules\Category($rows);
            $html = $this->category->Show();
            $this->main_category = $html;
        }
    }
}