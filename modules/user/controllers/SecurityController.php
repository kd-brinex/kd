<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user\controllers;

use app\modules\user\models\UserRemote;

use dektrium\user\controllers\SecurityController as BaseController;
use app\modules\user\models\LoginForm;

class SecurityController extends BaseController
{
    public function actionLogin()
    {
        $model = \Yii::createObject(LoginForm::className());

        $this->performAjaxValidation($model);
//        $user_remote= new UserRemote();
//        $params=\Yii::$app->getRequest()->post();
//        var_dump($params);die;
//        if (isset($params['login-form'])){
//            var_dump($params['login-form']['login'],$params['login-form']['password']);die;
//        $ruser=$user_remote->getRemoteUser($params['login-form']['login'],$params['login-form']['password']);

//        }
        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }

}
