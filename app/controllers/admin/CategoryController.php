<?php
namespace app\controllers\admin;


use app\models\admin\Category;

class CategoryController extends AppController {

    public function __construct($route)
    {
        parent::__construct($route);
        $this->setModel(new Category());
    }

    public function indexAction () {

        if (!$this->user->access('category')) {
            RefreshPage(url('/admin'));
        }
        $category = $this->DB()->findAll();

        if (isset($_GET['remove'])) {
            $id = intval($_GET['remove']);
            if ($this->DB()->findOne($id)) {
                $this->DB()->delete($id);
                $this->DB()->query("UPDATE `category` SET `parent` = '0' WHERE `parent` = ?", [$id]);
                $this->DB()->query("UPDATE `items` SET `category` = '0' WHERE `category` = ?", [$id]);
                RefreshPage(url('/admin/category'));
            }
        }

        $sort_category = [];

        foreach ($category as $item) {
            $sort_category[$item['parent']][] = $item;
        }

        $this->set(['list' => $this->SortList($sort_category)]);

    }

    public function addAction() {
        if (!$this->user->access('category')) {
            RefreshPage(url('/admin'));
        }
        $items = $this->DB()->findAll();
        $sort_items = [];
        foreach ($items as $val) {
            $sort_items[$val['id']] = $val;
        }

        if (isset($_POST['add'])) {
            $params = [];
            $params['title'] = trim(strip_tags($_POST['title']));
            $params['parent'] = intval($_POST['parent']);
            $params['parent'] = isset($sort_items[$params['parent']]) ? $params['parent'] : 0;

            if (!empty($params['title'])) {
                $this->DB()->Insert($params);
                $id = $this->DB()->LastID();
                RefreshPage(url('/admin/category/edit-'.$id));
            } else {
                $this->ShowMessage('Ошибка!', '`Заголовок` обязательно к заполнению');
            }
        }

        $this->set(compact('items'));
    }

    public function editAction(){
        if (!$this->user->access('category')) {
            RefreshPage(url('/admin'));
        }
        $id = intval($this->route['id']);
        $item = $this->DB()->findOne($id);
        if ($item) {
            $items = $this->DB()->findAll("ASC", "*", "`id` != ?",[$id]);
            $sort_items = [];
            foreach ($items as $val) {
                $sort_items[$val['id']] = $val;
            }

            if (isset($_POST['edit'])) {
                $params = [];
                $params['title'] = trim(strip_tags($_POST['title']));
                $params['parent'] = intval($_POST['parent']);
                $params['parent'] = isset($sort_items[$params['parent']]) ? $params['parent'] : 0;

                if (!empty($params['title'])) {
                    $this->DB()->updateAttributes($params, $id);
                    RefreshPage();
                } else {
                    $this->ShowMessage('Ошибка!', '`Заголовок` обязательно к заполнению');
                }

            }

            $this->set(compact('item', 'items'));
        }
    }

    public function SortList($array, $parent = 0, $level = 0) {
        $arr = [];
        if (isset($array[$parent])) {
            foreach ($array[$parent] as $item) {
                $item['level'] = $level;
                $arr[] = $item;

                $level ++;
                $arr = array_merge($arr, $this->SortList($array,$item['id'], $level));
                $level --;
            }
        }
        return $arr;
    }
}