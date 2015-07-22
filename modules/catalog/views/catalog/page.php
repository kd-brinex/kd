<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 26.06.15
 * Time: 16:50
 */
//$model=$dataProvider->query->url_params;

//var_dump($model);die;

?>


<div class="page-scroll col-md-5 col-xs-12">

<?php
$this->params['breadcrumbs']= $params['breadcrumbs'];
foreach ($model['models'] as $number=>$m){
//    var_dump($model);die;
    echo Collapse::widget([
        'items' => [
            [
                'label' => (($m[0]['number_type']!=4)?$number.' - '.$m[0]['desc_en']:$number.' - ** Std Parts'),
//                'content'=>'',
                'content'=>$this->render('parts_group',['model'=>$m]),

                // Открыто по-умолчанию
                'options'=>['class'=>"panel-label col-xs-12 row",'id'=>$number],
//                    'contentOptions' => [  ]
            ],
        ]
    ]);
//    :'<div class="panel-group collapse in"><div class="col-xs-12 panel panel-default row"><div class="panel-heading"><h4 class="panel-title">'.Html::a($m[0]['number'].' - ** Std Parts',Url::to(['/finddetails','article'=>$m[0]['number']]),['target'=>'blank']).'</h4></div></div></div>');
}
?>
    </div>
<div class="col-md-7 col-xs-12">
    <div class="page_image">
        <?= Html::img(\app\modules\catalog\models\ToyotaQuery::getImageUrl()."/Img/".$model['params']['catalog']."/".$model['params']['rec_num']."/".$model['params']['pic_code'].'.png',[]);?>
    </div>
    <?php
    foreach($model['labels'] as $labels){
//    var_dump($m[0]);die;
        foreach($labels as $m) {
            $label = '<div id="' . $m['number'] . '" data-position="1"  title="' . $m['desc_en'] . '" class="page_label" style="left: ' . $m['x1'] . 'px; top: ' . $m['y1'] . 'px; width: ' . $m['width'] . 'px; height: ' . ($m['height']<20?'20':$m['height']) . 'px; ">' . $m['number'] . '</div>';
            echo $label;
        }

    }
    ?>
</div>