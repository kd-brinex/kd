<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 12.10.15
 * Time: 15:10
 */
use yii\helpers\Html;
use yii\widgets\Pjax;
//var_dump($params);die;

//var_dump($params);die;
Pjax::begin();
?>
<div class="models">
    <?= Html::beginForm('/autocatalogs/'.$params['marka'].'/podbor','get',['name'=>'podbor','data-pjax' =>true]);?>

<?= Html::label('Модель:','family')?>
    <?=Html::dropDownList('family',$params['family'],$params['familys'],['id'=>'family'])?>
<br>
    <?= (!empty($params['years']))?Html::label('Год выпуска:','year').Html::dropDownList('year',$params['year'],$params['years'],['id'=>'year']):''?>
<br>
<?= (!empty($params['engines']))?Html::label('Двигатель:','engine').Html::dropDownList('engine',$params['engine'],$params['engines'],['id'=>'engine']):''?>
<br>
<?= Html::submitButton('Загрузить');?>
<?= Html::endForm();?>
    <?php
Pjax::end();