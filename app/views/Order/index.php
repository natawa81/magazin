<h3 class="h-title">Заказы</h3>
<?php if (!$is_logged) {
    echo '<div class="alert alert-info">Вы не авторизированны!</div>';
    return;
} ?>
<div class="alert alert-info">
    <p>Внимание!</p>
    <p>Сумма оплаты доствки в стоимость заказа не входит!</p>
    <p>Узнать стоимость доставки можно по <b><a href="<?=url('/page/delivery')?>" style="text-decoration: underline;">ссылке</a></b></p>
</div>

<form method="POST" action="">
    <button name="payAll" class="btn btn-block btn-primary">Оплатить всё\выделенное</button>
    <br />
    <div class="cart">
    <?php foreach ($items as $item) : ?>

        <div class="item" id="item-<?=$item['id']?>">
            <p class="title" style="overflow: hidden;"><?=$item['title']?><input checked type="checkbox" name="payItem[]" value="<?=$item['order_id']?>" style="float: right" /></p>
            <div class="img">
                <img src="<?=url('/public/upload/images/'.$item['image'])?>" />
            </div>
            <div class="info">
                <p class="price"><?=($item['need_count'])?> X <?=($item['price'])?>Р</p>
                <p>Дата заказа: <?=DateFormat($item['time'])?></p>
                <?php if ($item['type'] == 'payed') : ?>
                    <p>Дата оплаты: <?=DateFormat($item['pay_time'])?></p>
                <?php endif; ?>

                <a class="btn btn-primary" href="<?=url('/view/'.$item['id'])?>">Подробнее</a>
                <a class="btn btn-success" onclick="payOne(<?=$item['order_id']?>);">Оплатить отдельно</a>
            </div>
        </div>

<?php endforeach; ?>
    </div>
</form>
<form method="POST" action="">
    <input type="hidden" value="" name="payOne" id="payOne" />
</form>
<script type="text/javascript">
    function payOne (order_id) {
        $("#payOne").val(order_id).parent().submit();
    }
</script>