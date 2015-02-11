
<div class="btn-group">
        <a class="btn" href="?viewType=1">&nbsp;<i class="icon-th"></i>&nbsp;</a>
        <a class="btn" href="?viewType=2">&nbsp;<i class="icon-th-list"></i>&nbsp;</a>
        <a class="btn" href="?viewType=3">&nbsp;<i class="icon-align-justify"></i>&nbsp;</a>
    </div>
<?php
//echo $view;
//var_dump($view);die;
echo yii\widgets\ListView::widget([

    'dataProvider' => $dataProvider,

    'options'=>$params['options'],

    'itemOptions' => $params['itemOptions'],

    'itemView' => ($params['viewType']==1)?
        function ($model){return $this->render('tovars_block_view_1', ['model' => $model]);}:
        (($params['viewType']==2)?
            function ($model){return $this->render('tovars_block_view_2', ['model' => $model]);}:
            function ($model){return $this->render('tovars_block_view_3', ['model' => $model]);}),
]);?>
