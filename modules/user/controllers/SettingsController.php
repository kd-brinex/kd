<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 23.03.15
 * Time: 9:46
 */
namespace app\modules\user\controllers;

use Yii;
use dektrium\user\controllers\SettingsController as BaseSettingsController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use app\modules\user\models\OrderSearch;
use app\modules\user\models\OrdersSearch;

class SettingsController extends BaseSettingsController
{
    public function behaviors(){
//    $this->layout='/main.php';
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['profile', 'account', 'confirm', 'networks', 'connect', 'disconnect', 'cars', 'orders', 'order'],
                        'roles'   => ['@']
                    ],
                ]
            ],
        ];
    }
    public function actionCars(){
       return $this->render('cars',[]);
    }

    /**
     * Поиск заказов пользователя
     * @return string
     */
    public function actionOrders(){
        $model = new OrderSearch();
        $model = $model->search('user_id = :uid', [':uid' => Yii::$app->user->id], 'orders');

        $v = [];
        $b = [];
        foreach($model->getModels() as $key => $order){
            foreach($order->orders as $k => $position){
                if($position->status <= 4) {
                    $v[$order->id] = $order;
                } else {
                    $b[$order->id] = $order;
                }
            }
        }
        return  $this->render('orders',['new_orders' =>$v, 'old_orders' => $b, 'model' => $model]);
    }

    /**
     * Поиск всех позиций определенного заказа
     * @return string
     * @return string
     */
    public function actionOrder(){
        if(Yii::$app->request->isAjax) {
            $model = new OrdersSearch();
            $orders = $model->search('order_id = :order_id', [':order_id' => Yii::$app->request->post('id')]);

            return $this->renderAjax('_order', ['orders' => $orders]);
        }
    }
}