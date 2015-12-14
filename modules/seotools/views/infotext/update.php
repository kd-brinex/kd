<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\seotools\models\base\Infotext */

$this->title = Yii::t('seotools', 'Update Infotext: ') . ' ' . $model->meta_id."-".$model->city->name;
if(!empty($meta_id = Yii::$app->request->get("_j")))
{
    $this->params['breadcrumbs'][] = ['label' => Yii::t('seotools', 'Meta Bases'), 'url' => ['manage/index']];
    $this->params['breadcrumbs'][] = ['label' => $meta_id, 'url' => ['manage/view', 'id' => $meta_id]];
}
else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('seotools', 'Infotexts'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->meta_id, 'url' => ['view', 'id' => $model->id]];
}
$this->params['breadcrumbs'][] = Yii::t('seotools', 'Update');
?>

<div class="infotext-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'city_list' => $city_list,
    ]) ?>

</div>

