<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;
use app\models\Login;
use vendor\modules\Validator;
use VK\VK;

class LoginController extends AppController {

    protected $fields = ['password' => 'Пароль', 're-password' => 'Повторный пароль', 'email' => 'Почта'];

    protected $messages = [
                'error' => [
                    1 => 'Такого пользователя не существует!',
                    2 => 'Введен неверный пароль!',
                    3 => 'Не верные данные для восстановления!',
                ],
                'success' => [
                    1 => 'Вы успешно вошли!',
                    2 => 'На вашу почту было отправлено сообщение с ссылкой на восстановление пароля',
                    3 => 'Вы успешно сменили пароль!',
                ],
        ];

    public function __construct($route) {
        parent::__construct($route);
    }
    
    public function indexAction () {
        global $app;

        $this->title('Авторизация');

        $user = new \vendor\core\User;
        $logged = false;
        if (!$user->login()) {
            if (isset ($_POST['login'])) {
                $v = new Validator(['username' => $_POST['username'], 'password' => $_POST['password']]);

                $v->rule('required', ['username', 'password']);


                $username = trim(strip_tags($_POST['username']));
                $password = trim(strip_tags($_POST['password']));
                if (!empty($username) && !empty($password)) {
                    $msgs = $user->Authorize($username, $password);
                    if ($msgs['error'] > 0) {
                        $this->ShowMessage("Ошибка входа", $this->messages['error'][$msgs['error']]);
                    } else {
                        $this->ShowMessage($this->messages['success'][$msgs['success']]);
                        $logged = true;
                    }
                }
            }
        }
        $vars = [
            'login'=>$user->login(),
            'logged'=>$logged
        ];
        $this->set($vars);
    }

    public function logoutAction() {
        $redirect = isset($_GET['redirect']) ? '/'.strip_tags($_GET['redirect']) : '/';
        $this->user->logout();
        header ("Location: ".$redirect);
    }

    public function loginvkAction () {
        if ($this->user->login()) {
            RefreshPage(url('/'));
        }
        $vkConfig = GetConfig('vk');
        $vk = new VK($vkConfig['app_id'], $vkConfig['api_secret']);
        $url = $vk->getAuthorizeUrl('uid, first_name, last_name', $vkConfig['redirect_url']);
        if (isset($_SESSION['vk_ses_token'])) {
            $token = $_SESSION['vk_ses_token'];
            $vk->setAccessToken($token['access_token']);
            $userInfo = $vk->getFields('uid,first_name,last_name,screen_name,sex,bdate,photo_big');
            $data = $this->DB()->findOne($userInfo['uid'], 'vk_uid');
            if ($data) {
                $this->user->LoginByEmail($data['email']);
                RefreshPage(url('/'));
            } else {
                if (isset($_POST['register'])) {
                    $email = tst($_POST['email']);
                    $arr = [
                        'email' => $email,
                        'vk_token' => $token['access_token'],
                        'vk_uid' => $userInfo['uid']
                    ];
                    $v = new Validator(['email' => $email]);
                    $v->rule('required', ['email']);
                    $v->rule('email', 'email');
                    if ($v->validate()) {
                        $find = $this->DB()->findOne($email, 'email');
                        if ($find) {
                            $this->ShowMessage('Ошибка!', 'Пользователь с такой почтой уже существует!');
                        } else {
                            $this->DB()->Insert($arr);
                            $this->user->LoginByEmail($arr['email']);
                            RefreshPage(url('/'));
                        }
                    } else {
                        $errors = parseValidatorErrors($v->errors(), $this->fields);
                        $this->ShowMessage('Ошибка!', $errors);
                    }
                }

                $this->set(['register'=>true]);
            }

        } else {
            header("Location: $url");
        }
    }


    public function vkAction () {
        $vkConfig = GetConfig('vk');
        $vk = new VK($vkConfig['app_id'], $vkConfig['api_secret']);
        $token = $vk->getAccessToken($_GET['code'],$vkConfig['redirect_url']);
        if ($vk->isAuth()) {
            $_SESSION['vk_ses_token'] = $token;
            RefreshPage(url('/login/loginvk'));   
        }
    }

    public function rememberAction () {
        $this->title('Востановить пароль');

        $this->setModel(new Login());
        $step = 0;
        if (!isset($_GET['link'])) {
            if (isset($_POST['remember'])) {
                $email = trim(strip_tags($_POST['email']));
                $msgs = $this->user->Remember($email);
                if ($msgs['error'] > 0) {
                    $this->ShowMessage("Ошибка входа", $this->messages['error'][$msgs['error']]);
                } else {
                    $this->ShowMessage($this->messages['success'][$msgs['success']]);
                }
            }

        } else {
            $step = 1;
            $link = trim(strip_tags($_GET['link']));
            $row = $this->DB()->link($link);
            if ($row) {
                if (isset($_POST['remember'])) {
                    $arr = [
                        'password' => trim(strip_tags($_POST['password'])),
                        're-password' => trim(strip_tags($_POST['re_password']))
                    ];
                    $v = new Validator($arr);
                    $v->rule('required', array_keys($arr));
                    $v->rule('equals', 'password', 're-password');
                    $v->rule('regex', ['password','re-password'], '/^(?=^.{8,20}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/ui');
                    if ($v->validate()) {
                        $msgs = $this->user->CompleteRemember($link, $arr['password'], $arr['re-password']);
                        if ($msgs['error'] > 0) {
                            $this->ShowMessage("Ошибка входа", $this->messages['error'][$msgs['error']]);
                        } else {
                            $this->ShowMessage($this->messages['success'][$msgs['success']]);
                        }
                    } else {
                        $messages = [];
                        foreach ($v->errors() as $key => $er) {
                            for ($i = 0; $i < count($er); $i++) {
                                $messages[] = str_replace($key, "Поле `".$this->fields[$key]."`", mb_strtolower($er[$i]));
                            }
                        }
                        $this->ShowMessage("Ошибка", "<ul><li>".implode("</li><li>", $messages)."</li></ul>");

                    }
                }
            } else {
                $step = 0;
            }
        }
        $this->set(compact('step'));
    }
    
}
