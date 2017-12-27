<?php

namespace app\controllers;


class PageController extends AppController
{
    public function indexAction() {
        $page = trim(strip_tags($this->route['page']));
        if (!empty($page)) {
            $row = $this->DB()->findOne($page, 'url');
            if ($row) {
                $row['pagename'] = $row['title'];
                unset($row['title']);
                $this->set($row);
                $this->setMeta($row['keywords'], $row['description']);
                return;
            }
        }
        $this->ShowMessage('Страница не найдена!');
    }

}