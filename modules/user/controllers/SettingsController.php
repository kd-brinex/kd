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
use app\modules\user\models\SettingsForm;

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
        $model = new \app\modules\user\models\OrderSearch();
        $model = $model->search();
        return  $this->render('orders',['model' => $model]);
    }
    public function actionAccount()
    {
        /** @var SettingsForm $model */
        $model = \Yii::createObject(SettingsForm::className());
        $this->performAjaxValidation($model);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account details have been updated'));
            return $this->refresh();
        }

        return $this->render('account', [
            'model' => $model,
        ]);
    }
}