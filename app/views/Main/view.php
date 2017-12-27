<div class="view-item">
	<h4><?=$row['title']?></h4>
	<div class="view-content">
		<div class="view-image">
			<div class="img"><img src="<?=url('/public/upload/images/'.$row['image'])?>" /></div>
			<br />
			<button onclick="javascript:addInCart();" class="btn btn-block btn-danger">Добавить в корзину</button>
		</div>
		<div class="view-info">
			<ul>
				<li>Количество: <b><?=$row['count']?>шт.</b></li>
				<li>Цена: <b><?=$row['price']?>р</b></li>
				<li>Автор: <b><?=$row['author']?></b></li>
				<li>Издатель: <b><?=$row['publisher']?></b></li>
				<li>Язык: <b><?=$row['lang']?></b></li>
				<li>Год издания: <b><?=$row['year']?></b></li>
				<li>Количество страниц: <b><?=$row['pages']?></b></li>
				<li>Формат: <b><?=$row['format']?></b></li>
				<li>Категория: <b><?=$row['cat_name']?></b></li>
			</ul>
		</div>
	</div>
	<h4>Описание:</h4>
	<div class="description"><?=$row['description']?></div>
</div>
