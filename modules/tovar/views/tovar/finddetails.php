<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\Json;

use app\modules\tovar\tovarAsset;

tovarAsset::register($this);



foreach ($provider->allModels as $key => $value) {
    $mas[$value['groupid']][] = $value;

}


var_dump($provider);die;
for ($ii = 0; $ii < 3; $ii++) {

    $tablee[$ii] = "
        <table class='table table-bordered' id = 'user_list$ii'  >
        <thead>
        <tr>

        <th>Артикул</th>
        <th>Производитель</th>
        <th>Название</th>
        <th>Цена</th>
        <th>Количество</th>
        <th>Заказ от(шт.)</th>
        <th>Доставка</th>
        <th></th>

        </tr>
        </thead>
        <tbody>
        ";

    for ($i = 0; $i < count($mas[$ii]); $i++) {
        if (!empty($mas[$ii][$i]['name'])) {
            $tablee[$ii] .= '<tr>';
            $tablee[$ii] .= '<td>' . $mas[$ii][$i]['code'] . '</td>';
            $tablee[$ii] .= '<td>' . $mas[$ii][$i]['manufacture'] . '</td>';
            $tablee[$ii] .= '<td>' . $mas[$ii][$i]['name'] . '</td>';
            $tablee[$ii] .= '<td>' . $mas[$ii][$i]['price'] . '</td>';
            $tablee[$ii] .= '<td>' . $mas[$ii][$i]['quantity'] . '</td>';
            if ($mas[$ii][$i]['lotquantity']>1) {$tablee[$ii] .= '<td><div class="red">' . $mas[$ii][$i]['lotquantity'] . '</div></td>';}
            else {$tablee[$ii] .= '<td>' . $mas[$ii][$i]['lotquantity'] . '</td>';}
            if ($mas[$ii][$i]['estimation']>=90) $cl='fine';
                elseif ($mas[$ii][$i]['estimation']<90) $cl='good';
                    else $cl='bad';
            $tablee[$ii] .= '<td><div title="Надежность поставщика (склад '.$mas[$ii][$i]['flagpostav'].'-'.$mas[$ii][$i]['storeid'].')'.$mas[$ii][$i]['estimation'].'% " class="'.$cl.'">' . $mas[$ii][$i]['srokmax'] . '</div></td>';
            $key = $ii.'-'.$i;
            $tablee[$ii] .= '<td>'. Html::a('<i class="icon-shopping-cart icon-white "></i>Заказать', '#', [
                    'title' => 'Заказать',

                    'class' => 'btn btn-primary btn-xs orderBud'.$key.'',
                    'onClick' => '$.ajax({ type :"POST", "data" : '.Json::encode($mas[$ii][$i]).', url : "'.\yii\helpers\Url::to(['tovar/basket']).'", success : function(d) { $(".orderBud'.$key.'").parent().html(d) } });return false;'
                ]).'</td>';
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
