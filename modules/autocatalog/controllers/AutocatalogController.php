<?php

namespace app\modules\autocatalog\controllers;

use app\controllers\MainController;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\autocatalog\models\Car;
use app\modules\tovar\models\Tovar;


class AutocatalogController extends MainController
{
    public function behaviors()
    {
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
                        'actions' => ['index', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->getIsAdmin();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'model', 'indexvin', 'indexframe'],
                        'roles' => ['?', '@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['catalog', 'album', 'page'],
                        'roles' => ['?', '@']
                    ],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $params = \Yii::$app->request->queryParams;
        $catalog = $this->module->getCatalog();
        if (isset($params['article'])) {
            $parts = \Yii::$app->params['Parts'];
            $details = (isset($params['article'])) ? Tovar::findDetails($params) : [];

            $provider = new ArrayDataProvider([
                'allModels' => $details,
                'sort' => $parts['sort'],
                'pagination' => $parts['pagination'],
            ]);
//            var_dump($provider);die;
//            'code' => string 'HY012' (length=5)
//      'name' => string 'ШРУС ВНЕШНИЙ' (length=23)
//      'manufacture' => string 'HDK' (length=3)
//      'price' => float 2339
//      'quantity' => int 26
//      'srokmin' => int 6
//      'srokmax' => int 10
//      'provider' => string 'KD4-109' (length=7)
//      'reference' => string '585283300' (length=9)
//      'srok' => string '6-10' (length=4)
//      'estimation' => float 100
//      'lotquantity' => string '1' (length=1)
//      'pricedate' => string '' (length=0)
//      'pricedestination' => string '' (length=0)
//      'skladid' => string 'MSAS' (length=4)
//      'sklad' => string 'Москва-MSAS' (length=17)
//      'groupid' => string '0' (length=1)
//      'flagpostav' => string 'kodParts4' (length=9)
//      'storeid' => int 109
//      'pid' => string '4' (length=1)
//      'srokdays' => int 5
//      'weight' => string '100' (length=3)
//      'cross' => string '' (length=0)
//      'ball' => float 116
//            /var/www/kolesa-darom.dev/modules/tovar/views/tovar/finddetails.php
//          return  $this->render('@app/modules/tovar/views/tovar/finddetails',
//              ['provider' => $provider,
//            'columns' =>$parts['columns'],]);

//            'code' => string 'HY012' (length=5)
//      'name' => string 'ШРУС ВНЕШНИЙ' (length=23)
//      'manufacture' => string 'HDK' (length=3)
//      'price' => string '2033.5800' (length=9)
//      'quantity' => int 26
//      'srokmin' => int 1
//      'srokmax' => int 5
//      'estimation' => string '100.0' (length=5)
//      'lotquantity' => int 1
//      'skladid' => string 'MSAS' (length=4)
//      'sklad' => string 'Москва' (length=12)
//      'groupid' => string 'Original' (length=8)
//      'provider' => string '' (length=0)
//      'reference' => string '' (length=0)
//      'srok' => string '' (length=0)
//      'pricedate' => string '' (length=0)
//      'pricedestination' => string '' (length=0)
//      'flagpostav' => string '' (length=0)
//      'storeid' => string '' (length=0)
//      'pid' => string '' (length=0)
//      'srokdays' => string '' (length=0)
//      'weight' => string '' (length=0)
//      'cross' => string '' (length=0)
//      'ball' => string '' (length=0)
//      'statSuccessCount' => string '' (length=0)
//      'statRefusalCount' => string '' (length=0)
//      'statTotalOrderCount' => string '' (length=0)
            return $this->render('index', [
                'catalog' => $catalog,
                'provider' => $provider,
                'columns' => $parts['columns'],
                'params' =>$params]);
        }

        return $this->render('index', [
            'catalog' => $catalog,
            'params' =>$params
        ]);
    }


}
