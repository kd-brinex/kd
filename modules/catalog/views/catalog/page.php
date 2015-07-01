<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Collapse;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 26.06.15
 * Time: 16:50
 */
//$model=$dataProvider->query->url_params;

//var_dump($model);die;
?>

<div class="col-md-6 col-xs-12">
<?= Html::img(\app\modules\catalog\models\ToyotaQuery::getImageUrl()."/Img/".$model['params']['catalog']."/".$model['params']['rec_num']."/".$model['params']['pic_code'].'.png',['width'=>'100%']);?>
</div>
<div class="col-md-6 col-xs-12">

<?php
foreach ($model['models'] as $number=>$model){
//    var_dump($model);die;
    echo Collapse::widget([
        'items' => [
            [
                'label' => $number.' - '.$model[0]['desc_en'],
//                'content'=>'',
                'content'=>$this->render('parts_group',['model'=>$model]),
                // Открыто по-умолчанию
                'options'=>['class'=>"col-xs-12 row"],
//                    'contentOptions' => [  ]
            ],
        ]
    ]);
}
//var_dump($dataProvider);die;
//GridView::widget([
//    'dataProvider' => $dataProvider,
//    'columns' => [
//        ['attribute'=>'number',
//        'format'=>'raw',
//        'value'=>function($model){
//            return Html::a($model['part_code'],Url::to(['/finddetails','article'=>$model['part_code']]));
//}],
//'number',
//        'desc_en',
//        'x1',
//        'y1',
//        'x2',
//        'y2',
//        'id',
////        'part_code'
//    ]
//    ]);
?>
    </div>