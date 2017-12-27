<h2 class="sub-header">Добавление</h2>
<div class="edit-form">
    <form action="" method="POST">
        <p>Название</p>
        <input type="text" name="title" class="form-control" />
        <p>Родитель</p>
        <select name="parent" class="form-control">
            <option value="0" selected>--Без родителя--</option>
            <?php foreach ($items as $val) : ?>
                <option value="<?=$val['id']?>"><?=$val['title']?></option>
            <?php endforeach; ?>
        </select>
        <br />
        <input type="submit" name="add" value="Добавить" class="btn btn-block btn-lg btn-primary" />
    </form>
</div>