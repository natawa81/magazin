<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vendor\core;

class Registry {
    
    /**
     * Копия класса Registry
     * @var Registry
     */
    protected static $instance;
    
    /**
     * список всех обьектов
     * @var array
     */
    protected static $objects = [];
    
    public function __construct() {
        global $config;
        foreach ($config['class_components'] as $key => $value) {
            if (is_array($value)) {
                self::$objects[$key] = new $value[0]($value[1]);
            } else if (is_object($value)) {
                self::$objects[$key] = $value;
            }else self::$objects[$key] = new $value;
        }
    }
    
    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    public function __get($name){
        if(is_object(self::$objects[$name])) {
            return self::$objects[$name];
        }
    }
    
    public function __set($name, $value) {
        if (is_object($value) && !isset(self::$objects[$name])) {
            self::$objects[$name] = $value;
        }
    }
    
}
