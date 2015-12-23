<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 29.07.15
 * Time: 15:04
 */

namespace app\modules\autoparts\controllers;

use app\modules\autoparts\models\PartProviderUser;
use app\modules\autoparts\models\ProviderStateCode;
use app\modules\user\models\Order;
use Yii;

use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\base\Exception;

use app\modules\tovar\models\Tovar;
use app\modules\user\models\Orders;
use app\modules\user\models\OrderSearch;
use app\modules\user\models\OrdersSearch;
use app\modules\autoparts\models\OrderUpdate1c;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class OrdersController extends Controller
{
    public function behaviors()
    {
        $this->layout = "/admin.php";
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Parts', 'Admin'],
                    ]
                ],
            ],
        ];
    }

    public function actionIndex(){
        $model = new OrderSearch();
        $params = Yii::$app->request->queryParams;
        $orders = $model->search('', '', '', $params);
        return $this->render('orders', ['orders' => $orders, 'model' => $model]);
    }

    public function actionManagerOrder($id = null){
        if(Yii::$app->request->isAjax) {
            if (empty($id)) return false;
            $model = new OrdersSearch;
            $model = $model->search('order_id = :order_id AND related_detail IS NULL', [':order_id' => (int)$id]);
            $order = $this->findModel($id);
            if ($order != null && $model !== null) {
                foreach($order->orders as $position){
                    if($position->order_provider_id !== null) {
                        $detail = $this->getDetailProviderInfo(['provider' => $position->provider->name, 'order_id' => $position->order_provider_id], $position);
                        if($detail !== false && $detail['status'] != $position->order_provider_status) {
                            $position->order_provider_status = $detail['status'];
                            $position->save();
                        }
                    }
                }
                return $this->renderAjax('_managerOrder', ['order' => $order, 'model' => $model]);
            }
        } //else $this->redirect(Url::toRoute('/admin/orders'));
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

    public function actionDelete($id){
        if(($model = OrdersSearch::findOne($id)) !== null) {
            if($model->delete()) {
                $relatedDetail = OrdersSearch::findOne($model->related_detail);
                $relatedDetail->status = (int)$model->minState;
                $result = Json::encode([
                        'id' => $model->id,
                        'rel_det' => $model->related_detail,
                        'status_text' => $model->minState ? $model->stateAll[$model->minState] : $model->stateAll[Orders::ORDER_IN_WORK]
                ]);
            }
        }
    }

    public function actionDeleteMain($id){
        if(($model = OrdersSearch::findOne($id)) !== null) {
            if($model->delete()) {
                $relatedDetails = OrdersSearch::find()->where('related_detail = :related_detail ORDER BY id ASC', [':related_detail' => $model->id])->all();
                if(!empty($relatedDetails)){
                    $counter = 0;
                    $newRalatedDetail = null;
                    foreach($relatedDetails as $detail){
                        if(!$counter){
                            $detail->related_detail = null;
                            $detail->save();
                            $newRalatedDetail = $detail->id;
                        } else {
                            $detail->related_detail = $newRalatedDetail;
                            $detail->save();
                        }
                        $counter++;
                    }
                }
            }
        }
    }

    public function actionOrdersUpdate(){
        if (!Yii::$app->request->isAjax || empty($id = (int)Yii::$app->request->post('id')))
            return false;

        $model = OrdersSearch::findOne($id);
        $model->is_paid = !$model->is_paid ? 1 : 0 ;

        return $model->save();
    }

    public function actionOrderProviderStatus(){
        if (!Yii::$app->request->isAjax)
            return false;

        if(Yii::$app->request->post('hasEditable')) {
            $post = Yii::$app->request->post();
            $model = OrdersSearch::findOne($post['editableKey']);

            $data['OrdersSearch'] = current($post['OrdersSearch']);
            if ($model->load($data) && $model->validate()) {
                $status = $this->getDetailProviderInfo(['provider' => $model->provider->name, 'order_id' => $model->order_provider_id], $model);
                if($status !== false) {
                    if (!empty($status)) {
                        $model->order_provider_status = $status['status'];
                        $model->save();
                    }
                    $data = ['output' => $model->order_provider_id, 'status' => $status['status'], 'status_text' => $status['status_name']];
                } else $data = ['output' => '', 'message' => 'Статус не определен. Номер заказа введен неверно, либо сервер поставщика временно не доступен. Попробуйте пожалуйста позже.'];

            }
            return Json::encode($data);
        }
    }

    private function getDetailProviderInfo($params, $model)
    {
        $orderDetails = Tovar::getProviderOrderState($params, $model->order->store_id);
        if (!empty($orderDetails)){
            foreach ($orderDetails as $detail) {
                if ($detail['code'] == $model->product_article && $detail['quantity'] == $model->quantity) {
                    $stateCode = ProviderStateCode::findOne(['provider_id' => $model->provider->id, 'status_code' => $detail['status']]);
                    if ($stateCode === null) {
                        $providerStateCode = new ProviderStateCode();
                        $providerStateCode->provider_id = $model->provider->id;
                        $providerStateCode->status_code = $detail['status'];
                        $providerStateCode->status_name = $detail['status_name'];
                        $providerStateCode->save();
                    } else if($detail['status_name'] != '' && $stateCode->status_name != $detail['status_name']){
                        $stateCode->status_name = $detail['status_name'];
                        $stateCode->save();
                    }
                    return [
                        'status' => $detail['status'],
                        'status_name' => !empty($detail['status_name']) ? $detail['status_name'] : $stateCode->status_name
                    ];
                }
            }
        } else return false;
    }

    public function actionOrdersStateUpdate(){
        $status = (int)Yii::$app->request->post('status');
        if (!Yii::$app->request->isAjax
            || empty($id = (int)Yii::$app->request->post('id'))
            || !isset($status))
            return false;

        $model = OrdersSearch::findOne($id);
        $old_status = $model->status;
        $model->status = $status;

        if($model->save()){
            $relatedDetail = OrdersSearch::findOne($model->related_detail);
            $relatedDetail->status = $model->minState;
            $relatedDetail->save();
            $data = ['status' => $model->status, 'id' => $model->order_id, 'old_status' => $old_status];
            if($model->related_detail)
                $data = array_merge($data, ['rel_det' => $model->related_detail, 'state_text' => $model->stateAll[$model->minState]]);

            return Json::encode($data);
        }
    }

    public function actionProviderOrderStateUpdate(){
        $post = Yii::$app->request->post();
        if(($orderPosition = Orders::findOne((int)$post['id'])) !== null){
            $orderPosition->order_provider_status = $post['Orders']['order_provider_status'];
            return $orderPosition->save();
        }
    }

    public function actionSendTo1c($id){
        $order = new OrderUpdate1c;
        $order->OrderId = (int)$id;
        return $order->save() ?: false;
    }

    public function actionPricing(){
        $id = (int)Yii::$app->request->post('order');
        $code = Yii::$app->request->post('code');
        $city = (int)Yii::$app->request->post('city_id');

        if(empty($id) && empty($code)) return false;

        $allDetails = [];
        if(!empty($id)) {
            $details = OrdersSearch::find()
                ->where('order_id = :order_id', [':order_id' => $id])
                ->andWhere('status <= :status', [':status' => Orders::ORDER_ADOPTED])
                ->andWhere('provider_id > 0')
                ->andWhere('provider_id <> 5')
                ->andWhere('related_detail IS NULL')
                ->all();

            foreach ($details as $detail) {
                $article = !empty($detail->product_article) ? $detail->product_article : (!empty($detail->product_id) ? $detail->product_id : null);
                $compareDetails = Tovar::findDetails(['article' => $article, 'city_id' => $detail->order->store->city_id]);

                $allDetails[$article]['manufacture'] = $detail->manufacture;
                $allDetails[$article]['offers'] = $compareDetails;
            }
        } else
            $allDetails[$code]['offers'] = Tovar::findDetails(['article' => $code, 'city_id' => $city]);

        $offers = [];
        foreach ($allDetails as $key => $detail_offers) {
            if(is_array($detail_offers) && !empty($detail_offers)){
                foreach($detail_offers['offers'] as $offer){
                    if(strtoupper($offer['code']) == strtoupper($key)) {
                        $offers[$key]['offers'][] = $offer;

                    }
                }
            }
        };

        if(!empty($id)){
            $firstOffers = $this->firstOffers($details, $offers);
            $data = $this->buildArray($firstOffers, $offers, $details);
            $orderdetails = $details;
        } else {
            $data = $offers[key($offers)]['offers'];
            $orderdetails['order_id'] = Yii::$app->request->post('order_id');
            $orderdetails['detail_id'] = 0;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);

        $offersData = $this->toDataProvider($offers);

        $params = ['model' => $dataProvider, 'offersData' => $offersData, 'orderedDetails' => $orderdetails];

        return Json::encode(['output' => '', 'table' => $this->renderAjax('_pricing', $params)]);
    }

    private function toDataProvider($offers){
        $dataProviders = [];
        foreach($offers as $detail => $offer){
            $dataProviders[$detail] = new ArrayDataProvider([
                'allModels' => $offer['offers'],
                'pagination' => false
            ]);
        }
        return $dataProviders;
    }

    private function firstOffers($orderDetails, &$offers){
        $orderDetailIndex = 0;
        $offersTo = [];
        foreach ($offers as $detail => &$detail_offer) {
            $orderDetail = $orderDetails[$orderDetailIndex];
            $cheapOffer[$detail]['offer'] = [];
            $expensiveOffers[$detail]['offer'] = [];
            $minShippingPeriod = [];
            $minPrice = [];
            if(empty($detail_offer['offers'])) {
                $offersTo[$detail]['cheap'] = false;
                $offersTo[$detail]['expensive'] = false;
                $orderDetailIndex++;
                continue;
            }
            foreach ($detail_offer['offers'] as $k => &$v) {
                if ($v['price'] <= $orderDetail->part_price &&
                    $v['srokmax'] <= $orderDetail->delivery_days &&
                    $v['manufacture'] === $orderDetail->manufacture)
                    $minShippingPeriod[$k] = $v['srokmax'];
                else if($v['manufacture'] === $orderDetail->manufacture)
                    $minPrice[$k] = $v['price'];

                $v['name'] = $v['name'] != $orderDetail->part_name ? $v['name'] . '|r' : $v['name'];
                $v['manufacture'] = $v['manufacture'] != $orderDetail->manufacture ? $v['manufacture'] . '|r' : $v['manufacture'];
                $v['price'] = $v['price'] < $orderDetail->part_price ? $v['price'] . '|g' : ($v['price'] > $orderDetail->part_price ? $v['price'].'|r' : $v['price']);
                $v['quantity'] = $v['quantity'] < $orderDetail->quantity ? $v['quantity'] . '|r' : ($v['quantity'] > $orderDetail->quantity ? $v['quantity'].'|g' : $v['quantity']);
                $v['pid'] = $v['pid'] != $orderDetail->provider_id ? $v['pid'] . '|r' : $v['pid'];
                $v['srokmax'] = $v['srokmax'] < $orderDetail->delivery_days ? $v['srokmax'] . '|g' : ($v['srokmax'] > $orderDetail->delivery_days ? $v['srokmax'].'|r' : $v['srokmax']);
            }
            $orderDetailIndex++;
            $offersTo[$detail]['cheap'] = !empty($minShippingPeriod) ? array_keys($minShippingPeriod, min($minShippingPeriod))[0] : false;
            $offersTo[$detail]['expensive'] = !empty($minPrice) ? array_keys($minPrice, min($minPrice))[0] : false;
        }
        return $offersTo;
    }

    private function buildArray($firstOffers, &$fromOffers, $orderDeatils){
        $details = [];
        $orderDetailIndex = 0;
        foreach($firstOffers as $detail => $offer){
            $index = !empty($offer['cheap']) ? $offer['cheap'] : $offer['expensive'];
            if($index !== false){
                $details[$orderDeatils[$orderDetailIndex]->id] = $fromOffers[$detail]['offers'][$index];
                unset($fromOffers[$detail]['offers'][$index]);
            } else {
                $d = $orderDeatils[$orderDetailIndex];
                $details[$orderDeatils[$orderDetailIndex]->id]['code'] = !empty($d->product_id) ? $d->product_id : $d->product_article.'|r';
                $details[$orderDeatils[$orderDetailIndex]->id]['manufacture'] = $d->manufacture;
                $details[$orderDeatils[$orderDetailIndex]->id]['name'] = $d->part_name;
                $details[$orderDeatils[$orderDetailIndex]->id]['quantity'] = $d->quantity;
                $details[$orderDeatils[$orderDetailIndex]->id]['price'] = $d->part_price;
                $details[$orderDeatils[$orderDetailIndex]->id]['srokmax'] = $d->delivery_days;
                $details[$orderDeatils[$orderDetailIndex]->id]['pid'] = $d->provider_id;
                $details[$orderDeatils[$orderDetailIndex]->id]['provider'] = 'KD'.$d->provider_id.'-'.$d->order->store_id;
            }

            $orderDetailIndex++;
        }
        return $details;
    }

    public function actionInOrder(){
        if(!empty($post = Yii::$app->request->post()) && Yii::$app->request->isAjax){
            $order = new OrdersSearch;
            $order->manufacture = strpos($post['manufacture'], '|r') || $post['manufacture'] == '|r' ? explode('|',$post['manufacture'])[0] : $post['manufacture'];
            $order->part_name = strpos($post['name'], '|r') ? explode('|',$post['name'])[0] : $post['name'];
            $order->part_price = (int)$post['price'];
            $order->quantity = (int)$post['quantity'];
            $order->status = \app\modules\user\models\Orders::ORDER_IN_WORK;
            $order->datetime = date('Y-m-d H:m:s');
            $order->product_article = strpos($post['code'], '|r') ? explode('|',$post['code'])[0] : $post['code'];
            $order->order_id = $post['order_id'];
            $order->provider_id = (int)$post['pid'];
            $order->delivery_days = (int)$post['srokmax'];
            $order->related_detail = $post['detail_id'] == 0 ? null : $post['detail_id'];
            if($order->save()) {
                $order->provider_id = $order->provider->name;
                return Json::encode($order->getAttributes());
            }
        }
        return false;
    }



    protected function findModel($id){
        if(($model = OrderSearch::findOne($id)) !== null)
            return $model;
        else
           throw new Exception('This not found');
    }

}