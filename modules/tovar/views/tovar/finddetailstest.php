<?php
use yii\helpers\Html;
use yii\grid\GridView;
Yii::$app->view->registerCssFile('/css/parts.css');
?>

    <div class="center-td">
        <div class="sel-parts borders">
            <div class="sel-parts-1">
                <h2>Найти запчасть<br>по номеру детали</h2>
                <br>
                <form name="search" method="GET" action="">
                    <input type="text" class="bootstrap-input" name="article" id="article" >
                    <input type="submit" class="btn" value="Найти">
                </form>
            </div>
            <div class="sel-parts-2">
                <h2>Номер детали<br>можно узнать в каталогах</h2>
                <br>
                <?=Html::a('Открыть каталог автомобилей','/auto',['class'=>'btn']);?>
                <!--<a class="btn" href="http://kd.auto2d.com//">Открыть каталог автомобилей </a>-->
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

<?php
//var_dump($provider);

echo GridView::widget([
    'dataProvider' => $provider,
//    'filterModel' => ['Name' => ''],
    'columns' =>$columns,
]);


