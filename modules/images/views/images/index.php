<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\images\ImgImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Картинки';
$this->params['breadcrumbs'][] = $this->title;

foreach ($dataProvider->models as $key)
{
    $key->src= Html::img($key->src, ['alt' => '','width'=>'150']);
}


?>
<div class="img-image-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить картинку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'table',
            'table_id',
            'src:Html',
            'title',
            // 'alt',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
