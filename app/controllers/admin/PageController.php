<?php

namespace app\controllers\admin;


use vendor\modules\Validator;

class PageController extends AppController
{

    private $fields = [
        'url' => 'УРЛ',
        'title' => 'Заголовок',
        'content' => 'Контент',
        'keywords' => 'Ключевые слова',
        'description' => 'Описание'
    ];

    public function indexAction () {
        $pages = $this->DB()->findAllByPK();
        if (isset($_GET['remove'])) {
            $id = intval($_GET['remove']);
            if (isset($pages)) {
                $this->DB()->delete($id);
                RefreshPage(url('/admin/page'));
            }
        }
        $this->set(compact('pages'));
    }

    public function editAction () {
        $id = intval($_GET['id']);
        $page = $this->DB()->findOne($id);
        if (!$page) {
            $this->ShowMessage('Ошибка!', 'Данная страница не найдена!');
            return;
        }
        if (isset($_POST['edit'])) {
            $arr = [];
            if (isset($_POST['url'])) {
                if (!empty($_POST['url'])) {
                    $arr['url'] = toCpu($_POST['url']);
                }
            }
            if (!isset($arr['url'])) $arr['url'] = toCpu($_POST['title']);
            $arr['title'] = tst($_POST['title']);
            $arr['content'] = trim($_POST['content']);
            $arr['keywords'] = tst($_POST['keywords']);
            $arr['description'] = tst($_POST['description']);
            $text = tst($arr['content']);
            if (isset($_POST['auto-keywords'])) {
                $arr['keywords'] = getKeywords($text);
            }
            if (isset($_POST['auto-description'])) {
                $arr['description'] = getDescription($text);
            }
            $this->DB()->updatePage($arr, $id);
            RefreshPage();
        }
        $this->set(compact('page'));
    }


    public function addAction () {
        if (isset($_POST['add'])) {
            $arr = [];
            if (isset($_POST['url'])) {
                if (!empty($_POST['url'])) {
                    $arr['url'] = toCpu($_POST['url']);
                }
            }
            if (!isset($arr['url'])) $arr['url'] = toCpu($_POST['title']);
            $arr['title'] = tst($_POST['title']);
            $arr['content'] = trim($_POST['content']);
            $arr['keywords'] = tst($_POST['keywords']);
            $arr['description'] = tst($_POST['description']);
            $text = tst($arr['content']);
            if (isset($_POST['auto-keywords']) || empty($arr['keywords'])) {
                $arr['keywords'] = getKeywords($text);
            }
            if (isset($_POST['auto-description']) || empty($arr['description'])) {
                $arr['description'] = getDescription($text);
            }
            $v = new Validator($arr);
            $v->rule('required', array_keys($arr));
            if (!$v->validate()) {
                $errors = parseValidatorErrors($v->errors(), $this->fields);
                $this->ShowMessage('Ошибка!', $errors);
            } else {
                $find = $this->DB()->findOne($arr['url'], 'url');
                if ($find) {
                    $this->ShowMessage('Ошибка!', 'Страница с таким УРЛ уже существует!');
                } else {
                    $this->DB()->Insert($arr);
                    RefreshPage(url('/admin/page'));
                }
            }
        }
    }
}