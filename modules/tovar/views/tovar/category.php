<?php
use yii\widgets\ListView;
use app\modules\tovar\categoryAsset;

categoryAsset::register($this);
?>
<div class="btn-group">
        <a class="btn" href="?viewType=1">&nbsp;<i class="icon-th icon-black"></i>&nbsp;</a>
        <a class="btn hidden-xs" href="?viewType=2">&nbsp;<i class="icon-th-list icon-black"></i>&nbsp;</a>
        <a class="btn" href="?viewType=3">&nbsp;<i class="icon-align-justify icon-black"></i>&nbsp;</a>
    </div>
<?php

$this->title=$params['tip_id'];
$this->params['breadcrumbs'][]=$this->title;
$headers = $params['viewType'] == 3 ? '<tr><th>Наименование товара</th><th>В магазине</th><th>На складе</th><th>Цена</th><th>Заказать</th></tr>' : '';
echo ListView::widget([
    'layout' => "$headers{items}\n<tr><td style=\"border:none;text-align: left\" colspan=\"5\">\n{summary}{pager}</td></tr>",
    'dataProvider' => $dataProvider,
    'options'=>$params['options'],

    'itemOptions' => $params['itemOptions'],

    'itemView' => function ($model) use ($params){
        return $this->render('tovars_block_view_'.$params['viewType'], ['model' => $model]);
    }
]);?>
