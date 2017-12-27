<h2 class="sub-header">Редактирование</h2>
<div class="edit-form">
    <form action="" method="POST">
        <p>Название</p>
        <input type="text" name="title" class="form-control" value="<?=$item['title']?>" />
        <p>Родитель</p>
        <select name="parent" class="form-control">
            <option value="0" <?=($item['parent']=='0'?'selected':'')?>>--Без родителя--</option>
            <?php foreach ($items as $val) : ?>
                <option value="<?=$val['id']?>" <?=($item['parent']==$val['id']?'selected':'')?>><?=$val['title']?></option>
            <?php endforeach; ?>
        </select>
        <br />
        <input type="submit" name="edit" value="Изменить" class="btn btn-block btn-lg btn-primary" />
    </form>
</div>