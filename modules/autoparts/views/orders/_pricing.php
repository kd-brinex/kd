<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 29.09.15
 * @time: 10:25
 */
use \kartik\grid\GridView;
use yii\helpers\Html;
?>

<?=GridView::widget([
    'id' => 'pricing-order-grid',
    'dataProvider' => $model,
    'responsive' => true,
    'hover' => true,

    'toolbar' => [
        [
            'options' => ['class' => 'btn-group-sm']
        ],
    ],
    'panel' => [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-tasks"></i> Позиции</h3>',
        'before' => Html::a('<i class="glyphicon glyphicon-triangle-left"></i> К заказам', ['#'], ['class' => 'btn btn-primary', 'onClick' => 'goTo(1); return false']),
        'beforeOptions' => ['class' => 'btn-group-sm'],
        'footer' => false,
    ],
])?>
