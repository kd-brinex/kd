<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 23.03.15
 * Time: 9:46
 */
namespace app\modules\user\controllers;

use dektrium\user\controllers\SettingsController as BaseSettingsController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class SettingsController extends BaseSettingsController
{
    public function behaviors()
    {
//    $this->layout='/main.php';
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['profile', 'account', 'confirm', 'networks', 'connect', 'disconnect', 'cars', 'orders'],
                        'roles'   => ['@']
                    ],
                ]
            ],
        ];
    }
    public function actionCars()
    {
       return $this->render('cars',[]);
    }

    public function actionOrders()
    {
       return  $this->render('orders',[]);
    }
}