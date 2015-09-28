<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 29.07.15
 * Time: 15:04
 */

namespace app\modules\autoparts\controllers;

use Yii;

use yii\helpers\Json;
use yii\web\Controller;
use yii\base\Exception;

use app\modules\user\models\OrderSearch;

class OrdersController extends Controller
{
    public function actionIndex(){
        $model = new OrderSearch();

        $params = Yii::$app->request->queryParams;

        $orders = $model->search('', '', '', $params);

        return $this->render('orders', ['orders' => $orders, 'model' => $model]);
    }

    public function actionManagerorder(){
//        $order_id = $
    }

    public function actionUpdate(){
        if (!Yii::$app->request->isAjax)
            return false;
        if(Yii::$app->request->post('hasEditable')) {
            $post = Yii::$app->request->post();
            $model = $this->findModel($post['editableKey']);

            $data['OrderSearch'] = current($post['OrderSearch']);
            if ($model->load($data) && $model->save())
                $data = ['output' => $model->comment];

            return Json::encode($data);
        }
    }

    protected function findModel($id){
        if(($model = OrderSearch::findOne($id)) !== null)
            return $model;
        else
           throw new Exception('This not found');
    }

}