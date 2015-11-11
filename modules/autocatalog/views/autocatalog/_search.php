<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 19.08.15
 * Time: 15:12
 */
use yii\jui\Tabs;
use yii\helpers\Url;
use yii\helpers\Html;
$cat='';
$to='';
//var_dump($catalog);die;
foreach($catalog as $key=>$marka)
{
//    var_dump($marka);die;
    $cat .= Html::tag('div',Html::a($marka['prop']['marka'],Url::toRoute('/autocatalogs/'.$key.'/'.$marka['prop']['region'])),['class'=>'autocatalog_marka']);
    $to .= Html::tag('div',Html::a($marka['prop']['marka'],Url::toRoute('to/'.$key)),['class'=>'autocatalog_marka']);
}
$items=[
//    [
//        'label' => 'Номер детали',
//        'content' => $this->render('_search_details',['params'=>$params]),
//        'options'=>['class'=>'acatalog-tabs','tag' => 'div'],
//        'active' => true,
//    ],
    [
        'label' => 'VIN',

        'content' => $this->render('_search_vin',['params'=>$params]),
        'options'=>['class'=>'acatalog-tabs', 'tag' => 'div'],

    ],
    [
        'label' => 'Автокаталог',
        'content' => $cat,
        'options'=>['class'=>'acatalog-tabs','tag' => 'div'],

    ],
//    [
//        'label' => 'Каталог ТО',
//        'content' => $to,
//        'options'=>['class'=>'acatalog-tabs','tag' => 'div'],
//
//    ],
//    [
//        'label' => 'Parts',
////        'content' =>$this->render('autod/types'),
////        'format'=>'raw',
//        'content'=>'<iframe src="/autod/types.php" width="100%" height="600px"></iframe>',
////        'content'=>'<iframe href="'.Url::to('/autod/index.php',true).'" width="100%" height="600px"></iframe>',
//
//
//        'options'=>['class'=>'acatalog-tabs','tag' => 'div'],
//
//    ],

];

?>
<div>
            <?=Tabs::widget([
    'items' => $items,

]);?>
</div>