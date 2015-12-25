<?php

namespace app\modules\basket\controllers;

use app\modules\autoparts\models\OrderUpdate1c;
use app\modules\user\models\Order;
use app\modules\user\models\Orders;
use app\modules\user\models\Profile;
use dektrium\user\models\User;
use Yii;
use app\controllers\MainController;
use app\modules\basket\models\BasketSearch;
use yii\helpers\Url;
use yii\helpers\Json;

use app\modules\tovar\models\Tovar;

class BasketController extends MainController
{

    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $tab = isset($params['tab']) ? $params['tab'] : 0;
        $bmodel = new BasketSearch();
        $bdataProvider = $bmodel->search([]);

        $user = \Yii::$app->user->isGuest ? new User : User::findOne(['id' =>  Yii::$app->user->id]);

        if ($bdataProvider->totalCount) {
            $itogo = $this->summa($bdataProvider, ['tovar_summa']);
            $basketContent = $this->renderPartial('basket_tab', ['model' => $bdataProvider, 'itogo' => $itogo]);
        } else
            $basketContent = $this->renderPartial('not_tovar');

        $profile = Yii::$app->user->isGuest ? new Profile() : Profile::findOne(['user_id' => Yii::$app->user->id]);

        $cityCode = Yii::$app->request->cookies['city'];

        $city = \app\modules\city\models\CitySearch::find()->where(['id' => ($cityCode ? $cityCode : 2097)])->one();

        $stores = new \app\modules\autoparts\models\TStoreSearch();

        $stores = $stores->search([':city_id' => $cityCode]);

        $user_tab_data = [
            'city' => $city,
            'profile' => $profile,
            'user' => $user,
        ];

        $delivery_tab_data = [
            'stores' => $stores
        ];

        return $this->render('index', [
            'basketContent' => $basketContent,
            'user_data' => $user_tab_data,
            'delivery_data' => $delivery_tab_data,
            'tab' => $tab
        ]);
    }

    public function summa($dp, $column){
        foreach ($dp->models as $data) {
            foreach ($column as $c) {
                isset($result[$c]) ? $result[$c] += $data->$c : $result[$c] = $data->$c;
            }
        }
        return $result;
    }

    public function actionPut(){
        $params = Yii::$app->request->post();
        $post = array_merge(Yii::$app->request->post());
        $params = Yii::$app->request->queryParams;
        switch ($params['mode']) {
            case 'changeQuantity':
                if(Yii::$app->request->isAjax) {
                    if(!empty(($position = (int)$post['position'])) && !empty(($quantity = (int)$post['quantity']))) {
                        $basket = BasketSearch::findOne($position);
                        $basket->tovar_count = $quantity;

                        return $basket->update();
                    }
                }
                break;
            case 'put':
                $session = new \yii\web\Session;
                $id = Yii::$app->request->post('id');
                if ($id) {
                    $tovar = Tovar::findOne(['id' => $id]);
                    if ($tovar) {
                        $toBasket = new BasketSearch();
                        $toBasket->tovar_id = $id;
                        $toBasket->tovar_count = 1;
                        $toBasket->tovar_price = $tovar->price;
                        $toBasket->session_id = Yii::$app->session->id;
                        $toBasket->tovar_min = 1;
                        $toBasket->provider_id = 5; //id=5 провайдер KD ищет в локальной базе
                        if (Yii::$app->user->id)
                            $toBasket->uid = Yii::$app->user->id;
                        if ($toBasket->save())
                            return '<a class="btn" href="' . url::toRoute(['/basket/basket'], true) . '"><i class="icon-shopping-cart icon-black"></i>Уже в корзине</a>';
                        else if (YII_DEBUG)
                            var_dump($toBasket->getErrors());
                    } else {
                        return false;
                    }
                }
                return '<a class="btn" href="' . url::toRoute(['/basket/basket'], true) . '"><i class="icon-shopping-cart icon-black"></i>Уже в корзине</a>';
                break;
            case 'update':
//                $data = Yii::$app->request->post();
//                if (isset($data) && $data != '') {
//                    $basket = BasketSearch::findOne(['id' => intval($data['row_id'])]);
//                    if ($basket)
//                        $basket->description = Html::encode($data['text']);
//                    if ($basket->save())
//                        return true;
//                }
                if(Yii::$app->request->post('hasEditable')) {
                    $post = Yii::$app->request->post();
                    $model =  $basket = BasketSearch::findOne(['id' => $post['editableKey']]);

                    $data['OrderSearch'] = current($post['OrderSearch']);
                    if ($model->load($data) && $model->save())
                        $data = ['output' => $model->comment];

                    return Json::encode($data);
                }
                break;
            case 'order':
                // создаем новый заказ
                $user_id = Yii::$app->user->id;
                $number = ($user_id ? $user_id :'N') . '-' . date("ymdhis");
                $orders = explode(';', Yii::$app->request->post('orderData'));
                $formData = Yii::$app->request->post('formData');
                if (isset($formData) && $formData != '') {
                    parse_str($formData, $fdata);
                    $profileData = array_values($fdata['Profile']);
                }
                $fdata['deliveryStore'] = isset($fdata['deliveryStore']) ? $fdata['deliveryStore'] : 0;
                $cityCode = Yii::$app->request->cookies['city'];
                $city = \app\modules\city\models\CitySearch::find()->where(['id' => ($cityCode ? $cityCode : 2097)])->one();
                $order_data = [
                    'number' => $number,
                    'date' => date("Y-m-d H:i:s"),
                    'user_id' => $user_id,
                    'user_name'=>$fdata['Profile']['name'],
                    'user_email' =>$fdata['User']['email'],
                    'user_telephone' => $fdata['User']['telephone'],
                    'user_location' => $city->name,
                    'store_id'=> (int) $fdata['deliveryStore'],
                ];
                $order = new Order();
                $order->load($order_data, '');
                $order->save();
                // передаем id заказа
                $order_id = $order->id;

                $order = new OrderUpdate1c();
                $order->OrderId = $order_id;
                $order->save();

                $user = \app\modules\user\models\User::findOne($user_id);
                $user->scenario = 'update';
                $user->telephone = $order_data['user_telephone'];
                $user->save();

                $profile = Profile::findOne($user_id);
                $profile->scenario = 'order';
                $profile->name = $order_data['user_name'];
                $profile->save();

                foreach ($orders as $order) {
                    $order = explode(':', $order);
                    $basket = BasketSearch::findOne(['id' => intval($order[0])]);
                    if ($basket) {
                        $product = Tovar::findOne(['id' => $basket->tovar_id]);
                        $data['Orders'] = [
                            'product_id' => ($product) ? $product->id : null,
                            'manufacture' => $basket->manufacturer,
                            'part_name' => ($product) ? $product->name : $basket->part_name,
                            'part_price' => $basket->tovar_price,
                            'product_article' => ($product) ? null : $basket->part_number,
                            'quantity' => $order[1],
                            'reference' => '',
                            'status' => \app\modules\user\models\Orders::ORDER_ADOPTED,
                            'datetime' => date('Y-m-d H:i:s'),
                            'description' => $basket->description,
                            'order_id'=> (int)$order_id,
                            'provider_id'=> (int)$basket->provider_id,
                            'delivery_days' => (int)$basket->period
                        ];
                        $Orders = new Orders();
                        if ($Orders->load($data) && $Orders->save())
                                $basket->delete();
                    }
                }
                return true;
                break;
            case 'remove':
                if (BasketSearch::deleteAll(['in', 'id', $post['id']]))
                    return JSON::encode($_POST);//$this->basket_row($dataProvider);
                break;
        }
    }

    public function basket_row($dataProvider)
    {
        if ($dataProvider->totalCount) {
            $itogo = $this->summa($dataProvider, ['tovar_summa']);
            return $this->render('zakaz_tab', [
                'model' => $dataProvider,
                'itogo' => $itogo,
            ]);
        } else {
            return $this->render('not_tovar');
        }
    }

}
