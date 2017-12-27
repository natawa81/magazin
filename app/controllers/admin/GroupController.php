<?php

namespace app\controllers\admin;


class GroupController extends AppController
{

    public $rights = [
        'settings' => 'Настройки',
        'items' => 'Товары',
        'category' => 'Категории',
        'order' => 'Заказы',
        'groups' => 'Группы',
        'users' => 'Пользователи',
        'page' => 'Страницы'
    ];

    public function indexAction () {
        if (!$this->user->access('groups')) {
            RefreshPage(url('/admin'));
        }
        $vars = [];
        $vars['rights'] = $this->rights;

        $groups = $this->DB()->findAllByPK();

        if (isset($_POST['add'])) {
            $arr = [];
            $arr ['startgroup'] = isset($_POST['startgroup']) ? 1 : 0;
            $arr ['title'] = trim(strip_tags($_POST['title']));
            if (empty($arr['title'])) {
                $this->ShowMessage("Ошибка!", "Поле `Название` должно быть заполнено!");
            } else {
                $right = isset($_POST['rights']) ? $_POST['rights'] : [];

                foreach ($this->rights as $key => $r) {
                    $arr['rights'][$key] = isset($right[$key]) ? 1 : 0;
                }

                $arr['rights'] = serialize($arr['rights']);
                $this->DB()->InsertRights($arr);
                RefreshPage();
            }
        } else if (isset($_POST['save'])) {
            $messages = [];
            foreach ($_POST['update'] as $id => $item) {
                if (isset($groups[$id])) {
                    if (isset($item['remove'])) {
                        $this->DB()->DeleteRights($id);
                    } else {
                        $arr = [];
                        $arr['title'] = trim(strip_tags($item['title']));
                        if (empty($arr['title'])) {
                            $messages[] = 'У группы `'.$groups[$id]['title'].'` поле `Название` не должно быть пусто!';
                        } else {
                            $arr['startgroup'] = intval($_POST['startgroup']) == $id ? 1 : 0;
                            $right = isset($item['rights']) ? $item['rights'] : [];

                            foreach ($this->rights as $key => $r) {
                                $arr['rights'][$key] = isset($right[$key]) ? 1 : 0;
                            }
                            $arr['rights'] = serialize($arr['rights']);
                            $this->DB()->UpdateRights($arr, $groups[$id]['id']);
                        }
                    }
                }
            }
            if (count($messages) > 0) {
                $this->ShowMessage("Ошибка!", "<ul><li>" . implode("</li><li>", $messages) . "</li></ul>");
            } else {
                RefreshPage();
            }
        }

        $vars['groups'] = $groups;


        $this->set($vars);
    }
}