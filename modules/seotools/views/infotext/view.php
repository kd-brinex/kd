<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\seotools\models\base\Infotext */

$this->title = $model->meta_id."-".$model->city->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('seotools', 'Infotexts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php \yii\widgets\Pjax::begin(); ?>
<div class="infotext-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('seotools', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('seotools', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('seotools', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'meta_id',
            'city.name',
            'infotext_before:ntext',
            'infotext_after:ntext',
        ],
    ]) ?>

</div>
<?php \yii\widgets\Pjax::end(); ?>