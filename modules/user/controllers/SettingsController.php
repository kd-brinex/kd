<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 23.03.15
 * Time: 9:46
 */
namespace app\modules\user\controllers;

use app\modules\user\models\Order;
use app\modules\user\models\Orders;
use app\modules\user\models\OrderSearch;
use app\modules\user\models\OrdersSearch;
use app\modules\user\models\SettingsForm;
use dektrium\user\controllers\SettingsController as BaseSettingsController;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

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
                        'actions' => ['profile', 'account', 'confirm', 'networks', 'connect', 'disconnect', 'cars', 'orders', 'order','pay'],
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
     * Поиск заказов пользователя и расспределение по вкладкам "Активные заказы и архив"
     * @return string
     */
    public function actionOrders(){
        //TODO разобраться с фильтрацией по order.number в таблице
        $model = new OrderSearch();
        $model = $model->search('user_id = :uid', [':uid' => Yii::$app->user->id], 'orders');
        $morders = new OrdersSearch();

        if(!empty($params = Yii::$app->request->queryParams)) {
            $morders->load($params);
        }
        $orders = $morders->searchOrdersUser(Yii::$app->user->id);
        $new_orders = [];
        $old_orders = [];
        foreach($model->getModels() as $key => $order){
            $counter = 0;
            foreach($order->orders as $k => $position){
                in_array($position->status, [
                    Orders::ORDER_CANCELED,
                    Orders::ORDER_ISSUED,
                    Orders::ORDER_CANCELED_BY_PROVIDER,
                ]) ?: $counter++;
            }
            $counter ? $new_orders[$order->id] = $order : $old_orders[$order->id] = $order;
        }
        return  $this->render('orders',['new_orders' => $new_orders, 'old_orders' => $old_orders, 'searchModel' => $model, 'orders' => $orders, 'morders' => $morders]);
    }

    /**
     * Поиск всех позиций определенного заказа
     * @return string
     * @return string
     */
    public function actionOrder(){
        if(Yii::$app->request->isAjax) {
            $model = new OrdersSearch();
            $orders = $model->search('order_id = :order_id AND related_detail IS NULL', [':order_id' => Yii::$app->request->post('id')]);

            return $this->renderAjax('_order', ['orders' => $orders, 'searchModel' => $model]);
        }
    }
    public function actionAccount()
    {
        /** @var SettingsForm $model */
        $model = \Yii::createObject(SettingsForm::className());
        $this->performAjaxValidation($model);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account details have been updated'));
            return $this->refresh();
        }

        return $this->render('account', [
            'model' => $model,
        ]);
    }
    public function actionPay()
    {
        $order = Order::findOne(['id'=>$_GET['id']]);
        return $this->render('pay',['order'=>$order]);
    }
}