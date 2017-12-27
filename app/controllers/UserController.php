<?php

namespace app\controllers;


use vendor\modules\Settings;
use vendor\modules\Validator;

class UserController extends AppController {

    private $fields = [
        'address' => 'Улица, дом, квартира',
        'region' => 'Край, область, регион',
        'land' => 'Город',
        'mail-index' => 'Почтовый индекс',
        'phone-code' => 'Код телефона',
        'phone-number' => 'Номер телефона',
        'password' => 'Текущий пароль',
        'new-password' => 'Новый пароль',
        'new-password-two' => 'Повторите новый пароль',
        'fio' => 'Ф.И.О.'
    ];

    public function indexAction () {
        $vars = ['isLogged' => $this->user->login()];
        $vars['regions'] = Settings::instance()->regions;
        $vars['lands'] = Settings::instance()->lands;
        if ($this->user->login()) {
            $vars ['info'] = $this->DB()->Profile($this->user->id());
            if (isset($_POST['save'])) {
                $arr = [
                    'address' => trim (strip_tags (str_replace('\\','/', $_POST['address']))),
                    'region' => trim(strip_tags($_POST['province'])),
                    'land' => trim(strip_tags($_POST['land'])),
                    'mail-index' => trim(strip_tags($_POST['mail-index'])),
                    'phone-code' => intval($_POST['phone-code']),
                    'phone-number' => intval($_POST['phone-number']),
                    'fio' => trim(strip_tags($_POST['fio']))
                ];
                $v = new Validator($arr);
                $v->rule('required',array_keys($arr));
                $v->rule('regex', 'address', '/^([А-Яа-яA-Za-z0-9\s]+)(,|,\s+|\s+)(\d+|\d+\/\d+)(,|,\s|\s+)(\d+)$/');
                $v->rule('in', 'region', $vars['regions']);
                $v->rule('in', 'land', $vars['lands']);
                $v->rule('regex', 'fio', '/^([А-я]{1,})(\s+)([А-я]{1,})(\s+)([А-я]{1,})$/u');
                $v->rule('regex', 'phone-number', '/^([0-9]{10})$/');
                $v->rule('regex', 'phone-code', '/^([0-9]{1,3})$/');
                $v->rule('regex', 'mail-index', '/^([0-9]{5,6})$/');

                if (!$v->validate()) {
                    $messages = [];
                    foreach ($v->errors() as $key => $er) {
                        for ($i = 0; $i < count($er); $i++) {
                            $messages[] = str_replace($key, "Поле `".$this->fields[$key]."`", mb_strtolower($er[$i]));
                        }
                    }
                    $this->ShowMessage("Ошибка", "<ul><li>".implode("</li><li>", $messages)."</li></ul>");
                } else {
                    $this->DB()->Set($arr);
                    $vars['info'] = $arr;
                    $this->ShowMessage('Изменения успешно сохранены');
                }
            } else if (isset($_POST['save-pass'])) {
                $arr = [
                    'password' => trim(strip_tags($_POST['password'])),
                    'new-password' => trim(strip_tags($_POST['new-password'])),
                    'new-password-two' => trim(strip_tags($_POST['new-password-two'])),
                ];
                $v = new Validator($arr);
                $v->rule('required', array_keys($arr));
                $v->rule('equals', 'new-password', 'new-password-two');
                $v->rule('regex', ['new-password','new-password-two'], '/^(?=^.{8,20}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/ui');

                if (!$v->validate()) {
                    $messages = [];
                    foreach ($v->errors() as $key => $er) {
                        for ($i = 0; $i < count($er); $i++) {
                            $messages[] = str_replace($key, "Поле `".$this->fields[$key]."`", mb_strtolower($er[$i]));
                        }
                    }
                    $this->ShowMessage("Ошибка", "<ul><li>".implode("</li><li>", $messages)."</li></ul>");
                } else {
                    if ($this->user->EqualPassword($arr['password'])) {
                        $this->user->SavePassword($arr['new-password']);
                        $this->ShowMessage('Изменения успешно сохранены');
                    } else {
                        $this->ShowMessage("Ошибка",  "<ul><li>`Текущий пароль` не верный!</li></ul>");
                    }
                }

            }

        }

//        $vars = compact();
        $this->title('Профиль пользователя');
        $this->set($vars);
    }
}