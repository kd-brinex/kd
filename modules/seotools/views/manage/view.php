<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model jpunanua\seotools\models\base\MetaBase */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('seotools', 'Meta Bases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = !empty($model->title)?$model->title:$model->id_meta;

?>
<div class="meta-base-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('seotools', 'Update'), ['update', 'id' => $model->id_meta], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('seotools', 'Delete'), ['delete', 'id' => $model->id_meta], [
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
            'id_meta',
            'hash',
            'route',
            'robots_index',
            'robots_follow',
            'author',
            'title',
            'keywords:ntext',
            'description:ntext',
            'h1_title',
            'infotext_before:ntext',
            'infotext_after:ntext',
            'sitemap',
            'sitemap_change_freq',
            'sitemap_priority',
            'created_at',
            'updated_at',
        ],
    ]) ?>
    <?= Html::a(Yii::t('seotools', 'Create Infotext'), ['infotext/create','meta_id' => $model->id_meta, '_j' => 2], ['class' => 'btn btn-success']) ?>

<!--    --><?php // \yii\bootstrap\Modal::begin([
//        'id' => 'infotext-modal',
//        'header' => '&nbsp;',
//        'toggleButton' => [
//            'label' => 'Create Infotext',
//            'data-target' => '#infotext-modal',
//            'class' => 'btn btn-success',
//            'onclick'=>'create_infotext('.$model->id_meta.')',
//        ],
//    ]);
//    \yii\bootstrap\Modal::end();  ?>


    <?= GridView::widget([
        'id' => 'manage-infotext-grid',
        'dataProvider' => $infotext,
         'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'meta_id',
            'city.name',
            'infotext_before:ntext',
            'infotext_after:ntext',

            ['class' => 'yii\grid\ActionColumn',

                                'urlCreator'=>function($action, $model, $key, $index){
                                return  yii\helpers\Url::toRoute(['infotext/'.$action, 'meta_id' => $model->meta_id, 'city_id' => $model->city_id, '_j' => 2] );
                            },
                'template'=>'{update}  {delete}',
            ],
        ],
    ]); ?>

</div>
