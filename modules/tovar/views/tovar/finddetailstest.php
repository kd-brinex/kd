<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;

use app\modules\tovar\tovarAsset;

tovarAsset::register($this);


foreach ($provider->allModels as $key => $value) {
    $mas[$value['groupid']][] = $value;

}


for ($ii = 0; $ii < 3; $ii++) {

    $tablee[$ii] = "
        <table id = 'user_list$ii'  class='table'>
        <thead>
        <tr>

        <th>Артикул</th>
        <th>Название</th>
        <th>Производитель</th>
        <th>Цена</th>
        <th>Количество</th>
        <th>Доставка</th>

        </tr>
        </thead>
        <tbody>
        ";

    for ($i = 0; $i < count($mas[$ii]); $i++) {
        if (!empty($mas[$ii][$i]['name'])) {
            $tablee[$ii] .= '<tr>';
            $tablee[$ii] .= '<td>' . $mas[$ii][$i]['code'] . '</td>';
            $tablee[$ii] .= '<td>' . $mas[$ii][$i]['name'] . '</td>';
            $tablee[$ii] .= '<td>' . $mas[$ii][$i]['manufacture'] . '</td>';
            $tablee[$ii] .= '<td>' . $mas[$ii][$i]['price'] . '</td>';
            $tablee[$ii] .= '<td>' . $mas[$ii][$i]['quantity'] . '</td>';
            $tablee[$ii] .= '<td>' . $mas[$ii][$i]['srokmax'] . '</td>';
            $tablee[$ii] .= '</tr>';
        }
    }

    $tablee[$ii] .= '</tbody></table>';
}

?>


<div class="center-td">
    <div class="sel-parts borders">
        <div class="sel-parts-1">
            <h2>Найти запчасть<br>по номеру детали</h2>
            <br>

            <form name="search" method="GET" action="">
                <input type="text" class="bootstrap-input" name="article" id="article">
                <input type="submit" class="btn" value="Найти">
            </form>
        </div>
        <div class="sel-parts-2">
            <h2>Номер детали<br>можно узнать в каталогах</h2>
            <br>
            <?= Html::a('Открыть каталог автомобилей', '/auto', ['class' => 'btn']); ?>
            <!--<a class="btn" href="http://kd.auto2d.com//">Открыть каталог автомобилей </a>-->
        </div>
        <div class="clearfix"></div>
    </div>
</div>


<?php

echo Tabs::widget([
    'items' => [
        [
            'label' => 'Оригинал',
            'content' => "$tablee[0]<table class='table table-bordered' id = 'user_list00' class='revert-bootstrap'  ></table>",
            'active' => true
        ],
        [
            'label' => 'Оригинальная замена',
            'content' => "$tablee[1]<table class='table table-bordered' id = 'user_list11' ></table>",
        ],
        [
            'label' => 'Неоригинальная замена',
            'content' => "$tablee[2]<table class='table table-bordered' id = 'user_list22' ></table>",
        ],


    ]
]);

?>
