<?php

namespace app\modules\user\controllers;

use dektrium\user\controllers\RegistrationController as BaseController;
use app\modules\user\models\RegistrationForm;
use yii\web\NotFoundHttpException;
use dektrium\user\models\User;


class RegistrationController extends BaseController
{

    public function actionRegister()
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException;
        }

        $model = \Yii::createObject(RegistrationForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
            return $this->render('/message', [
                'title'  => \Yii::t('user', 'Your account has been created'),
                'module' => $this->module,
            ]);
        }

        return $this->render('register', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }

    public function actionConnect($account_id)
    {
        //var_dump("connect");die;
        $account = $this->finder->findAccountById($account_id);

        if ($account === null || $account->getIsConnected()) {
            throw new NotFoundHttpException;
        }

        /** @var User $user */
        $user = \Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'connect'
        ]);


        if(\Yii::$app->request->get("provider")=='kd')
        {
            $data=array();
            $data['User']=array();
            $data['User']['username']=\Yii::$app->request->get("username");
            $data['User']['email']=\Yii::$app->request->get("email");
        }
        else {
            $data = \Yii::$app->request->post();
        }

        if ($user->load($data) && $user->create()) {
            $account->user_id = $user->id;
            $account->save(false);
            \Yii::$app->user->login($user, $this->module->rememberFor);
            return $this->goBack();
        }

        return $this->render('connect', [
            'model'   => $user,
            'account' => $account
        ]);
    }


}
