<?php
use yii\widgets\DetailView;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 25.06.15
 * Time: 18:00
 */
?>
<div class="album-block col-xs-4">
<?= DetailView::widget([
    'model' => $model,
    'attributes'=>[
//    'catalog',
//        'catalog_code',
//        'compl_code',
        'desc_en',
//        'end_date',
//        'ftype',
//        'illust_no',
//        'ipic_code',
//        'op1',
//        'op2',
//        'op3',
//        'part_groupe',
//        'pic_code',
//        'pic_desc_code',
//        'start_date',
        [   'label'=>'pic_code',
            'format'=>'raw',
            'value'=> Html::img(\app\modules\catalog\models\ToyotaQuery::getImageUrl().$model['pic_code'].'.png',['height'=>'300px'])
            ]
    ]
   ]);?>
    </div>
