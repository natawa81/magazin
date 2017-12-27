<h3 class="sub-header">Заказы</h3>
<div class="order-list">
<?php foreach ($orders as $order) :
    $data = unserialize($order['data']); ?>
    <div class="item">
        <form method="POST" action="">
            <h4 style="overflow:hidden"><?=$data['title']?> <span style="float:right">x <?=$order['count']?></span></h4>
            <p>Заказал: <b><?=$order['email']?></b></p>
            <p>Был оформлен: <b><?=DateFormat($order['time'])?></b><?=($order['type'] == 'payed' ? (', был оплачен: <b>'.DateFormat($order['pay_time']).'</b>') : '')?></p>
            <p>Заказ: <?=($order['type']=='wait'?'Ожидает оплаты':'Оплачен')?></p>
            <p>Статус заказа:
                <select class="form-control" name="type" <?=($order['type']=='wait'?'disabled':'')?>>
                <?php foreach ($types as $index => $type) {
                    echo '<option value="'.$index.'" '.($index==$order['admintype']?'selected':'').'>'.$type.'</option>';
                } ?>
                </select>
            </p>
            <?php if ($order['curator'] == '0' || $order['curator'] == $user->id()) : ?>
                <button name="set" value="<?=$order['id']?>" class="btn btn-block btn-primary">Сохранить <?=( $order['curator'] == $user->id() ? '' : '(автоматически следить за заказом)')?></button>
            <?php endif; ?>
        </form>
    </div>
<?php endforeach; ?>
</div>
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