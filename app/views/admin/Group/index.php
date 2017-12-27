<style type="text/css">
    .table-group td {
        text-align: center;
    }
</style>
<h2 class="sub-header">Группы</h2>

<table class="table-group table table-bordered" width="100%">
    <tr>
        <td>Название</td>
        <?php foreach ($rights as $title) {
           echo '<td>'.$title.'</td>';
        }?>
        <td>Группа при регистрации</td>
        <td></td>
    </tr>
    <form method="POST" action="">
        <tr>
            <td><input type="text" name="title" class="form-control" placeholder="Название" /></td>
            <?php foreach ($rights as $key => $value) {
                echo '<td><input type="checkbox" name="rights['.$key.']" /></td>';
            }?>
            <td><input type="checkbox" name="startgroup" /></td>
            <td><input type="submit" name="add" class="btn btn-block btn-success" value="Добавить" /></td>
        </tr>
    </form>
    <form method="POST" action="">
    <?php foreach($groups as $group) :
    $group['rights'] = unserialize($group['rights']);
    $id = $group['id'];
    ?>
    <tr>
        <td><input type="text" name="update[<?=$id?>][title]" class="form-control" placeholder="Название" value="<?=$group['title']?>" /></td>
        <?php foreach ($rights as $key => $right) {
            $right = isset($group['rights'][$key]) ? $group['rights'][$key] : '0';
            echo '<td><input type="checkbox" '.($right=='1'?'checked':'').' name="update['.$id.'][rights]['.$key.']" /></td>';
        } ?>
        <td><input type="radio" <?=($group['startgroup']=='1'?'checked':'')?> name="startgroup" value="<?=$id?>" /></td>
        <td><label for="remove-<?=$id?>">Удалить?</label> <input type="checkbox" id="remove-<?=$id?>" name="update[<?=$id?>][remove]" /></td>
    </tr>
    <?php endforeach; ?>
        <tr>
            <td colspan="<?=(count($rights) + 3)?>"><button name="save" class="btn btn-block btn-primary">Сохранить</button></td>
        </tr>
    </form>
</table>