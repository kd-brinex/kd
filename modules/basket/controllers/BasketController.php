<?php

namespace app\modules\basket\controllers;

use app\modules\user\models\Order;
use app\modules\user\models\Orders;
use app\modules\user\models\Profile;
use dektrium\user\models\User;
use Yii;
use app\controllers\MainController;
use app\modules\basket\models\BasketSearch;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

use app\modules\tovar\models\Tovar;

class BasketController extends MainController
{

    public function actionIndex()
    {
        $bmodel = new BasketSearch();
        $bdataProvider = $bmodel->search([]);

//        $user = User::findOne(['id' => (\Yii::$app->user->isGuest) ? \Yii::$app->params['nouser_id'] : \Yii::$app->user->identity->getId()]);
        $user = (\Yii::$app->user->isGuest) ?new User : User::findOne(['id' =>  Yii::$app->user->id]);

        if ($bdataProvider->totalCount) {
            $itogo = $this->summa($bdataProvider, ['tovar_summa']);
            $basketContent = $this->renderPartial('basket_tab', ['model' => $bdataProvider, 'itogo' => $itogo]);
        } else {
            $basketContent = $this->renderPartial('not_tovar');
        }
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
            'delivery_data' => $delivery_tab_data
        ]);
    }

    public function summa($dp, $column)
    {

        foreach ($dp->models as $data) {
            foreach ($column as $c) {
                if (isset($result[$c])) {
                    $result[$c] += $data->$c;
                } else {
                    $result[$c] = $data->$c;
                }
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
//                $t=$model->findOne(['tovar_id'=>$post['id']]);
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
                $data = Yii::$app->request->post();
                //var_dump($data);die;
                if (isset($data) && $data != '') {
                    $basket = BasketSearch::findOne(['id' => intval($data['row_id'])]);
                    if ($basket)
                        $basket->description = Html::encode($data['text']);
                    if ($basket->save())
                        return true;
                }
                break;
            case 'order':
                // создаем новый заказ
                $user_id = Yii::$app->user->id;
                $number = (($user_id) ? $user_id :'N') . '-' . date("ymd_his");

                $orders = explode(';', Yii::$app->request->post('orderData'));
                $formData = Yii::$app->request->post('formData');
                if (isset($formData) && $formData != '') {
                    parse_str($formData, $fdata);
                    $profileData = array_values($fdata['Profile']);
                }
                $fdata['deliveryStore'] = isset($fdata['deliveryStore']) ? $fdata['deliveryStore'] : 0;


                $order_data = [
                    'number' => $number,
                    'date' => date("Y-m-d H:i:s"),
                    'user_id' => $user_id,
                    'user_name'=>$fdata['Profile']['name'],
                    'user_email' =>$fdata['User']['email'],
                    'user_telephone' => $fdata['User']['telephone'],
                    'user_location' => $fdata['Profile']['location'],
                    'store_id'=> (int) $fdata['deliveryStore'],
                ];
                $order = new Order();
                $order->load($order_data, '');
                $order->save();
                // передаем id заказа
                $order_id = $order->id;


                foreach ($orders as $order) {
                    $order = explode(':', $order);
                    $basket = BasketSearch::findOne(['id' => intval($order[0])]);
//                    var_dump($basket);die;
                    if ($basket) {
                        $product = Tovar::findOne(['id' => $basket->tovar_id]);
                                $data['Orders'] = [
                                    'product_id'=>($product)? $product->id:null,
                                    'manufacture'=>$basket->manufacturer,
                                    'part_name'=>($product)?$product->name:$basket->part_name,
                                    'part_price'=>$basket->tovar_price,
                                    'product_article'=>($product)?null:$basket->part_number,
                                    'quantity'=>$order[1],
                                    'reference'=>'',
                                    'status'=>\app\modules\user\models\Orders::ORDER_IN_WORK,
                                    'datetime'=>date('Y-m-d H:i:s'),
                                    'description'=>$basket->description,
                                    'order_id'=> (int)$order_id,
                                    'provider_id'=> (int)$basket->provider_id,
                                    'delivery_days' => (int)$basket->period
                                    ];

                    $Orders = new Orders();
                        if ($Orders->load($data)){
                        if ($Orders->save()){$basket->delete();}

                        }
//                        $Orders->save()){$basket->delete();}
                    }
                }
//                $rows = ['product_id', 'product_article', 'manufacture', 'part_name', 'part_price', 'quantity', 'status', 'datetime', 'description', 'store_id', 'order_id','provider_id'];

//                if (!Yii::$app->user->isGuest) {
//                    array_unshift($rows, 'uid');
//                    $profile = \app\modules\user\models\Profile::findOne(['user_id' => Yii::$app->user->id]);
//                    if ($profile) {
//                        $profile->attributes = $fdata['Profile'];
//                        $profile->update();
//                    }
//                } else {
//                    $rows = array_merge($rows, ['name', 'email', 'location', 'telephone']);
//                }
//                if(isset($fdata['deliveryStore']) && $fdata['deliveryStore'] != ''){
//                    array_merge($rows, ['store_id']);
//                }
//                var_dump($rows, $orderData);
//                die;

//                array(12) {
//                [0]=> string(3) "uid" [1]=> string(10) "product_id" [2]=> string(15) "product_article" [3]=> string(11) "manufacture" [4]=> string(9) "part_name" [5]=> string(10) "part_price" [6]=> string(8) "quantity" [7]=> string(6) "status" [8]=> string(8) "datetime" [9]=> string(11) "description" [10]=> string(8) "store_id" [11]=> string(8) "order_id" }
//                array(1) {
//                [0]=> array(12) {
//                    [0]=> int(1) [1]=> NULL [2]=> string(5) "HY012" [3]=> string(3) "HDK" [4]=> string(23) "ШРУС ВНЕШНИЙ" [5]=> float(2490) [6]=> string(1) "1" [7]=> int(1) [8]=> string(19) "2015-09-03 10:08:19" [9]=> NULL [10]=> string(3) "105" [11]=> int(4) } }



// записываем позиции заказа в базу
//                Yii::$app->db->createCommand()->batchInsert('order', ['number', 'date', 'user_id'], [$number, time(), Yii::$app->user->id])->execute();
//                $orders=new Orders();
//                var_dump($orders->attributes());die;

//                $request = Yii::$app->db->createCommand()->batchInsert('orders', $rows, $orderData)->execute();
//                if ($request > 0)
                    return true;
                break;
            case 'remove':
                if (BasketSearch::deleteAll(['in', 'id', $post['id']]))
                    return JSON::encode($_POST);//$this->basket_row($dataProvider);
                break;
        }
//        $model->put($params);


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
