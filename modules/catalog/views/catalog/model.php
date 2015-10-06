<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tovar\models\ParamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//var_dump($params['breadcrumbs']);die;
//$this->title = $params['title'];
$this->params['breadcrumbs']= $params['breadcrumbs'];
//$data=$dataProvider->models[0];
//$dataProvider->query->setData($data);
//var_dump($dataProvider);die;
?>
<div class="catalog-model">

    <h1><?= Html::encode($this->title) ?></h1>
<?php
if(!empty($dataProvider)){
foreach($dataProvider as $name=>$model)
{
//    var_dump($model);die;
    echo Collapse::widget([
        'items' => [
            [
                'label' => $name,
                'content'=>$this->render('model_group',['model'=>$model]),
                'options'=>['class'=>"col-xs-12 row"],
            ],
        ]
    ]);}
}
else{

    echo Alert::widget([
        'options' => [
            'class' => 'alert-danger'
        ],
        'body' => 'Информация о модели не найдена.'
    ]);
 }

?>
</div>