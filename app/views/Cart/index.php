<h3 class="h-title">Корзина</h3>
<?php
if (count($posts) == 0) {
   echo '<div class="alert alert-info">В вашей корзине нет товаров</div>';
   return;
}?>
<form action="" method="POST">
    <button class="btn btn-primary btn-block" name="buy">Купить все</button>
    <br />
<div class="cart">
        <?php foreach ($posts as $k => $v) {
            echo '<div class="item" id="item-'.$v['id'].'">
                <p class="title">'.$v['title'].'</p>
                <div class="img">
                <img src="'.url('/public/upload/images/'.$v['image']).'" />
                </div>
                <div class="info">
                    <p class="price">
                        <input type="number" name="values['.$v['id'].']" value="'.$v['need_count'].'" class="form-control" /> X '.$v['price'].'Р
                    </p>
                    <a class="btn btn-primary" href="'.url('/view/'.$v['id']).'">Подробнее</a>
                    <a class="btn btn-danger" href="javascript:removeInCart('.$v['id'].')">Убрать из корзины</a>
                </div>
            </div>';
        } ?>
</div>
</form>

<script type="text/javascript">
    function removeInCart ( id ) {
        $.ajax({
            url:location.href,
            dataType: "JSON",
            data: {"id":id},
            success: function (res) {
                $("#item-"+id).fadeOut(300, function(){
                    $(this).remove();
                });
                noty({ text: res['msg'], layout: 'bottomRight', type:'success'});
                $("#in_cart").html(res['in_cart']);
            }
        });
    }
</script>