<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 13.08.15
 * @time: 11:59
 */

namespace app\modules\autoparts\controllers;

class DefaultController extends ProviderController
{
    public function actionIndex()
    {
        $providerObj = \Yii::$app->getModule('autoparts')->run->provider('Berg', ['store_id' => 6]);
        var_dump($providerObj->getOrderState(['order_id' => '8445043']));
    }

}