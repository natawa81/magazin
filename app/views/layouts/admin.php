<!DOCTYPE HTML>
<html lang="en" class="mdl-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Админ-панель</title>
    <link rel="stylesheet" type="text/css" href="<?=url('/public/styles/css/bootstrap.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?=url('/public/styles/css/dashboard.css?'.mt_rand(0,9999))?>">
    <script type="text/javascript" src="<?=url('/public/styles/js/jquery-3.2.1.min.js')?>"></script>
    <script type="text/javascript" src="<?=url('/public/styles/js/ie-emulation-modes-warning.js')?>"></script>
    <script type="text/javascript" src="<?=url('/public/styles/js/bootstrap.min.js')?>"></script>
    <script type="text/javascript" src="<?=url('/public/styles/ckeditor2/ckeditor.js?'.mt_rand(0,19999))?>"></script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?=url('/admin')?>">Главная (админ-панель)</a></li>
                <li><a href="<?=url('/')?>">Главная (сайт)</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li><a href="<?=url('/admin/settings')?>">Настройки</a></li>
                <li><a href="<?=url('/admin')?>">Предметы</a></li>
                <li><a href="<?=url('/admin/category')?>">Категории</a></li>
                <li><a href="<?=url('/admin/order')?>">Заказы</a></li>
                <li><a href="<?=url('/admin/group')?>">Группы</a></li>
                <li><a href="<?=url('/admin/users')?>">Пользователи</a></li>
                <li><a href="<?=url('/admin/page')?>">Страницы</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Dashboard</h1>
            <?php
            if (!empty($message['title'])) {
                echo '<div class="alert alert-info"><p>'.$message['title'].'</p>'.$message['text'].'</div>';
            }
            ?>
<!--            <div class="row placeholders">-->
<!--                <div class="col-xs-6 col-sm-3 placeholder">-->
<!--                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">-->
<!--                    <h4>Label</h4>-->
<!--                    <span class="text-muted">Something else</span>-->
<!--                </div>-->
<!--                <div class="col-xs-6 col-sm-3 placeholder">-->
<!--                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">-->
<!--                    <h4>Label</h4>-->
<!--                    <span class="text-muted">Something else</span>-->
<!--                </div>-->
<!--                <div class="col-xs-6 col-sm-3 placeholder">-->
<!--                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">-->
<!--                    <h4>Label</h4>-->
<!--                    <span class="text-muted">Something else</span>-->
<!--                </div>-->
<!--                <div class="col-xs-6 col-sm-3 placeholder">-->
<!--                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">-->
<!--                    <h4>Label</h4>-->
<!--                    <span class="text-muted">Something else</span>-->
<!--                </div>-->
<!--            </div>-->
            <?=$content?>
        </div>
    </div>
</div>
</body>
</html>
