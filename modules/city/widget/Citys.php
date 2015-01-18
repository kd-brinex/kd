<?php
namespace  app\modules\city\widget;
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17.01.15
 * Time: 23:41
 */
//use app\modules\city\IpGeoBase;
use app\modules\city\models\CitySearch;
use yii\base\Widget;
class Citys extends Widget
{
    public static function widget(){
        $model  =new CitySearch();
        $cites=$model->search([]);
//        var_dump($cites);die;
    foreach( $cites as $c){
        var_dump($c);die;
        echo '<p>'.$c['city'].'</p>';
    }
    }
}