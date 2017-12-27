<?php

namespace app\controllers\admin;


use vendor\core\User;
use vendor\modules\Validator;

class UsersController extends AppController
{
    public function indexAction () {
        if (!$this->user->access('users')) {
            RefreshPage(url('/admin'));
        }
        $vars = [];

        $groups = $this->DB()->GetAllGroups();
        $start_group = 0;
        foreach ($groups as $id => $group) {
            if ($group['startgroup']=='1') $start_group = $id;
        }

        if (isset($_POST['save'])) {
            $items = $_POST['edit'];
            $messages = [];
            foreach ($items as $id => $user) {
                $u = $this->DB()->findOne($id);
                if ($u) {
                    $arr = [];
                    $arr['user_group'] = isset($groups[$user['user_group']]) ? intval($user['user_group']) : $start_group;
                    $password = trim(strip_tags($user['password']));

                    if (!empty($password)) {
                        $v = new Validator(['password' => $password]);
                        $v->rule('regex', 'password', '/^(?=^.{8,20}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/ui');
                        if ($v->validate()) {
                            $arr['password'] = User::HASH(['password' => $password]);
                            $this->DB()->UpdateUser($arr, $u['user_id']);
                        } else {
                            $messages = array_merge($messages, $this->Messages($v->errors()));
                        }
                    } else {
                        $this->DB()->UpdateUser($arr, $u['user_id']);
                    }
                }
            }
            if (count($messages) > 0) {
                $this->ShowMessage("Ошибка!", "<ul><li>".implode("</li><li>", $messages)."</li></ul>");
            } else {
                $this->ShowMessage("Пользователи обновлены!");
            }
        }
        $query = isset($_GET['query']) ? trim(strip_tags($_GET['query'])) : '';
        if (!empty($query)) {
            $vars['users'] = $this->DB()->findLike(trim(strip_tags($_GET['query'])), 'email');
        } else {

            $execute = [];
            $num = 12;
            $page = isset($_GET['page']) ? abs(intval($_GET['page'])) : 1;
            $result = $this->DB()->BindSQL("SELECT COUNT(*) as `count` FROM `users` ORDER BY `user_id` DESC", $execute, true);
            $posts = $result[0]['count'];
            $total = intval(($posts - 1) / $num) + 1;
            $page = min($page, $total);
            $start = $page * $num - $num;


            $execute[] = [$start, \PDO::PARAM_INT];
            $execute[] = [$num, \PDO::PARAM_INT];

            $vars ['users'] = $this->DB()->BindSQL("SELECT * FROM `users` ORDER BY `user_id` DESC LIMIT ?, ?", $execute, true);
            $vars ['pgn'] = createPagination($page,1,$total,2,url('/admin/users?page={page}'));
        }

        $vars ['groups'] = $groups;

        $this->set($vars);
    }
    public function Messages ($errors) {
        $messages = [];
        foreach ($errors as $key => $er) {
            for ($i = 0; $i < count($er); $i++) {
                $messages[] = str_replace($key, "Поле `Новый пароль`", mb_strtolower($er[$i]));
            }
        }
        return $messages;
    }
}