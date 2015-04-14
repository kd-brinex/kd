<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.04.15
 * Time: 10:26
 */

$this->params['breadcrumbs'][] = ['label'=>'Каталог','url'=>['/auto']];
$this->params['breadcrumbs'][] = ['label'=>$models->typeName,'url'=>['/auto/marks/'.$params['typeid']]];
$this->params['breadcrumbs'][] = $models->markName;
Yii::$app->view->registerCssFile('/assets/auto/css/style.css');
Yii::$app->view->registerCssFile('/assets/auto/css/adc.css');
Yii::$app->view->registerCssFile('/assets/auto/css/fw.css');
//$wroot = '../modules/auto/catalogs/auto2d/';
//$cssView = file_get_contents($wroot . 'media/css/fw.css');
//$cssView .= file_get_contents($wroot . 'media/css/style.css');
//$cssView .= file_get_contents($wroot . 'media/css/adc.css');
//$this->registerCss($cssView);
//var_dump($models);?>
<div id="AutoDealer">
    <h1>Список моделей <?= $models->markName ?> </h1>



    <?php foreach ($models->models AS $modelCode => $model) { ?>

        <div class="col-xs-12 col-lg-6">
            <a href="/auto/tree/<?= $model->model_id ?>">
                <div class="modelItem">
                    <div class="col-xs-6">
                        <div class="bold mb10"><?= $model->model_name ?></div>
                        <img src="<?= $model->model_url ?>" width="100%">

                    </div>
                    <div class="col-xs-6">
                        <div><?= $model->model_modification ?></div>
                    </div>
                </div>
            </a>
        </div>

    <?php } ?>


</div>
