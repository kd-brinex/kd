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
//            /var/www/kolesa-darom.dev/modules/tovar/views/tovar/finddetails.php
//          return  $this->render('@app/modules/tovar/views/tovar/finddetails',
//              ['provider' => $provider,
//            'columns' =>$parts['columns'],]);
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
