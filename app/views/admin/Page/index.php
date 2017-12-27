<h2 class="sub-header" style="overflow: hidden">Страницы <a class="btn btn-primary" href="<?=url('/admin/page/add')?>" style="float:right">+</a></h2>

<table width="100%" class="table table-bordered">
    <tr>
        <td>Название страницы</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
<?php foreach ($pages as $page) : ?>
    <tr>
        <td><?=$page['title']?></td>
        <td width="100" style="text-align: center;"><a target="_blank" href="<?=url('/page/'.$page['url'])?>">Просмотр</a></td>
        <td width="100" style="text-align: center;"><a href="<?=url('/admin/page/edit?id='.$page['id'])?>">Редактировать</a></td>
        <td width="100" style="text-align: center;"><a href="<?=url('/admin/page?remove='.$page['id'])?>">Удалить</a></td>
    </tr>
<?php endforeach;?>
</table>