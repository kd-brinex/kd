<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 15:58
 */

use kartik\grid\GridView;
use yii\widgets\DetailView;
use \yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 11:22
 */
//var_dump($info->models);die;
echo (!empty($params['breadcrumbs'])) ? Breadcrumbs::widget(['links' => $params['breadcrumbs']]) : '';
?>

    <div class="auto-info">

        <?= DetailView::widget([
                'model' => $info->models[0],
                'template' => '<tr><th>{label}</th><td class="upper">{value}</td></tr>',
                'attributes' => [
                    'cat_code',
                    'marka',
                    'family',
                    'cat_name',
                    'from',
                    'to',
                    [
                        'attribute' => 'vehicle_type',
                        'format' => 'raw',
                        'value' => Html::tag('span', Yii::t('autocatalog', $info->models[0]->vehicle_type), ['class' => 'upper']),

                    ],

                ],
            ]
        ); ?>
    </div>
    <div class="models">
        <?= Html::beginForm('', 'post', ['name' => 'catalog']); ?>
        <?= GridView::widget([
            'dataProvider' => $provider,
//        'showHeader' => false,
            'layout' => "{items}\n{pager}",
            'panelTemplate' => '<div class="panel {type}">{sort}</div>',
//        'bootstrap' =>false,
            'columns' => [
                ['attribute' => 'name',
                    'label' => 'Характеристики',
                    'value' => function ($model, $key, $index, $widget) {
                        return Yii::t('autocatalog', $model['name']);

                    }
                ],
                [
                    'attribute' => 'value',
                    'format' => 'raw',
                    'label' => 'Варианты',
                    'value' => function ($model, $key, $index, $widget) use ($params) {
                        $key[0] = '';
                        $values[0] = 'Unknown';
                        $keys = array_merge($key, explode(';', $model['key']));
                        $values = array_merge($values, explode(';', $model['value']));

//                    $select=(!empty($params['option']))?explode('|',$params['option'])[$index]:$key[0];
//                    var_dump($keys);die;
                        $select = $keys[0];
                        if (!empty($params['option'])) {
                            $option = str_replace('  ', '|', $params['option']);
                            while (strpos($option, '||') > 0) {
                                $option = str_replace('||', '|', $option);
                            }
                            $options = explode('|', $option);
                            $select = (!empty($options[$index])) ? $options[$index] : $select;

                        }

                        $val = array_combine($keys, $values);
                        $html = Html::radioList($model['type_code'], $select, $val, []);
                        return $html;
                    },],


            ],

        ]); ?>

        <?= Html::endForm(); ?>

        <?= GridView::widget([
            'dataProvider' => $podbor,
            'layout' => "{items}\n{pager}",
            'columns' => [

//    'cat_code',
//    'cat_folder',
//    'option',
                [
                    'label' => 'Найденные автокаталоги',
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $widget) use ($params) {
                        return Html::a(Html::button('Перейти к подбору автозапчастей - ' . strtoupper($params['marka']) . ' ' . $params['family'] . '. ' . $model['cat_folder'] . ' (' . $params['option'] . ')', ['class' => "btn btn-success", 'id' => 'catalog_button']), \yii\helpers\Url::to(base64_encode($params['option']) . '/' . $model['cat_folder']));
//            return Html::a('Каталог',\yii\helpers\Url::to($model['cat_code'].'/'.$model['cat_folder'].'/'.$params['option']));
                    },
                ]
            ]

        ]); ?>

    </div>

<?php
Yii::$app->view->registerCss('
.circle {
	background-color: rgba(0,0,0,0);
	border:5px solid rgba(0,183,229,0.9);
	opacity:.9;
	border-right:5px solid rgba(0,0,0,0);
	border-left:5px solid rgba(0,0,0,0);
	border-radius:50px;
	box-shadow: 0 0 35px #2187e7;
	width:50px;
	height:50px;
	margin:0 auto;
	-moz-animation:spinPulse 1s infinite ease-in-out;
	-webkit-animation:spinPulse 1s infinite linear;
}
.circle1 {
	background-color: rgba(0,0,0,0);
	border:5px solid rgba(0,183,229,0.9);
	opacity:.9;
	border-left:5px solid rgba(0,0,0,0);
	border-right:5px solid rgba(0,0,0,0);
	border-radius:50px;
	box-shadow: 0 0 15px #2187e7;
	width:30px;
	height:30px;
	margin:0 auto;
	position:relative;
	top:-40px;
	-moz-animation:spinoffPulse 1s infinite linear;
	-webkit-animation:spinoffPulse 1s infinite linear;
}
@-moz-keyframes spinPulse {
	0% { -moz-transform:rotate(160deg); opacity:0; box-shadow:0 0 1px #2187e7;}
	50% { -moz-transform:rotate(145deg); opacity:1; }
	100% { -moz-transform:rotate(-320deg); opacity:0; }
}
@-moz-keyframes spinoffPulse {
	0% { -moz-transform:rotate(0deg); }
	100% { -moz-transform:rotate(360deg);  }
}
@-webkit-keyframes spinPulse {
	0% { -webkit-transform:rotate(160deg); opacity:0; box-shadow:0 0 1px #2187e7; }
	50% { -webkit-transform:rotate(145deg); opacity:1;}
	100% { -webkit-transform:rotate(-320deg); opacity:0; }
}
@-webkit-keyframes spinoffPulse {
	0% { -webkit-transform:rotate(0deg); }
	100% { -webkit-transform:rotate(360deg); }
}
.content-load {width:800px; margin:0 auto; padding-top:50px;}
.container-load {width: 960px; margin: 0 auto; overflow: hidden;}
');
Yii::$app->view->registerJs(
    '
      $("input").change(function(){
      $("#w3-container").html(\'<div class="container-load"><div class="content-load"><div class="circle1"></div></div></div>\');
      $("form").submit();
      });
'
);
?>
<!--<div class="container-load"><div class="content-load"><div class="circle"></div><div class="circle1"></div></div></div>-->

