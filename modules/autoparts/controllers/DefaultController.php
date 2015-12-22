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
        $providerObj = \Yii::$app->getModule('autoparts')->run->provider('Partkom', ['store_id' => 109]);
        var_dump($providerObj->getOrderState(['order_id' => 'НДИ1947218']));
    }

}