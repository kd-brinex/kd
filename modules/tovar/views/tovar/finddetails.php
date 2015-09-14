<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\Json;

use app\modules\tovar\tovarAsset;

tovarAsset::register($this);
Yii::$app->view->registerCssFile('/css/parts.css');
if (!empty($provider->allModels)) {
    foreach ($provider->allModels as $key => $value) {
        $mas[$value['groupid']][] = $value;

    }

    for ($ii = 0; $ii < 3; $ii++) {
        if (!isset($mas[$ii]))continue;

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
        <th>Баллы</th>
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
//                $tablee[$ii] .= '<td>' . $mas[$ii][$i]['provider'] . '</td>';
                if ($mas[$ii][$i]['lotquantity'] > 1) {
                    $tablee[$ii] .= '<td><div class="red">' . $mas[$ii][$i]['lotquantity'] . '</div></td>';
                } else {
                    $tablee[$ii] .= '<td>' . $mas[$ii][$i]['lotquantity'] . '</td>';
                }
                if ($mas[$ii][$i]['estimation'] >= 90) $cl = 'fine';
                elseif ($mas[$ii][$i]['estimation'] < 90) $cl = 'good';
                else $cl = 'bad';
                $tablee[$ii] .= '<td><div title="Надежность поставщика (склад ' . $mas[$ii][$i]['provider'] . ')' . $mas[$ii][$i]['estimation'] . '% " class="' . $cl . '">' . $mas[$ii][$i]['srokmax'] . '</div></td>';
                $tablee[$ii] .= '<td>' . $mas[$ii][$i]['ball'] . '</td>';
                $key = $ii . '-' . $i;
                $tablee[$ii] .= '<td>' . Html::a('<i class="icon-shopping-cart icon-white "></i>Заказать', '#', [
                        'title' => 'Заказать',

                        'class' => 'btn btn-primary btn-xs orderBud' . $key . '',
                        'onClick' => '$.ajax({ type :"POST", "data" : ' . Json::encode($mas[$ii][$i]) . ', url : "' . \yii\helpers\Url::to(['/tovar/tovar/basket']) . '", success : function(d) { $(".orderBud' . $key . '").parent().html(d) } });return false;'
                    ]) . '</td>';
                $tablee[$ii] .= '</tr>';
            }
        }

        $tablee[$ii] .= '</tbody></table>';
    }
    for ($i = 0; $i < 3; $i++)
    {

        if (isset($tablee[$i]))

        {

            $table[$i]=$tablee[$i]."<table class='table table-bordered' id = 'user_list$i$i' class='revert-bootstrap'  ></table>";
        }
        else
        {
            $table[$i]="Ничего не найдено.";
        }
    }


    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Оригинал',
                'content' => "$table[0]",
                'active' => true
            ],
            [
                'label' => 'Оригинальная замена',
                'content' => "$table[1]",
            ],
            [
                'label' => 'Неоригинальная замена',
                'content' => "$table[2]",
            ],


        ]
    ]);
}



?>
