<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.08.15
 * Time: 16:41
 */
use yii\helpers\Html;

var_dump($model);die;
// Список радио для выбора года
$car_year=array_merge(['Неизвестно'],explode('|',$model['f_years']));

// Список радио для выбора региона
$car_region[]='Неизвестно';
$car_region=array_merge($car_region,explode('|',substr($model['data_regions'],0,-1)));
?>
<div class="col-xs-12 col-md-3">
    <?= Html::img($model['f_image_path'] . '/Titles/' . $model['image'] . '.png', ['width' => '99%']) ?>
    <?= Html::tag('dt', $model['catalog_code'], ["class" => "dl-horizontal"]) ?>
    <?= Html::tag('dt', $model['catalog_name'], ["class" => "dl-horizontal"]) ?>
    <?= Html::tag('d1', Html::tag('dt', 'Модельный ряд:') . Html::tag('dd', $model['model_name']), ["class" => "dl-horizontal"]) ?>
    <?= Html::tag('d1', Html::tag('dt', 'Регион:') . Html::tag('dd', $model['data_regions']), ["class" => "dl-horizontal"]) ?>
    <?= Html::tag('d1', Html::tag('dt', 'Тип автомобиля:') . Html::tag('dd', $model['vehicle_type']), ["class" => "dl-horizontal"]) ?>
    <?= Html::tag('d1', Html::tag('dt', 'Дата производства:') . Html::tag('dd', $model['date_start'] . '-' . $model['date_end']), ["class" => "dl-horizontal"]) ?>
</div>
<div class="col-xs-12 col-md-9">


    <form action="view_veh_major.php">
        <input type="hidden" name="lang_code" value="RU">
        <input type="hidden" name="catalog_code" value="$model['catalog_code']">
        <?= Html::tag('blockquote', 'Данные об автомобиле') ?>
        <?= Html::tag('d1',
            Html::tag('dt', 'Год:') .
            Html::tag('dd',  Html::radioList('car_year',null,$car_year))
            , ["class" => "dl-horizontal"]) ?>
        <?= Html::tag('d1', Html::tag('dt', 'Регион:') .
            Html::tag('dd',Html::radioList('car_region',$model['region'],$car_region))
            , ["class" => "dl-horizontal"]
        ) ?>
        <?= Html::tag('blockquote', 'Основные характеристики', ['class' => '']) ?>
        <table style="empty-cells:show; border-collapse:collapse">
            <tbody>
            <tr class="line">
                <td align="right" class="line">
                    <b>Кузов: </b></td>
                <td>
                    <div class="divlink"><input type="radio" name="vo_body_type" value="" checked="">Неизвестно</div>
                    <div class="divlink"><input type="radio" name="vo_body_type" value="A">[A] - 5 DOOR</div>
                    <div class="divlink"><input type="radio" name="vo_body_type" value="D">[D] - 3 DOOR</div>
                    <div class="divlink"><input type="radio" name="vo_body_type" value="F">[F] - 4 DOOR</div>
                    <div style="clear:both;"></div>
                </td>
            </tr>
            <tr class="line">
                <td align="right" class="line"><b>Класс: </b></td>
                <td>
                    <div class="divlink"><input type="radio" name="vo_grade" value="" checked="">Неизвестно</div>
                    <div class="divlink"><input type="radio" name="vo_grade" value="D">[D] - MIDDLE GRADE</div>
                    <div class="divlink"><input type="radio" name="vo_grade" value="G">[G] - HIGH GRADE</div>
                    <div style="clear:both;"></div>
                </td>
            </tr>
            <tr class="line">
                <td align="right" class="line"><b>Рабочий объем двигателя: </b></td>
                <td>
                    <div class="divlink"><input type="radio" name="vo_engine_capacity" value="" checked="">Неизвестно
                    </div>
                    <div class="divlink"><input type="radio" name="vo_engine_capacity" value="L">[L] - 1300 CC</div>
                    <div class="divlink"><input type="radio" name="vo_engine_capacity" value="P">[P] - 1500 CC</div>
                    <div style="clear:both;"></div>
                </td>
            </tr>
            <tr class="line">
                <td align="right" class="line"><b>Топливо: </b></td>
                <td>
                    <div class="divlink"><input type="radio" name="vo_fuel_type" value="" checked="">Неизвестно</div>
                    <div class="divlink"><input type="radio" name="vo_fuel_type" value="A">[A] - AUTO TRANSAXLE</div>
                    <div class="divlink"><input type="radio" name="vo_fuel_type" value="D">[D] - MANUAL TRANSAXLE</div>
                    <div style="clear:both;"></div>
                </td>
            </tr>
            <tr class="line">
                <td align="right" class="line"><b>Трансмиссия: </b></td>
                <td>
                    <div class="divlink"><input type="radio" name="vo_transmission" value="" checked="">Неизвестно</div>
                    <div class="divlink"><input type="radio" name="vo_transmission" value="B">[B] - 4 SPEED</div>
                    <div class="divlink"><input type="radio" name="vo_transmission" value="D">[D] - 5 SPEED</div>
                    <div style="clear:both;"></div>
                </td>
            </tr>
            <tr class="line">
                <td align="right" class="line"><b>Special Car: </b></td>
                <td>
                    <div class="divlink"><input type="radio" name="vo_special_car" value="E" checked="">[E] - EUROPE
                    </div>
                    <div style="clear:both;"></div>
                </td>
            </tr>
            <tr class="line">
                <td align="right" class="line"><b>Тип Управления: </b></td>
                <td>
                    <div class="divlink"><input type="radio" name="veh_drive_type" value="" checked="">Неизвестно</div>
                    <div class="divlink"><input type="radio" name="veh_drive_type" value="L">[L] - LEFT HAND DRIVE</div>
                    <div class="divlink"><input type="radio" name="veh_drive_type" value="R">[R] - RIGHT HAND DRIVE
                    </div>
                    <div style="clear:both;"></div>
                </td>
            </tr>
            <tr>
                <td class="line">&nbsp;</td>
                <td><a href="?lang_code=RU&amp;catalog_code=EUR2209500" title="">Сброс</a></td>
            </tr>
            </tbody>
        </table>
        <br>
        <input type="submit" value="Загрузить" style="height:50px; width:200px">


    </form>
</div>
