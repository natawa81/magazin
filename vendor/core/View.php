<?php

namespace vendor\core;

/**
 * Description of View
 *
 */
class View {
    
    /**
     * текущий маршрут и параметры (controller, action, params)
     * @var array
     */
    public $route = [];
    
    /**
     * текущий вид
     * @var string
     */
    public $view;
    
    /**
     * текущий шаблон
     * @var string
     */
    public $layout;

    protected $title = "";
    protected $message = [];

    public function __construct($route, $layout = '', $view = '', $message = []) {
        $this->route = $route;
        if($layout === false){
            $this->layout = false;
        }else{
            $this->layout = $layout ?: LAYOUT;
        }
        $this->view = $view;
        $this->message = $message;
    }

    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function render($vars){
        $this->route['prefix'] = str_replace('\\', '/', $this->route['prefix']);
        $vars ['title'] = $this->title;
        if(is_array($vars)) extract($vars);

        $file_view = APP . "/views/{$this->route['prefix']}{$this->route['controller']}/{$this->view}.php";
        ob_start();
        if(is_file($file_view)){
            require $file_view;
        }else{
            echo "<p>Не найден вид <b>$file_view</b></p>";
        }
        $content = ob_get_clean();
        
        if(false !== $this->layout){
            $file_layout = APP . "/views/layouts/{$this->layout}.php";
            if(is_file($file_layout)){
                $message = $this->message;
                require $file_layout;
            }else{
                echo "<p>Не найден шаблон <b>$file_layout</b></p>";
            }
        }
    }
    
}
