<?php

namespace vendor\core;

class FileUploader {
    
    /**
     * Максимальный размер файла
     * @var float|int
     */
    public $max_size = 1; // * 1048576
    /**
     * Создавать дерикторию для фалов автоматически ( дочерние и родительские в счёт )
     * @var bool 
     */
    public $dir_auto_create = false;
    /**
     * Разрешить доступ на загрузку массовых файлов
     * @var bool 
     */
    public $can_multiple = true;
    
    protected $rename = '';
    protected $finfo = [];
    protected $multiple = false;
    protected $extensions = ['png','jpg','gif'];
    protected $upload_dir = '';
    
    protected $errors = [];
    
    protected $max_width_height = [];
    protected $min_width_height = [];
    protected $array_sizes = [];
    
    protected $error_messages = [
        UPLOAD_ERR_INI_SIZE => 'Размер файла больше разрешенного директивой upload_max_filesize в php.ini',
        UPLOAD_ERR_FORM_SIZE => 'Размер файла превышает указанное значение в MAX_FILE_SIZE',
        UPLOAD_ERR_PARTIAL => 'Файл был загружен только частично',
        UPLOAD_ERR_NO_FILE => 'Не был выбран файл для загрузки',
        UPLOAD_ERR_NO_TMP_DIR => 'Не найдена папка для временных файлов',
        UPLOAD_ERR_CANT_WRITE => 'Ошибка записи файла на диск'
    ];

    public function __construct($finfo,$rename = '') {
        $this->finfo = $finfo;
        $this->multiple = is_array($this->finfo['name']);
        $this->rename = $rename;
    }
    
    public function getErrors($id = null) {
        if ($id != null) {
            return isset($this->errors[$id]) ? $this->errors[$id] : '';
        } else {
            return $this->errors;
        }
    }
    
    public function isErrors () {
        return (bool)count($this->errors);
    }
    
    public function setDir ($dir) {
        $this->upload_dir = ROOT .'/'. ltrim(rtrim($dir,'/'),'/');
    }
    
    /**
     * Максимальная ширина и высота загружаемого изображения
     * @param int $width
     * @param int $height
     */
    public function MaxSize($width,$height) {
        $this->max_width_height = [$width,$height];
    }
    
    /**
     * минимальная ширина и высота загружаемого изображения
     * @param int $width
     * @param int $height
     */
    public function MinSize($width,$height) {
        $this->min_width_height = [$width,$height];
    }
    
    /**
     * Разрешения файлов
     * @param array $extensions
     */
    public function setExtensions ($extensions) {
        if (is_array($extensions))$this->extensions = $extensions;
    }
    
    /**
     * Выбор нового имени файла [В случае если мульти-загрузка файлы будут принимать свои имена]
     * @param string $name
     */
    public function setName($name) {
        $this->rename = $name;
    }
    
    /**
     * Массив размеров доступных для загрузок
     * @param array $sizes
     */
    public function arraySizes ($sizes) {
        $this->array_sizes = $sizes;
    }
    
    protected function getMultipleFile($key) {
        $file = [];
        foreach ($this->finfo as $id => $array) {
            $file[$id] = $array[$key];
        }
        
        return $file;
    }
    
    protected function fileType($type) {
        switch ($type) {
            case 'image/jpeg': return 'jpg';
                break;
            case 'image/gif': return 'gif';
                break;
            case 'image/png': return 'png';
                break;
            case 'image/bmp': return 'bmp';
                break;
            case 'image/x-windows-bmp': return 'bmp';
                break;
            case 'application/pdf': return 'pdf';
                break;
            case 'application/zip': return 'zip';
                break;
            case 'application/rar': return 'rar';
                break;
            case 'application/x-gzip': return 'gz';
                break;
            case 'application/x-bzip': return 'bz';
                break;
            case 'application/x-bzip2': return 'bz2';
                break;
            case 'application/msword': return 'doc';
                break;
            case 'application/x-shockwave-flash': return 'swf';
                break;
            default: return false;
                break;
        }
    }
    
    protected function askSize ($tmp_name) {
        $mx_img = $mn_img = true;
        if (count($this->max_width_height) == 2 || count($this->min_width_height) == 2){
            $size = getimagesize($tmp_name);
            if (count($this->max_width_height) == 2) {
                $mx_img = false;
                if ($size[0] <= $this->max_width_height[0] && $size[1] <= $this->max_width_height[1]) {
                    $mx_img = true;
                }
            }
            if (count($this->min_width_height) == 2) {
                $mn_img = false;
                if ($size[0] >= $this->min_width_height[0] && $size[1] >= $this->min_width_height[1]) {
                    $mn_img = true;
                }
            }
            if (count($this->array_sizes)) {
                $mx_img = $mn_img = false;
                if (in_array([$size[0],$size[1]], $this->array_sizes)) {
                    $mx_img = $mn_img = true;
                }
            }
        }
        
        return ($mx_img && $mn_img);
    }
    
    protected function askDir ()  {
        if (!is_dir($this->upload_dir)) {
            if ($this->dir_auto_create) {
                $explode = explode("/", str_replace(ROOT.'/','',$this->upload_dir));
                $create = ROOT;
                foreach ($explode as $value){
                    $create .= '/'.$value;
                    @mkdir($create,0777);
                }
            } else {
                throw new \vendor\SelfException('Директория <b>'.  str_replace(ROOT, '', $this->upload_dir).'</b> отсутствует');
            }
        } else {

        }
    }

    public $completeFile = '';

    public function GetName () {
        return $this->completeFile;
    }

    protected function askFile ($file, $multi = false) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            if (in_array($this->fileType($file['type']), $this->extensions)) {
                if ($file['size'] <= $this->max_size*1048576) {
                    if ($this->askSize($file['tmp_name'])) {
                        if (is_uploaded_file($file['tmp_name'])) {
                            $end = pathinfo($file['name'],PATHINFO_EXTENSION);
                            $upload = $this->upload_dir . '/'. ( !empty($this->rename) && ! $multi ? $this->rename.'.'.$end : $file['name'] );
                            $this->completeFile = ( !empty($this->rename) && ! $multi ? $this->rename.'.'.$end : $file['name'] );
                            if (move_uploaded_file($file['tmp_name'],$upload)) {
                                return true;
                            } else $this->errors[] = "Файл <b>{$file['name']}</b> не был загружен";
                        } else $this->errors[] = "Файл <b>{$file['name']}</b> не был загружен";
                    } else $this->errors[] = "Неправильная ширина и высота файла <b>{$file['name']}</b>";
                } else $this->errorrs[] = "Файл <b>{$file['name']}</b> слишком большой";
            } else $this->errors[] = "Файл <b>{$file['name']}</b> не имеет правильное разрешение";
        } else $this->errors[] = $this->error_messages[$file['error']];
    }

    public function upload () {
        $this->askDir();
        if ($this->multiple && $this->can_multiple) {
            for ($i = 0; $i < count($this->finfo['name']); $i++) {
                $file = $this->getMultipleFile($i);
                $this->askFile($file, true);
            }
        } else if (! $this->multiple) {
            $this->askFile($this->finfo);
        } else if ($this->multiple && !$this->can_multiple) {
            
        }
        
        
        
//        getimagesize($filename)
    }
    
}
