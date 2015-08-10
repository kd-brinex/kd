<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 23.03.15
 * Time: 12:13
 */
use yii\helpers\Html;
use yii\grid\GridView;

Yii::$app->view->registerCssFile('/css/style-offer.css');
$this->title = Yii::t('user', 'My orders');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">

    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
                <div id="orders">
                        <?=GridView::widget([
                            'id' => 'OrdersGrid',
                            'rowOptions' => function($model){
                                return ['class' => 'gridRowStateBgColor'.$model['status']];
                            },
                            'dataProvider' => $model,
                            'columns' => [
                                [
                                    'label' => 'ID',
                                    'attribute' => 'id'
                                ],
                                [
                                    'header' => '<span style="color:#43b2ff">Производитель</span> /<br><span style="font-weight: normal">Номер детали</span>',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        return '<strong style="color: #43b2ff">' .$model['manufacture'].'</strong><br> '.$model['product_id'];
                                    }
                                ],
                                [
                                    'label' => 'Название детали',
                                    'attribute' => 'part_name'
                                ],
                                [
                                    'label' => 'Цена',
                                    'attribute' => 'part_price'
                                ],
                                [
                                    'label' => 'Кол-во',
                                    'attribute' => 'quantity'
                                ],
                                [
                                    'label' => 'Сумма',
                                    'value' => function($model){
                                        return $model['quantity']*$model['part_price'];
                                    }
                                ],
                                [
                                    'label' => 'Срок',
                                    'attribute' => 'datetime'
                                ],
                                [
                                    'label' => 'Статус',
                                    'attribute' => 'state.status_name'
                                ],
                                [
                                    'header' => 'Заказ с сайта/<br>Референс',
                                    'attribute' => 'reference'
                                ],
                            ]
                        ]);

                        ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <?php
    /**
     * Created by PhpStorm.
     * User: marat
     * Date: 26.02.15
     * Time: 10:20
     */


    ?>

</div>