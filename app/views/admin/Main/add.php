<h2 class="sub-header">Добавление</h2>
<div class="edit-form">
    <form method="POST" action="" enctype="multipart/form-data">
        <p>Название</p>
        <input type="text" class="form-control" value="<?=(isset($_POST['title'])?$_POST['title']:'')?>" name="title" />
        <p>Изображение</p>
        <div class="image-list">
            <div class="image-item"><input type="file" name="image" /></div>
        </div>
        <p>Категория</p>
        <select name="category" class="form-control">
            <?php foreach ($category as $id => $val) {
                echo '<option value="'.$id.'" >'.$val['title'].'</option>';
            } ?>
        </select>
        <p>Автор</p>
        <input type="text" class="form-control" value="<?=(isset($_POST['author'])?$_POST['author']:'')?>" name="author" />
        <p>Издатель</p>
        <input type="text" class="form-control" value="<?=(isset($_POST['publisher'])?$_POST['publisher']:'')?>" name="publisher" />
        <p>Цена</p>
        <input type="text" class="form-control" value="<?=(isset($_POST['price'])?$_POST['price']:'')?>" name="price" />
        <p>Количество</p>
        <input type="number" class="form-control" value="<?=(isset($_POST['count'])?$_POST['count']:'')?>" name="count" />
        <p>Язык</p>
        <input type="text" class="form-control" value="<?=(isset($_POST['lang'])?$_POST['lang']:'')?>" name="lang" />
        <p>Переплёт</p>
        <input type="text" class="form-control" value="<?=(isset($_POST['bookFormat'])?$_POST['bookFormat']:'')?>" name="bookFormat" />
        <p>Формат</p>
        <input type="text" class="form-control" value="<?=(isset($_POST['format'])?$_POST['format']:'')?>" name="format" />
        <p>Описание</p>
        <textarea name="description" class="form-control" id="editor1"><?=(isset($_POST['description'])?$_POST['description']:'')?></textarea>
        <p>Количество страниц</p>
        <input type="number" class="form-control" value="<?=(isset($_POST['pages'])?$_POST['pages']:'')?>" name="pages" />
        <p>Год</p>
        <input type="number" class="form-control" value="<?=(isset($_POST['year'])?$_POST['year']:'')?>" name="year" />
        <br />
        <button name="add" class="btn btn-primary btn-lg btn-block">Добавить</button>
    </form>
</div>
<script type="text/javascript">
    var ckeditor1 = CKEDITOR.replace( 'editor1',{
        customConfig: '/build-config.js'
    } );
</script>
