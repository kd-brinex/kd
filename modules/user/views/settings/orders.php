<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 23.03.15
 * Time: 12:13
 */
use yii\helpers\Html;
use yii\jui\Tabs;

Yii::$app->view->registerCssFile('/css/style-offer.css');
$this->title = Yii::t('user', 'My orders');
$this->params['breadcrumbs'][] = $this->title;

//var_dump($orders);die;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
<!-- Modal -->
<div id="order-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Информация о заказе</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" style="display: inline-block" class="btn btn-info" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal (end) -->
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
                <div id="orders">
                    <?=Tabs::widget([
                        'items' => [
                            [
                                'label' => 'Активные заказы',
                                'content' => $this->render('_new_orders', ['orders' => $new_orders, 'model' => $model])
                            ],
                            [
                                'label' => 'Архив',
                                'content' => $this->render('_old_orders', ['orders' => $old_orders, 'model' => $model])
                            ],
                            [
                                'label' => 'Позиции',
                                'content' => $this->render('_order', ['orders' => $orders, 'model' => $morders])
                            ]
                        ]
                    ])?>
                </div>
            </div>
        </div>
    </div>
<!--    <div class="col-md-3">-->
<?php //echo $this->render('_menu') ?>
<!--    </div>-->
</div>