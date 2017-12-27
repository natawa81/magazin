<?php
$p = GetFieldsAtArrray('url,title,content,keywords,description', $_POST);
?>
<div class="edit-form">
    <form method="POST" action="">
        <p>URL (Пример: сайт.ру/page/url)</p>
        <input name="url" type="text" value="<?=$p['url']?>" class="form-control" />
        <p>Название страницы</p>
        <input name="title" type="text" value="<?=$p['title']?>" class="form-control" />
        <p>Содержимое страницы</p>
        <textarea name="content" id="editor1"><?=$p['content']?></textarea>
        <p>Ключевые слова (<label><input type="checkbox" <?=(isset($_POST['auto-keywords'])?'checked':'')?> name="auto-keywords" /> Сгенирировать автоматически?</label>)</p>
        <textarea class="form-control" name="keywords" style="height: 50px;"><?=$p['keywords']?></textarea>
        <p>Ключевые слова (<label><input type="checkbox" <?=(isset($_POST['auto-description'])?'checked':'')?> name="auto-description" /> Сгенирировать автоматически?</label>)</p>
        <textarea class="form-control" name="description" style="height: 150px;"><?=$p['description']?></textarea>
        <br />
        <input type="submit" name="add" value="Добавить" class="btn btn-block btn-primary" />
    </form>
</div>

<script type="text/javascript">
    var ckeditor1 = CKEDITOR.replace( 'editor1',{
        customConfig: '/build-config.js'
    } );
</script>
