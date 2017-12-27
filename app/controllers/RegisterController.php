<?php

namespace app\controllers;


use vendor\modules\Settings;
use vendor\modules\Validator;

class RegisterController extends AppController
{
    private $fields = [
        'address' => 'Улица, дом, квартира',
        'region' => 'Край, область, регион',
        'land' => 'Город',
        'mail-index' => 'Почтовый индекс',
        'phone-code' => 'Код телефона',
        'phone-number' => 'Номер телефона',
        'password' => 'Пароль',
        'email' => 'Почта',
        're-password' => 'Повторите пароль',
        'fio' => 'Ф.И.О.'
    ];
    public function indexAction(){
        $vars = [];
        $vars['regions'] = Settings::instance()->regions;
        $vars['lands'] = Settings::instance()->lands;
        $vars['registered'] = false;
        if (isset($_POST['register'])) {
            $arr = [
                'address' => trim (strip_tags (str_replace('\\','/', $_POST['address']))),
                'region' => trim(strip_tags($_POST['province'])),
                'land' => trim(strip_tags($_POST['land'])),
                'mail-index' => trim(strip_tags($_POST['mail-index'])),
                'phone-code' => intval($_POST['phone-code']),
                'phone-number' => intval($_POST['phone-number']),
                'password' => trim(strip_tags($_POST['password'])),
                're-password' => trim(strip_tags($_POST['re-password'])),
                'email' => trim(strip_tags($_POST['email'])),
                'fio' => trim(strip_tags($_POST['fio']))
            ];
            $v = new Validator($arr);
            $v->rule('required',array_keys($arr));
            $v->rule('email', 'email');
            $v->rule('equals', 'password', 're-password');
            $v->rule('regex', ['password','re-password'], '/^(?=^.{8,20}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/ui');
            $v->rule('regex', 'address', '/^([А-Яа-яA-Za-z0-9\s]+)(,|,\s+|\s+)(\d+|\d+\/\d+)(,|,\s|\s+)(\d+)$/');
            $v->rule('in', 'region', $vars['regions']);
            $v->rule('in', 'land', $vars['lands']);
            $v->rule('regex', 'phone-number', '/^([0-9]{10})$/');
            $v->rule('regex', 'phone-code', '/^([0-9]{1,3})$/');
            $v->rule('regex', 'mail-index', '/^([0-9]{5,6})$/');
            $v->rule('regex', 'fio', '/^([А-я]{1,})(\s+)([А-я]{1,})(\s+)([А-я]{1,})$/u');

            if (!$v->validate()) {
                debug($v->errors());
            } else {
                $int = $this->DB()->register($arr);
                if ($int > 0) {
                    $this->ShowMessage("Вы успешно зарегистрированы!");
                    $vars['registered'] = true;
                } else {
                    $this->ShowMessage("Ошибка!", "Такая почта уже зарегистрирована!");
                }
            }
        }

        $this->set($vars);
    }
}