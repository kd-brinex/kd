<?php

namespace app\modules\basket\controllers;
use app\modules\basket\Basket;
use Yii;
use yii\web\Controller;
use app\modules\basket\models\Zakaz;


class DefaultController extends Controller
{
    public function actionIndex()
    {
        $model = new Zakaz();


            return $this->render('../zakaz/_form.php', [

                'model' => $model,
            ]);

    }
    public function actionPut(){
        $params=Yii::$app->request->post();
        $model = new Zakaz();
        $model->put($params);
        return $this->render('../zakaz/_form.php', [

            'model' => $model,
        ]);
     }
}
