<h3 class="h-title">Регистрация</h3>
<?php if ($registered) {
    return;
}?>
<form method="POST" action="" class="form-horizontal">
    <div class="form-group">

        <label class="col-sm-2 control-label">Почта</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" value="<?=(isset($_POST['email'])?$_POST['email']:'')?>" placeholder="Почта" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Пароль</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="password" placeholder="Пароль" />
        </div>
    </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Повторите пароль</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="re-password" placeholder="Повторите пароль" />
            </div>
        </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Ф.И.О.:</label>
        <div class="col-sm-10">

            <input type="text" class="form-control" name="fio" value="<?=(isset($_POST['fio'])?$_POST['fio']:'')?>" placeholder="Фамилия Имя Отчество" />

        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Улица, дом, квартира:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="<?=(isset($_POST['address'])?$_POST['address']:'')?>" name="address" placeholder="Ленина, 1, 1" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Край/область/регион:</label>
        <div class="col-sm-10">
            <select name="province" class="form-control">
                <option value="---">---</option>
                <?php foreach ($regions as $region) echo '<option value="'.$region.'" '.(isset($_POST['province'])? ($_POST['province']==$region?'selected':''):'').'>'.$region.'</option>'; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Город:</label>
        <div class="col-sm-10">
            <select name="land" class="form-control">
                <option value="---">---</option>
                <?php foreach ($lands as $land) echo '<option value="'.$land.'" '.(isset($_POST['land'])? ($_POST['land']==$land?'selected':''):'').'>'.$land.'</option>'; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Почтовый индекс:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="<?=(isset($_POST['mail-index'])?$_POST['mail-index']:'')?>" name="mail-index" placeholder="000000" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Номер телефона:</label>
        <div class="col-sm-10">
            <div class="form-phone">

                <div class="input-group">
                    <span class="input-group-addon">+</span>

                    <input type="text" class="form-control" value="<?=(isset($_POST['phone-code'])?$_POST['phone-code']:'')?>" name="phone-code" placeholder="7" style="width: 40px" />
                    <input type="text" class="form-control" value="<?=(isset($_POST['phone-number'])?$_POST['phone-number']:'')?>" name="phone-number" placeholder="8005553535" style="border-left: none;width: 200px" />
                </div>

            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <button name="register" class="btn btn-block btn-primary">Регистрация</button>
        </div>
    </div>

</form>
