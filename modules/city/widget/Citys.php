<?php
namespace  app\modules\city\widget;
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17.01.15
 * Time: 23:41
 */
use app\modules\city\IpGeoBase;
use yii\base\Widget;
class Citys extends Widget
{
    public function run(){
        $model  =new IpGeoBase();

    }
}