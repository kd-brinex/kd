<?php
    use yii\widgets\ListView;
    use yii\widgets\Pjax;

?>
<div class="basketStepsBlock col-xs-12">
        <div id="step3" class="basketSteps"><i style="float: left;" class="icon-white icon-circle-success"></i>Выберите магазин в Вашем городе</div>
</div>
<h2>Выберите место получения товара в Вашем городе.</h2>
<div class="col-xs-12">
    <?php
        Pjax::begin();
            echo ListView::widget([
                'dataProvider' => $stores,
                'itemOptions' => ['class' => 'item col-xs-3 store-row'],
                'itemView' => function ($model, $key, $index, $widget) {
                        return $this->render('_store_view',['model' => $model]);
                },
                'options' => [
                    'id' => 'basketDeliveryList'
                ],
        ]);
    Pjax::end();
?>
</div>
<div class="col-xs-offset-10 col-xs-12">
    <button type="button" class="btn btn-error"  onclick="toggleTab(2)">Назад</button>
    <button type="button" class="btn btn-success" onclick="checkTab()">Далее</button>
</div>