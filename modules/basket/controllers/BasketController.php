<?php

namespace app\modules\basket\controllers;

use app\modules\basket\models\Order;
use app\modules\basket\models\OrderSearch;
use app\modules\user\models\Profile;
use dektrium\user\models\User;
use dektrium\user\models\UserSearch;
use Symfony\Component\Finder\Expression\Expression;
use Yii;
use app\controllers\MainController;
use app\modules\basket\models\BasketSearch;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\basket\models\ZakazSearch;
use yii\helpers\Json;

use app\modules\tovar\models\Tovar;

class BasketController extends MainController
{

    public function actionIndex()
    {
        $bmodel = new BasketSearch();
        $bdataProvider = $bmodel->search([]);

        $user = new User();
        $muser=$user->findOne(['id'=>(\Yii::$app->user->isGuest)?\Yii::$app->params['nouser_id']:\Yii::$app->user->identity->getId()]);

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
        $stores = $stores->search([':city_id'=>$cityCode]);
        $user_tab_data = [
            'city' => $city,
            'profile' => $profile,
            'user' => $muser,
        ];
        $delivery_tab_data = [
            'stores' => $stores
        ];
        return $this->render('index', [
            'basketContent' => $basketContent,
            'user_data'=> $user_tab_data,
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

    public function actionPut()
    {
        $params = Yii::$app->request->post();
        $post = array_merge(Yii::$app->request->post());
        $params = Yii::$app->request->queryParams;
////        var_dump($post);die;
//        $model = new BasketSearch();
//        $result=$model->put($post);
//        $dataProvider = $model->search([]);
//
        switch ($params['mode']) {
            case 'put':
//                $t=$model->findOne(['tovar_id'=>$post['id']]);
                $session = new \yii\web\Session;
                $id = Yii::$app->request->post('id');
                if($id){
                    $tovar = Tovar::findOne(['id' => $id]);
                    if($tovar){
                        $toBasket = new BasketSearch();
                        $toBasket->tovar_id = $id;
                        $toBasket->tovar_count = 1;
                        $toBasket->tovar_price = $tovar->price;
                        $toBasket->session_id = Yii::$app->session->id;
                        $toBasket->tovar_min = 1;
                        if(Yii::$app->user->id)
                            $toBasket->uid = Yii::$app->user->id;
                        if($toBasket->save())
                            return '<a class="btn" href="'.url::toRoute(['/basket/basket'], true).'"><i class="icon-shopping-cart icon-black"></i>Уже в корзине</a>';
                        else if(YII_DEBUG)
                            var_dump($toBasket->getErrors());
                    } else {
                        return false;
                    }
                }
                return '<a class="btn" href="'.url::toRoute(['/basket/basket'], true).'"><i class="icon-shopping-cart icon-black"></i>Уже в корзине</a>';
                break;
            case 'update':
                    $data = Yii::$app->request->post();
                    //var_dump($data);die;
                    if(isset($data) && $data != ''){
                        $basket = BasketSearch::findOne(['id' => intval($data['row_id'])]);
                        if($basket)
                            $basket->description = Html::encode($data['text']);
                        if($basket->save())
                            return true;
                    }
                break;
            case 'order':
                $orderData = [];
                $orders = explode(';',Yii::$app->request->post('orderData'));
                $formData = Yii::$app->request->post('formData');
                if(isset($formData) && $formData != ''){
                    parse_str($formData, $fdata);
                    $profileData = array_values($fdata['Profile']);
                }
                $fdata['deliveryStore'] = isset($fdata['deliveryStore']) ? $fdata['deliveryStore'] : 0;
                foreach ($orders as $order) {
                    $order = explode(':',$order);
                    $basket = BasketSearch::findOne(['id' => intval($order[0])]);
                    if($basket){
                        $product  = Tovar::findOne(['id' => $basket->tovar_id]);
                        if($basket->delete()){
                            if($product){
                                $data = [
                                    $product->id,
//                                    null,
                                    $basket->manufacturer,
                                    $product->name,
                                    $basket->tovar_price,
                                    $order[1],
                                    \app\modules\user\models\Order::JUST_ORDERED,
                                    date('Y-m-d H:i:s'),
                                    $basket->description,
                                    $fdata['deliveryStore'],
//                                    $basket->provider_id
                                ];
                                if(!Yii::$app->user->isGuest)
                                    array_unshift($data, Yii::$app->user->id);
                                else
                                    $data = array_merge($data, $profileData);
                            } else {
                                $data = [
//                                    null,
                                    $basket->part_number,
                                    $basket->manufacturer,
                                    $basket->part_name,
                                    $basket->tovar_price,
                                    $order[1],
                                    \app\modules\user\models\Order::JUST_ORDERED,
                                    date('Y-m-d H:i:s'),
                                    $basket->description,
                                    $fdata['deliveryStore'],
//                                    $basket->provider_id
                                ];
                                if(!Yii::$app->user->isGuest)
                                    array_unshift($data, Yii::$app->user->id);
                                else
                                    $data = array_merge($data, $profileData);
                            }
//                            if(isset($fdata['deliveryStore']) && $fdata['deliveryStore'] != ''){
//                                array_merge($data, []);
//                            }
//                            var_dump($data);
                            $orderData[] = $data;

                        }
                    }
                }
                $rows = ['product_id',/*'product_article',*/ 'manufacture', 'part_name', 'part_price', 'quantity', 'status', 'datetime', 'description','store_id'/*,'provider_id'*/];

                if (!Yii::$app->user->isGuest) {
                    array_unshift($rows, 'uid');
                    $profile = \app\modules\user\models\Profile::findOne(['user_id' => Yii::$app->user->id]);
                    if($profile){
                        $profile->attributes = $fdata['Profile'];
                        $profile->update();
                    }
                } else
                    $rows = array_merge($rows, ['name', 'email', 'location', 'telephone']);
//                if(isset($fdata['deliveryStore']) && $fdata['deliveryStore'] != ''){
//                    array_merge($rows, ['store_id']);
//                }
//                var_dump($rows);die;
                $request = Yii::$app->db->createCommand()->batchInsert('orders', $rows, $orderData)->execute();
                if($request > 0 )
                    return true;
                break;
            case 'remove':
                if(BasketSearch::deleteAll(['in', 'id', $post['id']]))
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
