<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\seotools\models\base\Infotext */

$this->title = Yii::t('seotools', 'Update Infotext: ') . ' ' . $model->meta_id."-".$model->city->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('seotools', 'Infotexts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->meta_id, 'url' => ['view', 'meta_id' => $model->meta_id, 'city_id' => $model->city_id]];
$this->params['breadcrumbs'][] = Yii::t('seotools', 'Update');
?>

<div class="infotext-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'city_list' => $city_list,
    ]) ?>

</div>

