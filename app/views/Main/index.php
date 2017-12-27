<h3 class="h-title">Товары</h3>
<?php if (count($items) == 0) {
    echo '<div class="alert alert-info">Товаров по данной категории не найдено</div>';
} ?>
<div class="card-columns items">
<?php foreach (array_chunk($items, 3) as $id => $values ) {
    // echo '<div class="item-folder">';
    foreach ($values as $k => $v) {
        echo '<div class="item card">
                <p class="title">'.$v['title'].'</p>
                <div class="img">
                <img src="'.url('/public/upload/images/'.$v['image']).'" />
                </div>
                <p class="price">'.$v['price'].'Р</p>
                <a class="btn btn-primary" href="'.url('/view/'.$v['id']).'">Подробнее</a>
                <a class="btn btn-danger" href="javascript:addToCart('.$v['id'].')">В корзину</a>
            </div>';
    }
    // echo '</div>';
} ?>
</div>
<center>
<div class="btn-group">
    <?php foreach ($pgn as $i => $page) : ?>
    <a href="<?=$page['url']?>" class="btn <?=($page['current']?'btn-primary':'btn-default')?>">
        <?=($page['type'] == 'max' ? 'Последняя' : ( $page['type'] == 'min' ? 'Первая' :  $page['number']))?>
    </a>
    <?php endforeach; ?>
</div>
</center>
<script type="text/javascript">
    function addToCart(id) {
        $.ajax({
            url:location.href,
            dataType:"JSON",
            data: {"id":id},
            success: function (res) {
                noty({ text: res['msg'], layout: 'bottomRight', type:'success'});
                $("#in_cart").html(res['in_cart']);
            }
        });
    }
</script>