<?php

namespace vendor\core;

/**
 * Description of Controller
 *
 */
abstract class Controller {
    
    /**
     * текущий маршрут и параметры (controller, action, params)
     * @var array
     */
    public $route = [];
    
    /**
     * вид
     * @var string
     */
    public $view;
    
    /**
     * текущий шаблон
     * @var string
     */
    public $layout;
    
    /**
     * пользовательские данные
     * @var array
     */
    public $vars = [];
    
    public $defVars = [];
    
    /**
     * Модель контроллера
     * @var Model
     */
    public $model;

    public $title;
    
    public $message = [];


    public function __construct($route) {
        $this->route = $route;
        $this->view = $route['action'];
        $model = '\app\models\\'.$route['prefix'].$route['controller'];
        if (class_exists($model)) {
            $this->model = new $model;
        } else {
        }


        $this->defVars['meta_keywords'] = '';
        $this->defVars['meta_description'] = '';
    }


    public function title ($title) {
        $this->title = $title;
    }

    public function setMeta($keywords, $description) {
        $this->defVars['meta_keywords'] = $keywords;
        $this->defVars['meta_description'] = $description;
    }

    public function __get($name) {
//        debug($this->defVars);
        return $this->defVars[$name];
    }

    public function __set($name, $value)
    {
        $this->defVars[$name] = $value;
    }

    function trimToDot($string) {
        $pos = strrpos($string, '.');
        if (!$pos) {
            return $string;
        }
        return substr($string, 0, $pos);
    }
    
    public function ShowMessage ($title,$text = "") {
        if (is_array($text)) {
            $text = "<ul><li>".implode("</li><li>", $text)."</li></ul>";
        }
        $this->message = ['title'=>$title,'text'=>$text];
    }
    
    public static function GlobalMessage($title, $text = "") {
        self::ShowMessage($title,$text);
    }
    
    public function getView(){
        $vObj = new View($this->route, $this->layout, $this->view,$this->message);
        foreach ($this->defVars as $key => $value) {
            $this->vars[$key] = $value;
        }
        $vObj->setTitle($this->title);
        $vObj->render($this->vars);
    }
    
    public function setVar($key,$value) {
        $this->vars[$key]=$value;
    }
    
    /**
     * Стандартные переменные
     * @param array $vars
     */
    public function setDefault($vars) {
        foreach ($vars as $key => $var) $this->defVars[$key] = $var;
    }

    public function set($vars){
        $this->vars = $vars;
    }

    public function setModel($model){
        $this->model = $model;
    }

    public function DB () {
        if ($this->model == null) {
            throw new \Exception('Модель класса '.$this->route['controller'].'Controller не найдена!');
        }
        return $this->model;
    }

    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function loadView ($view, $vars = []) {
        extract($vars);
        require APP."/views/{$this->route['controller']}/{$view}.php";
    }
}
