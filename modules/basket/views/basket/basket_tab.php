<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 26.02.15
 * Time: 10:20
 */
use yii\grid\GridView;
use yii\helpers\Html;
Yii::$app->view->registerCssFile('/css/style-offer.css');

?>
<div class="basketStepsBlock col-xs-12">
    <div id="step1" class="basketSteps" style="display:block"><i style="float: left;" class="icon-white icon-circle-success"></i>Выберите товары для заказа</div>
</div>
<h1>Ваша корзина.</h1>
<div id="basket">
    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
        <?=GridView::widget([
           'id' => 'BasketGrid',
           'dataProvider' => $model,
           'columns' => [
               [
                   'attribute' => 'manufacturer',
                   'label' => 'Производитель'
               ],
               [
                    'label' => 'Номер детали',
                    'value' => function($model){
                            return $model['tovar_id'] ? $model['tovar_id']:$model['part_number'];
                    }
               ],
               [
                   'label' => 'Название детали',
                   'value' => function($model){
                       return $model['part_name'] ? $model['part_name']:$model['tovar']['name'];
                   }
               ],
               [
                   'attribute' => '',
                   'label' => 'Цена',
                   'value' => function($model){
                        return number_format($model['tovar_price'], 2,'.','');
                   },
                   'contentOptions' => [
                       'class' => 'itemPrice'
                   ]
               ],
               [

                   'label' => 'Кол-во ед.',
                   'format' => 'raw',
                   'value' => function($model){
                       return '<input type="number" class="form-control" onChange="countBasketSum()" value="'.$model['tovar_count'].'">';
                   },
                   'contentOptions' => [
                       'class' => 'itemCount'
                   ]

               ],
               [
                   'attribute' => 'allsum',
                   'label' => 'Сумма',
                   'value' => function($model){
                      return number_format($model['tovar_price'], 2,'.','');
                   },
                   'contentOptions' => [
                       'class' => 'itemFullPrice'
                   ]
               ],
               [
                   'attribute' => 'period',
                   'label' => 'Срок'
               ],
               [

                   'label' => 'Описание',
                   'format' => 'raw',
                   'value' => function($model){
                       $description = $model['description'] ? $model['description'] : 'Ввести описание';
                       $data = Html::a('<i class="icon-edit icon-white"></i>','#',['class' => 'grid-right-up-corner', 'onClick' => 'editText(this)', 'title'=>'Редактировать описание']);
                       $data .= Html::a('<i class="icon-cross icon-white"></i>','#',['class' => 'grid-left-up-corner', 'onClick' => 'cancelEdit(this)', 'title'=>'Отменить редактирование']);
                       $data .= '<span id="oldText" style="display: none"></span>';
                       $data .= Html::textarea('itemDescription', $description, ['readonly' => true]);
                       return $data;

                   },
                   'contentOptions' => [
                        'class' => 'itemDescription',
                   ]
               ],
               [
                   'class' => 'yii\grid\CheckboxColumn',
                   'checkboxOptions' => [
                       'onChange' => 'countBasketMarkedItemsSum()'
                   ],
                   'header' => yii\helpers\Html::checkBox('selection_all', false, [
                       'class' => 'select-on-check-all',
                       'onChange' => 'countBasketMarkedItemsSum()'
                   ]),
               ]
           ],
        ]);
        ?>

    </div>
    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
        <div class="basket-grid-footer">
            <div class="basked-all-items">В корзине товаров на сумму: <strong><?=Yii::$app->formatter->asCurrency($itogo['tovar_summa'])?></strong></div>
            <div class="basket-marked-items">Выбрано позиций <strong>0</strong>, на сумму <strong>0,00</strong> руб.</div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-9 col-xs-12">
            <?= \yii\helpers\Html::button('Удалить', ['class' => 'btn btn-error', 'onClick' => 'removeBasketItems()'])?>
            <?= \yii\helpers\Html::button(Yii::t('user', 'Оформить'), ['class' => 'btn btn-success', 'onClick' => 'toggleTab(2)']) ?>
        </div>
    </div>


</div>
