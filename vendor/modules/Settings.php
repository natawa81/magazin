<?php
namespace vendor\modules;


use vendor\core\Db;

class Settings {
    protected $db;
    protected $settings = [];

    protected static $instance;


    public function __construct() {
        $this->db = Db::instance();

        $query = $this->db->query("SELECT * FROM `settings`");
        foreach ($query as $item) {
            $this->settings[$item['param']] = unserialize($item['value']);
        }
    }

    public static function instance() {
        if (self::$instance == null)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __get($name) {
        return isset($this->settings[$name]) ? $this->settings[$name] : [];
    }

    public function __set($item, $val) {
        if (!is_array($val))
            $val = [$val];

        $this->db->execute("INSERT INTO `settings` (`param`, `value`) VALUES (?, ?) 
        ON DUPLICATE KEY UPDATE `value` = VALUES (`value`)", [$item, serialize($val)]);
        $this->settings[$item] = $val;
    }
}