<?php

use yii\helpers\Html;
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
        /*echo "<pre>";
print_r($mas[$ii]);
        echo "</pre>";*/

        $tablee[$ii] = "
        <table class='table table-bordered' id = 'table$ii'  >
        <thead>
        <tr>
        <th class='thd'>Производитель</th>
        <th class='thd'>Номер детали</th>
        <th class='thd'>Наименование</th>
        <th class='thd'>Наличие (шт.)</th>
        <th class='thd'>Заказ от(шт.)</th>
        <th class='thd dual'>Срок ожид.-гарант.</th>
        <th class='thd'>Цена</th>
        <th class='thd'>Баллы</th>
        <th></th>
        </tr>
        </thead>
        <tbody>
        ";

        for ($i = 0; $i < count($mas[$ii]); $i++) {
            if (!empty($mas[$ii][$i]['name'])) {
                $tablee[$ii] .= '<tr>';
                $tablee[$ii] .= '<td>' . $mas[$ii][$i]['manufacture'] . '</td>';
                $tablee[$ii] .= '<td>' . $mas[$ii][$i]['code'] . '</td>';
                $tablee[$ii] .= '<td>' . $mas[$ii][$i]['name'] . '</td>';

                if ($mas[$ii][$i]['estimation'] >= 85) $cl = 'fine';
                elseif ($mas[$ii][$i]['estimation'] < 25) $cl = 'bad';
                else $cl = 'good';

                $tablee[$ii] .= '<td><div title="Надежность поставщика (KD' . $mas[$ii][$i]['pid'] .'-'.$mas[$ii][$i]['storeid']. ')' . $mas[$ii][$i]['estimation'] . '% " class="square ' . $cl . '">' . $mas[$ii][$i]['quantity'] . '</div></td>';
                $tablee[$ii] .= '<td title="Минимальная партия заказа по которой действует цена на товар" '.($mas[$ii][$i]['lotquantity'] > 1 ? 'class="red"' : '').'>' . $mas[$ii][$i]['lotquantity'] . '</td>';
                $tablee[$ii] .= '<td>' . $mas[$ii][$i]['srok'] . '</td>';
                $tablee[$ii] .= '<td>' . $mas[$ii][$i]['price'] . '</td>';
                $tablee[$ii] .= '<td title="Количество начисляемых баллов. Баллы начисляются при покупке товара через сайт! Начисленные баллы становятся активными по истечении 14 дней с момента покупки.">' . $mas[$ii][$i]['ball'] . '<img src="/img/goods-bonuspoint.png" /></td>';
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

            $table[$i]=$tablee[$i]."<table class='table table-bordered' id = 'table$i$i' class='revert-bootstrap'  ></table>";
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
                'headerOptions' => [
                    'id' => 'ww2'
                ],
                'content' => "$table[1]",
            ],
            [
                'label' => 'Неоригинальная замена',
                'headerOptions' => [
                    'id' => 'ww3'
                ],
                'content' => "$table[2]",
            ],


        ]
    ]);
}



?>
