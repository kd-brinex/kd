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
//    var_dump($models);die;
    $img='';
    foreach ($models['models'] as $number=>$m){
if (empty($img)){$img=$m[0]['image'];}
        echo Collapse::widget([
            'items' => [
                [
                    'label' => $number.' - '.$m[0]['name'],
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
        <?= Html::img($img);?>
    </div>
    <?php
    foreach($models['labels'] as $labels){
//    var_dump($m[0]);die;
        foreach($labels as $m) {
            $label = '<div id="' . $m['number'] . '" data-position="1"  title="' . $m['name'] . '" class="page_label" style="left: ' . $m['x1'] . 'px; top: ' . $m['y1'] . 'px; width: ' . $m['width'] . 'px; height: ' . ($m['height']<20?'20':$m['height']) . 'px; ">' . $m['pnc'] . '</div>';
            echo $label;
        }

    }
    ?>
</div>