<?php
if(!isset($register)) {
	return;
}
?>
<h3 class="h-title">Авторизация</h3>
<div class="alert alert-info">
	<p>Внимание!</p>
	<p>Минимальные требования для авторизации через ВК - Почта!</p>
	<p>В дальнейшем при заказе вы должны будете заполнить все поля в разделе вашего профиля!</p>
</div>
<form method="POST" action="" class="form-horizontal">
    <div class="form-group">

        <label class="col-sm-2 control-label">Почта</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" value="<?=(isset($_POST['email'])?$_POST['email']:'')?>" placeholder="Почта" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <button name="register" class="btn btn-block btn-primary">Сохранить</button>
        </div>
    </div>

</form>
