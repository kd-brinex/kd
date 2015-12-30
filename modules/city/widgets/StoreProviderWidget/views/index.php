<div class="row">
    <div class="col-xs-6">
    <h2>Учетки</h2>
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $provider_user,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
//                'login',
//                'password',
                [
                  'label'=>'Поставщик',
                  'attribute'=>'provider.name'
                ],
                [
                  'label'=>'Разрешен',
                  'attribute'=>'provider.enable',
                    'format'=>'boolean',
                ],
                'marga',
                ['class' => 'yii\grid\ActionColumn',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        return [\yii\helpers\Url::to('/autoparts/provideruser/' . $action), 'id' => $model['id']];
                    },
                ],
            ],
        ]); ?>
    </div>

    <div class="col-xs-6">
<h2>Срок доставки</h2>
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $provider_srok,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'providerName',
                'city_id',
                'cityName',
                'days',
                ['class' => 'yii\grid\ActionColumn',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        return [\yii\helpers\Url::to('/autoparts/providersrok/' . $action), 'id' => $model['id']];
                    },
                ],
            ],
        ]); ?>

    </div>
</div>