<h2 class="sub-header" style="overflow: hidden">Товары <a class="btn btn-primary" href="<?=url('/admin/main/add')?>" style="float:right">+</a></h2>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th width="20">#</th>
            <th width="250">Название</th>
            <th width="300">Автор</th>
            <th width="200">Издатель</th>
            <th width="50">Год</th>
            <th width="50">Страницы</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($posts as $item) : ?>
            <tr>
                <td><?=$item['id']?></td>
                <td><?=lib_substr($item['title'],25)?>...</td>
                <td><?=$item['author']?></td>
                <td><?=$item['publisher']?></td>
                <td><?=$item['year']?></td>
                <td><?=$item['pages']?></td>
                <td><a href="<?=url('/admin/main/edit-'.$item['id'])?>" class="btn btn-primary"><i class="glyphicon glyphicon-pencil"></i></a></td>
                <td><a href="<?=url('/admin?remove='.$item['id'])?>" class="btn btn-primary"><i class="glyphicon glyphicon-remove"></i></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <center>
        <div class="btn-group">
            <?php foreach ($pgn as $i => $page) : ?>
                <a href="<?=$page['url']?>" class="btn <?=($page['current']?'btn-primary':'btn-default')?>">
                    <?=($page['type'] == 'max' ? 'Последняя' : ( $page['type'] == 'min' ? 'Первая' :  $page['number']))?>
                </a>
            <?php endforeach; ?>
        </div>
    </center>
</div>
