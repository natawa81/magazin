<?php

function debug($arr){
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

function getDescription ($text) {
    $text = preg_replace('/(\s+)/ui', ' ', $text);
    preg_match_all('/([A-zА-я.,\s]+)/ui', $text, $matches);
    $text = implode(" ", $matches[0]);
    $text = trim(lib_substr($text, 140));
    return $text;
}

function diff_scandir ($path, $prefix = '', $ext = []) {
    $arr = array_diff(scandir($path), ['..','.']);
    sort($arr);
    if ($prefix != '' || count($ext) > 0) {
        for ($i = 0; $i < count($arr); $i++) {
            $e = mb_strtolower(pathinfo($arr[$i], PATHINFO_EXTENSION));
            if (in_array($e, $ext)) {
                $arr[$i] = $prefix . $arr[$i];
            }
        }
    }
    return $arr;
}

function getKeywords ($text) {
    preg_match_all('/([A-zА-я]{4,})/ui', $text, $matches);
    $words = $matches[0];
    $count = min(count($words), 15);
    $return = [];
    for ($i = 0; $i < $count; $i++) {
        $word = trim($words[mt_rand(0, count($words) - 1)]);
        if (!in_array($word, $return))
        $return[] = $word;
    }

    return implode(",", $return);
}

function parseValidatorErrors ($errors, $fields = []) {
    $arr = [];
    foreach ($errors as $key => $error_list) {
        $key = mb_strtolower($key);
        foreach ($error_list as $error) {
            if (isset($fields[$key])) {
                $k = str_replace('_', ' ', $key);
                $arr[] = preg_replace('/^('.$k.')/iu', '`' . $fields[$key] . '`', mb_strtolower($error));
            } else {
                $arr[] = $error;
            }
        }
    }
    return $arr;
}


function tst ($text) {return trim(strip_tags($text));}

function GetByKeys () {
    $key = func_get_args();
    $array = $key[0];
    $return = [];
    for ($i = 1; $i < count($key); $i++)
        if (isset($array[$key[$i]])) $return[$key[$i]] = $array[$key[$i]];
    
    return $return;
}

/**
 * Поиск производится следующим образом: массив, [ключ=>поиск значения], поиск только одного массива
 * @param type $array
 * @param type $params
 * @param type $findOne
 */
function FindByParam ($array,$params = [],$findOne = false) {
    $list_contains = [];
    
    foreach ($array as $row) {
        if (count(array_intersect_assoc($row,$params)) == count($params)){
            if ($findOne) return $row;
            else $list_contains[] = $row;
        }
    }
    
    return $list_contains;
}

function GetConfig ($prefix = '') {
    if (!isset($GLOBALS['configs']))
        $GLOBALS['configs'] = require ROOT . '/config/config'.(!empty($prefix)?'_'.$prefix:'').'.php';
    return $GLOBALS['configs'];
}


/**
 * Unix-дату в системную, заложеную в config.php файле
 * @global type $config
 * @param type $date
 * @return type
 */
function DateFormat($date) {
    global $config;
    if (!$config) $config = GetConfig();
    return date($config['date_format'],$date);
}

/**
 * перезагрузить страницу
 */
function RefreshPage ($url = '') {
    if (empty($url)) $url = $_SERVER['REQUEST_URI'];
    header("Location: ".$url);
}

function HttpOnlyCookie ($name, $value) {
    setcookie($name,$value,0,'/', $_SERVER['HTTP_HOST'], FALSE, TRUE);
}


function url($url) {
    global $config;
    return rtrim($config['url'],'/').'/'.ltrim($url,'/');
}

function stripOtherHTML ($html) {
    $cfg = GetConfig();
    return strip_tags($html, $cfg['allowed_tags']);
}

function lib_substr ($str, $words) {
    return mb_substr($str, 0, $words);
}
function urldecode_utf8($string)
{
    $string = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($string));
    return html_entity_decode($string,null,'UTF-8');
}

function utf8_to_win1251 ($text) {
    return iconv("utf-8", "windows-1251", $text);
}
function win1251_to_utf8 ($text) {
    return iconv("windows-1251", "utf-8", $text);
}

function createPagination($current, $min = 1, $max, $size, $pattern) {
    $current = intval($current);
    if ($current <= 0) $current = 1;
    if ($current - $size < $min) $left = $min;
    else $left = $current - $size;

    if ($current + $size > $max ) $right = $max;
    else $right = $current + $size;

    $pages = array ();

    if ($current != $min)
            $pages[] = array (
                    'number' => $min,
                    'current' => $min == $current ? true : false,
                    'url' => str_replace('{page}', $min, $pattern),
                    'type' => 'min'
            );

    for ($i = $left; $i <= $right; $i++ ) {
            $pages[] = array (
                    'number' => $i,
                    'current' => $i == $current ? true : false,
                    'url' => str_replace('{page}', $i, $pattern),
                    'type' => 'middle'
            );
    }

    if ($current != $max)
    $pages[] = array (
            'number' => $max,
            'current' => $max == $current ? true : false,
            'url' => str_replace('{page}', $max, $pattern),
            'type' => 'max'
    );
    return $pages;
}

function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
}
function toCpu ($text) {
    $text = preg_replace("/[^a-zа-я0-9\s]/ui", "", $text);
    $text = preg_replace("/(\s+)/ui", "-", $text);
    $text = translit($text);
    $text = mb_strtolower($text);
    return $text;
}

function GetFieldsAtArrray($fields, $array, $tst = false) {
    if (!is_array($fields)) $fields = explode(",", $fields);
    $arr = [];
    foreach ($fields as $field) {
        $arr[$field] = isset($array[$field]) ? $array[$field] : '';
        if ($tst) {
            $arr[$field] = tst($arr[$field]);
        }
    }
    return $arr;
}