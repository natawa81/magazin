<!DOCTYPE html>
<html>
<head>
    <title><?=(!empty($title) ? $title : "")?> Магазин</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?=$meta_keywords?>" />
    <meta name="description" content="<?=$meta_description?>" />
    <link rel="stylesheet" type="text/css" href="<?=url('/public/styles/css/bootstrap.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?=url('/public/styles/css/style.css?'.mt_rand(0,9999))?>">
    <script type="text/javascript" src="<?=url('/public/styles/js/jquery-3.2.1.min.js')?>"></script>
    <script type="text/javascript" src="<?=url('/public/styles/js/bootstrap.min.js')?>"></script>
    <script type="text/javascript" src="<?=url('/public/styles/noty/packaged.min.js')?>"></script>
</head>
<body>

<div class="header">
    <div class="header-content"></div>
    <div class="menu">
        <ul>
            <li><a href="<?=url('/')?>">Главная</a></li>
            <?php if (!$user->login()) : ?><li><a href="<?=url('/login')?>">Авторизация</a></li><?php endif; ?>
            <li><a href="<?=url('/cart')?>">Корзина</a></li>
            <?php if ($user->login()) : ?><li><a href="<?=url('/order')?>">Заказы</a></li><?php endif; ?>
            <?php if ($user->login()) : ?><li><a href="<?=url('/user')?>">Профиль</a></li><?php endif; ?>
            <li><a href="<?=url('/page/delivery')?>">Доставка</a></li>
        </ul>
    </div>
</div>

<div class="main-wrapper">
    <div class="sidebar">
        <?php if ($user->login()) : ?>
            <h3 class="h-title"><?=$user->field('email')?></h3>
            <p>В вашей корзине: <b id="in_cart"><?=$in_cart?></b> товаров</p>
            <?php if ($user->isAdmin()) : ?>
                <p align="center"><a href="<?=url('/admin')?>" class="btn btn-primary btn-xs">Админ-панель</a></p>
            <?php endif; ?>
            <div align="center"><a class="btn btn-danger" href="<?=url('/login/logout?redirect='.$_SERVER['QUERY_STRING'])?>">Выход</a></div>
        <?php else : ?>
            <p>В вашей корзине: <b id="in_cart"><?=$in_cart?></b> товаров</p>

            <h3 class="h-title">Авторизация</h3>
            <form action="<?=url('/login')?>" method="POST">
                <div>
                    <p>Почта:</p>
                    <input type="email" name="username" class="form-control" />
                </div>
                <div class="login-pass">
                    <p>Пароль: <a href="<?=url('/login/remember')?>">Забыли пароль?</a></p>
                    <input type="password" name="password" class="form-control" />
                </div>
                <br/>
                <div class="login-button">
                    <input type="submit" class="btn btn-primary" value="Войти" name="login" />
                    <a href="<?=url('/register')?>" class="btn btn-success">Регистрация</a>
                </div>
            </form>
            <hr />
            <a href="<?=url('/login/loginvk')?>" class="btn btn-block btn-primary">Авторизация через Вк</a>
        <?php endif; ?>
        <h3 class="h-title">Категории</h3>
        <?=$main_category?>
        <h3 class="h-title">Фильтры</h3>
        <form method="GET" action="<?=$filters_link?>">
            <button class="btn btn-block btn-default">Фильтровать</button>
            <br />
            <input name="query_name" id="query_name" type="text" placeholder="Поиск по названию" value="<?=$data_filters_value['query_name']?>" class="form-control" />
            <br />
            <input name="query_author" id="query_author" type="text" placeholder="Поиск по авторам" value="<?=$data_filters_value['query_author']?>" class="form-control" />
            <div class="filter">
                <h5>Год:</h5>
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2">От:</span>
                    <input type="number" id="min_year" name="min_year" min="<?=$data_filters['min_year']?>" max="<?=$data_filters['max_year']?>" value="<?=$data_filters_value['min_year']?>" class="form-control" placeholder="Год издания" />
                </div>
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2">До:</span>
                    <input type="number" id="max_year" name="max_year" min="<?=$data_filters['min_year']?>" max="<?=$data_filters['max_year']?>" value="<?=$data_filters_value['max_year']?>" class="form-control" placeholder="Год издания" />
                </div>
            </div>
            <div class="filter">
                <h5>Кол-во страниц:</h5>
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2">От:</span>
                    <input type="number" id="min_pages" name="min_pages" min="<?=$data_filters['min_pages']?>" max="<?=$data_filters['max_pages']?>" value="<?=$data_filters_value['min_pages']?>" class="form-control" placeholder="Кол-во страниц   " />
                </div>
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2">До:</span>
                    <input type="number" id="max_pages" name="max_pages" min="<?=$data_filters['min_pages']?>" max="<?=$data_filters['max_pages']?>" value="<?=$data_filters_value['max_pages']?>" class="form-control" placeholder="Кол-во страниц" />
                </div>
            </div>
            <a id="clear-filters" class="btn btn-block btn-default">Отчистить фильтры</a>
            <input type="hidden" name="page" value="<?=(isset($_GET['page'])?abs(intval($_GET['page'])) : 1)?>" />
        </form>
    </div>
    <div class="content">
        <?php
        if (!empty($message['title'])) {
            echo '<div class="alert alert-info"><p>'.$message['title'].'</p>'.$message['text'].'</div>';
        }
        ?>
       <?=$content?>

    </div>

    <div class="clear"></div>
</div>

<div class="footer">
    
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $(".category li > span").click(function () {
            var ul = $(this).parent().children("ul");
            $(this).toggleClass("hidden-item");
            ul.slideToggle();
        });

        $("#clear-filters").click(function () {
            $("#min_year").val($("#min_year").attr("min"));
            $("#max_year").val($("#max_year").attr("max"));
            $("#min_pages").val($("#min_pages").attr("min"));
            $("#max_pages").val($("#max_pages").attr("max"));
            $("#query_author").val("");
            $("#query_name").val("");
        });
    });

</script>

<div class="hidden">▼</div>

</body>
</html>
