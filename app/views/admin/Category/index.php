<h2 class="sub-header" style="overflow: hidden">Категории <a class="btn btn-primary" href="<?=url('/admin/category/add')?>" style="float:right">+</a></h2>
<div class="category-items">
<?php foreach ($list as $item) : ?>
    <div class="item" style="margin-left: <?=($item['level'] * 20)?>px"><a href="<?=url('/admin/category/edit-'.$item['id'])?>"><?=$item['title']?></a>
        <a href="<?=url('/admin/category?remove='.$item['id'])?>" class="btn btn-primary"><i class="glyphicon glyphicon-remove"></i></a>
    </div>
<?php endforeach; ?>
</div>
