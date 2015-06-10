<?php

namespace app\modules\autoparts\controllers;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ProvidersrokController extends \yii\web\Controller
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
                        'allow' =>true,
                        'roles' =>['Parts','Admin'],
                    ]
                ]
            ],
        ];
    }
    public function actionCreate()
    {
        return $this->render('create');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

    public function actionView()
    {
        return $this->render('view');
    }

}
