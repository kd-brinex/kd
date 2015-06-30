<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 26.06.15
 * Time: 16:50
 */
$model=$dataProvider->query->url_params;
?>
<div class="col-md-6 col-xs-12">
<?= Html::img(\app\modules\catalog\models\ToyotaQuery::getImageUrl()."/Img/".$model['catalog']."/".$model['rec_num']."/".$model['pic_code'].'.png',['width'=>'100%']);?>
</div>
<div class="col-md-6 col-xs-12">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
//        ['attribute'=>'number',
//        'format'=>'raw',
//        'value'=>function($model){
//            return Html::a($model['number'],Url::to(['/finddetails','article'=>$model['number']]));
//}],
//
//        'desc_en',
//        'x1',
//        'y1',
//        'x2',
//        'y2',
//        'id',
    ]
    ]);?>
    </div>