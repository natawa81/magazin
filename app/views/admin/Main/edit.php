<h2 class="sub-header" style="">Редактирование</h2>
<div class="edit-form">
    <form method="POST" action="" enctype="multipart/form-data">
        <p>Название</p>
        <input type="text" class="form-control" value="<?=$row['title']?>" name="title" />
        <p>Изображение</p>
        <div class="image-list">
            <div class="image-item"><img src="<?=url('/public/upload/images/'.$row['image'])?>" /></div>
            <div class="image-item"><input type="file" name="image" /></div>
        </div>
        <p>Категория</p>
        <select name="category" class="form-control">
            <?php foreach ($category as $id => $val) {
                echo '<option value="'.$id.'" '.($id==$row['category']?'selected':'').'>'.$val['title'].'</option>';
            } ?>
        </select>
        <p>Автор</p>
        <input type="text" class="form-control" value="<?=$row['author']?>" name="author" />
        <p>Издатель</p>
        <input type="text" class="form-control" value="<?=$row['publisher']?>" name="publisher" />
        <p>Цена</p>
        <input type="text" class="form-control" value="<?=$row['price']?>" name="price" />
        <p>Количество</p>
        <input type="number" class="form-control" value="<?=$row['count']?>" name="count" />
        <p>Язык</p>
        <input type="text" class="form-control" value="<?=$row['lang']?>" name="lang" />
        <p>Переплёт</p>
        <input type="text" class="form-control" value="<?=$row['bookFormat']?>" name="bookFormat" />
        <p>Формат</p>
        <input type="text" class="form-control" value="<?=$row['format']?>" name="format" />
        <p>Описание</p>
        <textarea name="description" class="form-control" id="editor1"><?=$row['description']?></textarea>
        <p>Количество страниц</p>
        <input type="number" class="form-control" value="<?=$row['pages']?>" name="pages" />
        <p>Год</p>
        <input type="number" class="form-control" value="<?=$row['year']?>" name="year" />
        <br />
        <button name="edit" class="btn btn-primary btn-lg btn-block">Изменить</button>
    </form>
</div>
<script type="text/javascript">
    var ckeditor1 = CKEDITOR.replace( 'editor1',{
        customConfig: '/build-config.js'
    } );
</script>
