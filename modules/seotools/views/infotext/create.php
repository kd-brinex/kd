<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\seotools\models\base\Infotext */

$this->title = Yii::t('seotools', 'Create Infotext');
if(!empty($meta_id = Yii::$app->request->get("_j")))
{
    $this->params['breadcrumbs'][] = ['label' => $meta_id, 'url' => ['manage/view', 'id' => $meta_id]];
}
else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('seotools', 'Infotexts'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="infotext-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'city_list' => $city_list,
    ]) ?>

</div>
