<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\modules\city\ipgeobase;
use app\modules\city\models\CitySearch;

class SiteController extends Controller
{
public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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
//        var_dump(Yii::$app->ipgeobase->getLocation('178.207.171.10'));die;
//        $this->layout = false;
//        if (!isset(Yii::$app->request->cookies['city'])) {
//            Yii::$app->response->cookies->add(new \yii\web\Cookie([
//                'name' => 'city',
//                'value' => Yii::$app->ipgeobase->getLocation(Yii::$app->request->userIP)['id'],
//            ]));
//        }
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
//        $this->layout = false;
<<<<<<< HEAD
        $searchCity = new CitySearch();
        $dataProviderCity = $searchCity->search(Yii::$app->request->queryParams);
=======
//        $searchCity = new CitySearch();
//        $dataProviderCity = $searchCity->search(Yii::$app->request->queryParams);
>>>>>>> city
        return $this->render('index',[
            'name'=>'Marat',
//            'ipgeo'=> Yii::$app->ipgeobase->getLocation('144.206.192.6'),
//            'searchCity'=>$searchCity,
//            'dataProviderCity'=>$dataProviderCity,
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionUgb()
    {
        Yii::$app->ipgeobase->updateDB();
        return $this->render('about');
    }
}
