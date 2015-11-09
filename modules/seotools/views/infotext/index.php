<?php

use yii\helpers\Html;
use \kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\seotools\models\InfotextSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('seotools', 'Infotexts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="infotext-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('seotools', 'Create Infotext'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'meta_id',
            'city.name',
            'infotext_before:ntext',
            'infotext_after:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
