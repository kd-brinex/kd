<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\helpers\Url;
use yii\bootstrap\Tabs;

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
    $img = '';
    foreach ($models['models'] as $number => $m) {
//if (empty($img)){$img=$m[0]['image'];}
        echo Collapse::widget([
            'items' => [
                [
                    'label' => $number ,
//                'content'=>'',
                    'content' => $this->render('parts_group', ['model' => $m]),

                    // Открыто по-умолчанию
                    'options' => ['class' => "panel-label col-xs-12 row", 'id' => $number],
//                    'contentOptions' => [  ]
                ],
            ]
        ]);
//    :'<div class="panel-group collapse in"><div class="col-xs-12 panel panel-default row"><div class="panel-heading"><h4 class="panel-title">'.Html::a($m[0]['number'].' - ** Std Parts',Url::to(['/finddetails','article'=>$m[0]['number']]),['target'=>'blank']).'</h4></div></div></div>');
    }
    ?>
</div>
<div class="col-md-7 col-xs-12">

        <?php
        foreach ($images->models as $img) {

        $label='';
        foreach ($models['labels'] as $labels) {
            foreach ($labels as $m) {
//                var_dump($m['page'] , $img['page']);
                if ($m['page'] == $img['page']) {
                    $label .= '<div id="' . $m['pnc'] . '" data-position="1"  title="' . $m['name'] . '" class="page_label" style="left: ' . $m['x1'] . 'px; top: ' . $m['y1'] . 'px; width: ' . $m['width'] . 'px; height: ' . ($m['height'] < 20 ? '20' : $m['height']) . 'px; ">' . $m['pnc'] . '</div>';

                }
            }
        }
            $items [] =
                ['label' => $img->page,
                    'content' => Html::tag('div', Html::img($img->image) .$label,['class'=>'page_image'])];

        }
        echo Tabs::widget(['items' => $items]);

        ?>






</div>