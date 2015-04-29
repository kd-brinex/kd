<?php

namespace app\modules\api\controllers;
use yii\web\Controller;
use app\modules\api\models\Api;
class ApiController extends Controller
{
    public function actionFinddetails()
    {
    $params = \Yii::$app->request->queryParams;
    $details=Api::findDetails($params);
//    var_dump($details);die;
    return $details;
    }
}