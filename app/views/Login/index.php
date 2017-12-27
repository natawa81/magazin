<?php
if ($logged) {
    return;
}
if ($login) {
?>
<h3 style="text-align: center;">Вы уже вошли!</h3>
<?php
	return;
}
?>
<h3 class="h-title">Авторизация</h3>

<div class="form-login">
	<form action="" method="POST">
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
		</div>
	</form>
</div>