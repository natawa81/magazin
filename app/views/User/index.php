<?php if ($isLogged) : ?>
<div>
    <div class="user-form <?=($user->field('vk_uid')!=0?'full-form':'')?>">
        <form method="POST" action="">
            <h2>Адрес доставки</h2>
            <p>Ф.И.О.</p>
            <input type="text" class="form-control" name="fio" value="<?=$info['fio']?>" placeholder="Фамилия Имя Отчество" />
            <p>Улица, дом, квартира:</p>
            <input type="text" class="form-control" name="address" value="<?=$info['address']?>" placeholder="Ленина, 1, 1" />
            <p>Край/область/регион:</p>
            <select name="province" class="form-control">
                <option value="---">---</option>
                <?php foreach ($regions as $region) echo '<option value="'.$region.'" '.($info['region']==$region?'selected':'').'>'.$region.'</option>'; ?>
            </select>
            <p>Город:</p>
            <select name="land" class="form-control">
                <option value="---">---</option>
                <?php foreach ($lands as $land) echo '<option value="'.$land.'"'.($info['land']==$land?'selected':'').'>'.$land.'</option>'; ?>
            </select>
            <p>Почтовый индекс:</p>
            <input type="text" class="form-control" value="<?=$info['mail-index']?>" name="mail-index" placeholder="000000" />
            <p>Номер телефона:</p>
            <div class="form-phone">

                <div class="input-group">
                    <span class="input-group-addon">+</span>

                    <input type="text" class="form-control" value="<?=$info['phone-code']?>" name="phone-code" placeholder="7" style="width: 40px" />
                    <input type="text" class="form-control" value="<?=$info['phone-number']?>" name="phone-number" placeholder="8005553535" style="border-left: none;width: 200px" />
                </div>

            </div>
            <br />
            <input type="submit" class="btn btn-primary btn-block" value="Сохранить" name="save" />
        </form>
    </div>
    <?php if ($user->field('vk_uid') == 0) : ?>
    <div class="user-form">
        <h2>Безопасность</h2>
        <form method="POST" action="">
            <p>Текущий пароль:</p>
            <input type="password" name="password" class="form-control" placeholder="Текущий пароль" />
            <p>Новый пароль:</p>
            <input type="password" name="new-password" class="form-control" placeholder="Новый пароль" />
            <p>Повторите новый пароль:</p>
            <input type="password" name="new-password-two" class="form-control" placeholder="Повторите новый пароль" />
            <br />
            <input type="submit" class="btn btn-primary btn-block" value="Сохранить" name="save-pass" />
        </form>
    </div>
<?php endif; ?>
    <br clear="all" />
</div>
<?php else : ?>

<?php endif; ?>
