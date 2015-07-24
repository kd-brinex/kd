<?php
use yii\widgets\ListView;?>
<div class="btn-group">
        <a class="btn" href="?viewType=1">&nbsp;<i class="icon-th icon-black"></i>&nbsp;</a>
        <a class="btn hidden-xs" href="?viewType=2">&nbsp;<i class="icon-th-list icon-black"></i>&nbsp;</a>
        <a class="btn" href="?viewType=3">&nbsp;<i class="icon-align-justify icon-black"></i>&nbsp;</a>
    </div>
<?php
Yii::$app->view->registerCssFile('/css/style-offer.css');

$this->title=$params['tip_id'];
$this->params['breadcrumbs'][]=$this->title;
echo ListView::widget([
    'summary'=>'',
    'dataProvider' => $dataProvider,
//    'showHeader' => false,
    'options'=>$params['options'],

    'itemOptions' => $params['itemOptions'],

    'itemView' => ($params['viewType']==1
    )?
        function ($model){return $this->render('tovars_block_view_1', ['model' => $model]);}:
        (($params['viewType']==2)?
            function ($model){return $this->render('tovars_block_view_2', ['model' => $model]);}:
            function ($model){return $this->render('tovars_block_view_3', ['model' => $model]);}),
]);?>
