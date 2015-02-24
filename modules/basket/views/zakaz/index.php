<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\basket\models\ZakazSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Zakazs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zakaz-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Zakaz', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'session_id',
            'user_id',
            'user_name',
            'user_telephon',
            // 'user_email:email',
            // 'pay_id',
            // 'store_id',
            // 'adr_city',
            // 'adr_adres',
            // 'adr_index',
            // 'zakaz',
            // 'zakaz_summa',
            // 'zakaz_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
