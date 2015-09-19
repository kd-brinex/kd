<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 29.07.15
 * Time: 15:04
 */

namespace app\modules\autoparts\controllers;

use app\modules\user\models\Order;
use app\modules\user\models\OrdersSearch;
use Yii;
use yii\base\Exception;
use yii\web\Controller;
use app\modules\user\models\Orders;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

class OrdersController extends Controller
{
    public function actionIndex(){
        $model = new OrdersSearch();
        $orders = $model->search();
//        [
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => 50
//            ]
//        ]);
        return $this->render('orders', ['orders' => $orders]);
    }

    public function actionUpdate(){
        if (!Yii::$app->request->isAjax){
            return;}
        if(Yii::$app->request->post('hasEditable')) {
            $post = Yii::$app->request->post();
            $model = $this->findModel($post['editableKey']);
            $model->scenario = 'update';

            $data['Order'] = current($post['Order']);
            if ($model->load($data) && $model->save())
                $data = ['output' => $model->pay_datetime];

            return Json::encode($data);
        }
    }

    public function actionSend(){
        $post = Yii::$app->request->post();
        $result = [];
        if(isset($post) && $post != '' && is_array($post)){
            $model = Orders::find()->with('provider')->where($post)->all();
            //Yii::$app->soapClient->init();
            $options = [
                'wsdl' => 'http://ws.emex.ru/EmEx_Basket.asmx?WSDL',

            ];
            //$soap = Yii::$app->soapClient->getMethods();
            Yii::$app->soapClient->run();
//            var_dump($soap->__getFunctions());

//            var_dump($soap);


//            foreach($model as $row){
//                if($row->provider->enable){
//                    $params = Yii::$app->params['Parts']['PartsProvider'][$row->provider->name];
//                    if(isset($params)){
//                        $params['store_id'] = isset($row->store_id) ? $row->store_id : 109;
//                        $class = '\app\modules\autoparts\providers\\'.$row->provider->name;
//                        $params['ePrices']['Num'] = 1;
//                        $params['ePrices']['MLogo'] = 'HDK';
//                        $params['ePrices']['DNum'] = 'HY012';
//                        $params['ePrices']['Quan'] = 1;
//                        $params['ePrices']['Com'] = 'Тест';
//                        $params['method'] = 'toBasket';
//                        $provider = new $class($params);
//
//                        $res = $provider->toBasket();
//                    }
//                }
//            }
//            return Json::encode($result);

        }

    }

    protected function findModel($id){
        if(($model = Orders::findOne($id)) !== null)
            return $model;
        else
           throw new Exception('This not found');
    }

}