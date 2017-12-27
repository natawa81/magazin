<h3 class="h-title">Восстановление пароля</h3>
<?php if ($step == 0) : ?>
    <form action="" method="POST">
        <input type="email" name="email" placeholder="Почта" class="form-control" />
        <br />
        <button class="btn btn-block btn-primary" name="remember">Восстановить</button>
    </form>
<?php elseif ($step == 1) : ?>
    <form action="" method="POST">
        <input type="password" name="password" class="form-control"  placeholder="Новый пароль" />
        <br />
        <input type="password" name="re_password" class="form-control" placeholder="Повторите новый пароль" />
        <br />
        <button name="remember" class="btn btn-primary">Восстановить</button>
    </form>
<?php endif; ?>
