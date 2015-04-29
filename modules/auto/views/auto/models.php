<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.04.15
 * Time: 10:26
 */

//$this->params['breadcrumbs'][] = ['label'=>'Каталог','url'=>['/auto']];
//$this->params['breadcrumbs'][] = ['label'=>$models->typeName,'url'=>['/auto/marks/'.$params['typeid']]];
$this->params['breadcrumbs'][] = $models->markName;

//$this->registerCss($cssView);
//var_dump($models);?>
<div id="AutoDealer">
    <h1>Список моделей <?= $models->markName ?> </h1>



    <?php foreach ($models->models AS $modelCode => $model) {
        if($model->model_url!=''){?>

        <div class="col-xs-12 col-lg-6">
            <a href="/auto/tree/<?= $model->model_id ?>">
                <div class="col-xs-12 modelItem">
                    <div class="col-xs-6">
                        <div class="bold mb10"><?= $model->model_name ?></div>
                        <img src="<?= $model->model_url ?>" height="100px">

                    </div>
                    <div class="col-xs-6">
                        <div><?= $model->model_modification ?></div>
                    </div>
                </div>
            </a>
        </div>

    <?php } }?>


</div>
