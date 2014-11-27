<?php
class AppSettings {
    protected static $_instance;
    private $file = "config.ini"; // имя файла с настройками
    private $settings = array();
    
    private function __construct() {
        if (file_exists($this->file)) {
            $this->settings = parse_ini_file($this->file);
        }
    }
    
    private function __clone() {      
    }
    
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function get($val) {
        if (in_array($val, $this->settings)) {
            return $this->settings[$val];
        }
    }
}