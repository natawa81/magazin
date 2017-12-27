<?php

namespace app\controllers\admin;

use app\models\admin\Main;
use vendor\core\FileUploader;
use vendor\modules\Validator;

class MainController extends AppController
{

    private $attributes = [
        'title' => 'Заголовок',
        'author' => 'Автор',
        'publisher' => 'Издатель',
        'price' => 'Цена',
        'count' => 'Количество',
        'lang' => 'Язык',
        'bookFormat' => 'Переплёт',
        'format' => 'Формат',
        'description' => 'Описание',
        'pages' => 'Кол-во страниц',
        'category' => 'Категория',
        'year' => 'Год'
    ];

    public function __construct($route)
    {
        parent::__construct($route);

        $this->setModel(new Main());
    }

    public function indexAction() {

        if (isset($_GET['remove'])) {
            if (!$this->user->access('items')) {
                RefreshPage(url('/admin'));
            }
            $id = intval($_GET['remove']);
            $this->DB()->delete($id);
            RefreshPage(url('/admin'));
        }

        $execute = [];

        $num = 12;
        $page = isset($_GET['page']) ? abs(intval($_GET['page'])) : 1;
        $result = $this->DB()->BindSQL("SELECT COUNT(*) as `count` FROM `items` ORDER BY `id` DESC", $execute, true);
        $posts = $result[0]['count'];
        $total = intval(($posts - 1) / $num) + 1;
        $page = min($page, $total);
        $start = $page * $num - $num;


        $execute[] = [$start, \PDO::PARAM_INT];
        $execute[] = [$num, \PDO::PARAM_INT];

        $posts = $this->DB()->BindSQL("SELECT * FROM `items` ORDER BY `id` DESC LIMIT ?, ?", $execute, true);
        $pgn = createPagination($page,1,$total,2,'/admin?page={page}');

        $this->set(compact('posts', 'pgn'));
    }

    public function addAction() {

        if (!$this->user->access('items')) {
            RefreshPage(url('/admin'));
        }
        $category = $this->DB()->GetCategoryList();

        if (isset($_POST['add'])) {
            $params = [];
            foreach ($this->attributes as $attr => $title) {
                $params[$attr] = $attr != 'description' ? strip_tags( $_POST[$attr] ) : stripOtherHTML($_POST[$attr]);
            }

            $v = new Validator($params);

            $v->rule('required', array_keys($this->attributes));
            $v->rule('integer', ['count', 'pages', 'year']);
            $v->rule('in','category', array_keys($category));
            $v->rule('numeric', 'price');


            $errors = [];

            $file = new FileUploader($_FILES['image'], md5(mt_rand(0,1999999).time()));
            $file->setExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $file->setDir('/public/upload/images');
            $file->upload();
            if ($file->isErrors()) {
                $errors = $file->getErrors();
            } else {
                $params['image'] = $file->GetName();
            }
            if (!$v->validate())
                $errors = array_merge($errors, $v->errors());

            if (count($errors) > 0) {
                $this->parseMessage($errors);
            } else {
                $this->DB()->Insert($params);
                $id = $this->DB()->LastID();
                RefreshPage(url('/admin/main/edit-'.$id));
            }

        }
        $this->set(compact('category'));
    }

    public function editAction() {

        if (!$this->user->access('items')) {
            RefreshPage(url('/admin'));
        }
        $id = $this->route['id'];
        $row = $this->DB()->findOne($id);
        $category = $this->DB()->GetCategoryList();

        if (isset($_POST['edit'])) {
            $params = [];
            foreach ($this->attributes as $attr => $title) {
                $params[$attr] = $attr != 'description' ? strip_tags( $_POST[$attr] ) : stripOtherHTML($_POST[$attr]);
            }

            $v = new Validator($params);

            $v->rule('required', array_keys($this->attributes));
            $v->rule('integer', ['count', 'pages', 'year']);
            $v->rule('in','category', array_keys($category));
            $v->rule('numeric', 'price');


            $errors = [];


            if (!empty($_FILES['image'])) {
                $file = new FileUploader($_FILES['image'], md5(mt_rand(0,1999999).time()));
                $file->setExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $file->setDir('/public/upload/images');
                $file->upload();
                if ($file->isErrors()) {
                    $errors [] = $file->getErrors();
                } else {
                    unlink(ROOT . '/public/upload/images/'.$row['image']);
                    $this->DB()->updateAttributes(['image' => $file->GetName()], $id);
                }
            }

            if ($v->validate()) {
                $this->DB()->updateAttributes($params, $id);
                RefreshPage();
            } else {
                $errors = array_merge($errors, $v->errors());
            }
            if (count($errors) > 0) {
                $this->parseMessage($errors);
            }

        }

        $this->set(compact('row','category'));
    }


    public function parseMessage ($errors) {
        $messages = [];
        foreach ($errors as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $item) {
                    $messages[] = str_replace(mb_strtolower($key), '`' . $this->attributes[$key] . '`', mb_strtolower($item));
                }
            } else {
                $messages[] = mb_strtolower($val);
            }
        }
        $this->ShowMessage('Ошибка!', implode("<br />",$messages));
    }

}