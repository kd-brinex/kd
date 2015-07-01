<?php
use yii\widgets\DetailView;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 25.06.15
 * Time: 18:00
 */
//var_dump($model);die;
?>

<div class="col-md-6 col-sm-12">

<?= DetailView::widget([
    'model' => $model,
    'template' => "<tr><td>{value}</td></tr>",
    'attributes'=>[
//    'catalog',
//        'catalog_code',
//        'compl_code',
    [
        'label'=>'desc_en',
        'format'=>'raw',
        'value'=>Html::a($model['desc_en'], \yii\helpers\Url::to(array_merge(['page', $model])))

    ],
//        'desc_en',
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
            'value'=> Html::img(\app\modules\catalog\models\ToyotaQuery::getImageUrl()."/Img/".$model['catalog']."/".$model['rec_num']."/".$model['pic_code'].'.png',['height'=>'300px'])
            ]
    ]
   ]);?>
    </div>

