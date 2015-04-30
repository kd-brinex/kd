<?php

namespace app\modules\api\controllers;
use yii\web\Controller;
use app\modules\api\models\Api;
class ApiController extends Controller
{
    public function actionFinddetails()
    {
        $this->layout=false;
    $params = $_POST;
    $details=Api::findDetails($params);
//    var_dump($details);die;
    return $details;
    }
}