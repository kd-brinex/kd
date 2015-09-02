<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\controllers\MainController;;
use yii\filters\VerbFilter;


class AdminController extends MainController
{
public function behaviors()
    {
//        $this->layout = "/admin.php";
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
//                'only' => ['logout'],
                'rules' => [
//                    [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
                    [
//                        'actions' => ['view', 'search', ''],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
//        $this->layout = false;
        $this->layout = "admin.php";
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index',[]);
    }

    public function actionOrders(){
        return $this->render('orders',[]);
    }

}
