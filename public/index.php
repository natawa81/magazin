<?php
@session_start();

error_reporting(-1);

use vendor\core\Router;
use vendor\modules\Settings;

$query = rtrim($_SERVER['QUERY_STRING'], '/');

define('WWW', __DIR__);
define('ROOT', dirname(__DIR__));
define('CORE', ROOT . '/vendor/core');
define('APP', ROOT . '/app');
define('CACHE', ROOT.'/cache');
define('LAYOUT', 'default');

$config = require_once ROOT . '/config/config.php';
require ROOT . '/vendor/libs/functions.php';

spl_autoload_register(function($class){
    $file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
    if(is_file($file)){
        require_once $file;
    }
});

$app = vendor\core\Registry::instance();


vendor\modules\Validator::langDir(ROOT .'/vendor/modules/Validator/lang');
vendor\modules\Validator::lang('ru');


Router::add('^admin$', ['controller'=>'Admin','namespace'=>'app\admin']);


Router::add('^category-(?P<category>[0-9]+)$', ['controller' => 'Main','action'=>'index']);
Router::add('^view/(?P<id>[0-9]+)$', ['controller' => 'Main','action'=>'view']);
Router::add('^page/(?P<page>.+?)$', ['controller' => 'Page','action'=>'index']);

Router::add('^admin$',['controller' => 'Main', 'action' => 'index', 'prefix' => 'admin']);

Router::add('^admin/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$', ['prefix' => 'admin']);
Router::add('^admin/main/edit-(?P<id>[0-9]+)$', ['controller'=>'Main','action' => 'edit','prefix'=>'admin']);
Router::add('^admin/category/edit-(?P<id>[0-9]+)$', ['controller'=>'Category','action' => 'edit','prefix'=>'admin']);

Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');


Router::dispatch($query);