<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 23.10.15
 * @time: 14:21
 */

\yii\widgets\Pjax::begin([
    'id'=>'manager-order-grid-pjax-container']);
    echo \kartik\editable\Editable::widget([
            'model' => $model,
            'attribute' => 'order_provider_id',
            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
            'pjaxContainerId' => 'modal-body',
                'formOptions' => [
//                    'id' => 'asdasd'.rand()
                ]
       ]);
\yii\widgets\Pjax::end();
?>
