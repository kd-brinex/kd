<?php

namespace app\modules\api\controllers;
use yii\web\Controller;
use app\modules\api\models\Api;
class ApiController extends Controller
{
    public function actionFinddetails()
    {
        $this->layout=false;
    $params = \Yii::$app->request->queryParams;
//    $params = $_POST;
        $params=array_merge($params,$_POST);
//        var_dump($params);die;
    $details=Api::findDetails($params);
//    var_dump($details);die;
    return $details;
    }
    public function actionTovar_tip()
    {
        $this->layout=false;
        $params = \Yii::$app->request->queryParams;
        $params=array_merge($params,$_POST);
        $tovars=Api::tovar_tip($params);
        return $tovars;
    }
    public function actionTovar()
    {
        $this->layout=false;
        $params = \Yii::$app->request->queryParams;
        $params=array_merge($params,$_POST);
        $tovars=Api::tovar($params);
        return $tovars;
    }
}