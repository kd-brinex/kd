<?php

namespace app\modules\autocatalog\controllers;
use app\controllers\MainController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\autocatalog\models\Car;
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
        $this->module->module->load($params);


    }


}
