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
use app\modules\user\models\OrdersSearch;

class OrdersController extends Controller
{
    public function actionIndex(){
        $model = new OrderSearch();

        $params = Yii::$app->request->queryParams;

        $orders = $model->search('', '', '', $params);

        return $this->render('orders', ['orders' => $orders, 'model' => $model]);
    }

    public function actionManagerorder(){
        if(!empty($id = (int)Yii::$app->request->post('id'))){
            $model = new OrdersSearch;
            $model = $model->search('order_id = :order_id', [':order_id' => $id]);

            $order = $this->findModel($id);
            if($order != null && $model !== null)
                return $this->renderAjax('_managerOrder',['order' => $order,'model' => $model]);
        }
        return false;
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

    public function actionOrdersupdate(){
        if (!Yii::$app->request->isAjax || empty($id = (int)Yii::$app->request->post('id')))
            return false;

        $model = OrdersSearch::findOne($id);
        $model->is_paid = !$model->is_paid ?: false;

        return $model->save();
    }

    public function actionOrdersstateupdate(){
        $status = (int)Yii::$app->request->post('status');
        if (!Yii::$app->request->isAjax
            || empty($id = (int)Yii::$app->request->post('id'))
            || !isset($status))
            return false;

        $model = OrdersSearch::findOne($id);
        $old_status = $model->status;
        $model->status = $status;

        if($model->save()){
            return Json::encode(['status' => $model->status, 'id' => $model->order_id, 'old_status' => $old_status]);
        }
    }


    protected function findModel($id){
        if(($model = OrderSearch::findOne($id)) !== null)
            return $model;
        else
           throw new Exception('This not found');
    }

}