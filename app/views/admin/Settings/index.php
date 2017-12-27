<h2 class="sub-header">Настройки</h2>
<form method="POST" action="">
    <table width="100%" class="table table-bordered">
        <tr>
            <td width="25%">Список регионов: <span style="float: right" class="add-region-item"><i class="glyphicon glyphicon-plus"></i></span></td>
            <td id="regions">
                <?php foreach ($regions as $region) {
                    echo '<div class="input-group">
                            <input type="text" class="form-control" name="regions[]" value="'.$region.'" placeholder="Регион">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="$(this).parent().parent().remove();"><i class="glyphicon glyphicon-remove"></i></button>
                            </span>
                        </div>';
                }?>
            </td>
        </tr>
        <tr>
            <td>Список городов: <span style="float: right" class="add-land-item"><i class="glyphicon glyphicon-plus"></i></span></td>
            <td id="lands">
                <?php foreach ($lands as $land) {
                    echo '<div class="input-group">
                            <input type="text" class="form-control" name="lands[]" value="'.$land.'" placeholder="Город">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="$(this).parent().parent().remove();"><i class="glyphicon glyphicon-remove"></i></button>
                            </span>
                        </div>';
                }?>
            </td>
        </tr>
        <tr>
            <td>Список городов: <span style="float: right" class="add-delivery-item"><i class="glyphicon glyphicon-plus"></i></span></td>
            <td id="delivery">
                <?php foreach ($delivery as $item) {
                    echo '<div class="input-group">
                            <input type="text" class="form-control" name="delivery[]" value="'.$item.'" placeholder="Доставка">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="$(this).parent().parent().remove();"><i class="glyphicon glyphicon-remove"></i></button>
                            </span>
                        </div>';
                }?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button name="save" class="btn btn-block btn-primary btn-lg">Сохранить</button>
            </td>
        </tr>
    </table>
</form>


<script type="text/javascript">
    $(document).ready(function () {
        $(".add-region-item").click(function () {
            var html = '<div class="input-group"><input type="text" class="form-control" name="regions[]" placeholder="Регион"><span class="input-group-btn"><button class="btn btn-default" type="button" onclick="$(this).parent().parent().remove();"><i class="glyphicon glyphicon-remove"></i></button></span></div>';
            $("#regions").append(html);
        });
        $(".add-land-item").click(function () {
            var html = '<div class="input-group"><input type="text" class="form-control" name="lands[]" placeholder="Город"><span class="input-group-btn"><button class="btn btn-default" type="button" onclick="$(this).parent().parent().remove();"><i class="glyphicon glyphicon-remove"></i></button></span></div>';
            $("#lands").append(html);
        });
        $(".add-delivery-item").click(function () {
            var html = '<div class="input-group"><input type="text" class="form-control" name="delivery[]" placeholder="Доставка"><span class="input-group-btn"><button class="btn btn-default" type="button" onclick="$(this).parent().parent().remove();"><i class="glyphicon glyphicon-remove"></i></button></span></div>';
            $("#delivery").append(html);
        });
    });
</script>