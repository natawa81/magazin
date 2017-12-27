<?php
namespace vendor\core;

/**
 * Description of Cache
 *
 */
class Cache {
    
    protected $private_key = 'KEY_CACHE_PRIVATE';

    protected $limit = 15; // Лимит удаления файлов
    
    public function __construct() {
        $files = scandir(CACHE);
        unset($files[0],$files[1]);
        sort($files);
        $limit = count($files) > $this->limit ? $this->limit : count($files);
        for ($i = 0; $i < $limit; $i++) {
            $file = pathinfo($files[$i], PATHINFO_FILENAME);
            $cache = $this->unserializeFile($file,false);
            if ($cache && $cache['time'] <= time()) {
                unlink($this->file($file,false));
            }
        }
    }

    public function set ($file, $text, $lifetime = 300) {
        $data['time'] = time() + $lifetime;
        $data['text'] =  $text;
        if (file_put_contents($this->file($file), serialize($data))){
            return true;
        } else {
            return false;
        }
    }
    
    protected function file($file,$md5 = true) {
        $file = $md5 ? md5(sha1($file).$this->private_key) : $file;
        return CACHE.'/'.$file.'.txt';
    }
    
    protected function unserializeFile ($file,$md5 = true) {
        if (!file_exists($this->file($file,$md5))) return false;
        $cache = file_get_contents($this->file($file,$md5));
        $cache = unserialize($cache);
        return $cache;
    }

    public function get ($file) {
        if (file_exists($this->file($file))) {
            $cache = $this->unserializeFile($file);
            if ($cache['time'] <= time()) {
                unlink($this->file($file));
            }
            return $cache['text'];
        } return false;
    }
    
}
