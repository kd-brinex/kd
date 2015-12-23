<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Json;

use app\modules\tovar\finddetailsAsset;

finddetailsAsset::register($this);



echo $this->render('_search');

$originalLimiter = 5;
$analogLimiter = 3;
$originalDetailsTotalLimiter = 10;
$analogDetailsTotalLimiter = 6;
if (!empty($provider->allModels)) {
    foreach ($provider->allModels as $key => $value) {
        $mas[$value['groupid']][] = $value;
    }

    for ($ii = 0; $ii < 3; $ii++) {
        $limiter = !$ii ? $originalLimiter : $analogLimiter;
        $totalLimiter = !$ii ? $originalDetailsTotalLimiter : $analogDetailsTotalLimiter;
        if (!isset($mas[$ii])) continue;
        $tablee[$ii] = "
        <table class='table table-bordered details-table' id = 'table$ii'>
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
        <tbody>";
        $manufacturer = '';                 //переменная для ограничения вывода количества запчастей
        $items = 0;                         //переменная для ограничения вывода количества запчастей
        $mas_count = count($mas[$ii]);
        for ($i = 0; $i < $mas_count; $i++) {
            if (!empty($mas[$ii][$i]['name'])) {
                // скрипт установки ограничения вывода (НАЧАЛО)
                $nowManufacturer = mb_strtoupper($mas[$ii][$i]['manufacture']);
                $items++;
                if($manufacturer != $nowManufacturer){
                    $manufacturer = $nowManufacturer;
                    $items = 0;
                    $tablee[$ii] .= '<tr><td colspan="9" style="padding:15px"></td></tr>';
                }
                if($items == $totalLimiter)
                    $tablee[$ii] .= '<tr class="itemsToggler"><td colspan="9"><a href="#" data-manufacturer="'.preg_replace('/[^a-zA-ZА-Яа-я0-9]/', '-',$nowManufacturer).'">Посмотреть другие предложения '.$nowManufacturer.'</a></td></tr>';

                if($items >= $totalLimiter) continue;
                // скрипт установки ограничения вывода (КОНЕЦ)

                $tablee[$ii] .= '<tr '.($items < $limiter ? '' : 'class="hidden-element" data-item-manufacturer="'.preg_replace('/[^a-zA-ZА-Яа-я0-9]/', '-',$nowManufacturer).'"').'>';// для ограничения вывода подставляются свойства класса и data-item-manufacturer
                $tablee[$ii] .= '<td>' . $nowManufacturer . '</td>';
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

    for ($i = 0; $i < 3; $i++){
        if (isset($tablee[$i])){
            $table[$i] = $tablee[$i]."<table class='table table-bordered' id = 'table$i$i' class='revert-bootstrap'></table>";
        } else {
            $table[$i] = "Ничего не найдено.";
        }
    }

    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Оригинал',// ('.(isset($mas[0])? count($mas[0]) : 0).')',
                'content' => "$table[0]",
                'active' => true
            ],
            [
                'label' => 'Оригинальная замена',// ('.(isset($mas[1])? count($mas[1]) : 0).')',
                'headerOptions' => [
                    'id' => 'ww2'
                ],
                'content' => "$table[1]",
            ],
            [
                'label' => 'Неоригинальная замена', // ('.(isset($mas[2])? count($mas[2]) : 0).')',
                'headerOptions' => [
                    'id' => 'ww3'
                ],
                'content' => "$table[2]",
            ],


        ]
    ]);
}

$this->registerJS('

    //скрипт показывает/скрывает запчасти после работы ограничителя

    $(".itemsToggler").on("click", "a", function(){
        var $this = $(this),
            manufacturer = $this.data("manufacturer"),
            button_text = $this.text(),
            items = $("tr[data-item-manufacturer="+manufacturer+"]");

        $this.toggleClass("shown-button");
        items.toggleClass("hidden-element shown-element");

        return false;
    });
');
