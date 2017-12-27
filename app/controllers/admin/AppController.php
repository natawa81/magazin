<?php

namespace app\controllers\admin;

use vendor\core\Controller;

class AppController extends Controller
{
    public $layout = 'admin';
    public function __construct($route)
    {
        parent::__construct($route);
        $this->user = new \vendor\core\User;
        if (!$this->user->isAdmin())
            RefreshPage(url('/'));
    }
}