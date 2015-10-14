<?php

namespace app\modules\user\controllers;

use dektrium\user\controllers\RegistrationController as BaseController;
use app\modules\user\models\RegistrationForm;
use yii\web\NotFoundHttpException;

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

}
