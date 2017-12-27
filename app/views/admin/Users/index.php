<h2 class="sub-header">Пользователи</h2>
<style type="text/css">
    .table-center td {
        text-align: center;
    }
</style>
<form method="GET" action="<?=url('/admin/users')?>">
    <p><input type="text" name="query" class="form-control" value="<?=isset($_GET['query'])?trim(strip_tags($_GET['query'])):''?>" placeholder="Поиск по почте" /></p>
    <button class="btn btn-block btn-primary">Искать</button>
    <br />
</form>
<form method="POST" action="">
    <button name="save" class="btn btn-block btn-primary">Сохранить</button>
    <br />
    <table class="table-center table table-bordered" width="100%">
        <tr>
            <td>E-Mail</td>
            <td>Группа</td>
            <td>Новый пароль</td>
        </tr>
    <?php foreach ($users as $user) : $id = $user['user_id']; ?>
        <tr>
            <td><?=$user['email']?></td>
            <td><select name="edit[<?=$id?>][user_group]" class="form-control">
                    <?php foreach ($groups as $k => $g) {
                        echo '<option value="'.$k.'" '.($k == $user['user_group']?'selected':'').'>'.$g['title'].'</option>';
                    } ?>
                </select></td>
            <td><input type="text" class="form-control" name="edit[<?=$id?>][password]" /></td>
        </tr>
    <?php endforeach; ?>
    </table>
</form>
<?php if (isset($pgn)) : ?>
<center>
    <div class="btn-group">
        <?php foreach ($pgn as $i => $page) : ?>
            <a href="<?=$page['url']?>" class="btn <?=($page['current']?'btn-primary':'btn-default')?>">
                <?=($page['type'] == 'max' ? 'Последняя' : ( $page['type'] == 'min' ? 'Первая' :  $page['number']))?>
            </a>
        <?php endforeach; ?>
    </div>
</center>
<?php endif; ?>