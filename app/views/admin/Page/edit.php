<?php
if (!isset($page)) {
    return;
} ?>
<div class="edit-form">
    <form method="POST" action="">
        <p>URL (Пример: сайт.ру/page/url)</p>
        <input name="url" type="text" value="<?=$page['url']?>" class="form-control" />
        <p>Название страницы</p>
        <input name="title" type="text" value="<?=$page['title']?>" class="form-control" />
        <p>Содержимое страницы</p>
        <textarea name="content" id="editor1"><?=$page['content']?></textarea>
        <p>Ключевые слова (<label><input type="checkbox" name="auto-keywords" /> Сгенирировать автоматически?</label>)</p>
        <textarea class="form-control" name="keywords" style="height: 50px;"><?=$page['keywords']?></textarea>
        <p>Ключевые слова (<label><input type="checkbox" name="auto-description" /> Сгенирировать автоматически?</label>)</p>
        <textarea class="form-control" name="description" style="height: 150px;"><?=$page['description']?></textarea>
        <br />
        <input type="submit" name="edit" value="Изменить" class="btn btn-block btn-primary" />
    </form>
</div>

<script type="text/javascript">
    var ckeditor1 = CKEDITOR.replace( 'editor1',{
        customConfig: '/build-config.js'
    } );
</script>
