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
//use app\modules\user\clients\KD;
use yii\authclient\ClientInterface;
use yii\helpers\Url;
use dektrium\user\models\Account;

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

    public function authenticate(ClientInterface $client)
    {

        $attributes = $client->getUserAttributes();
        $provider   = $client->getId();
        $clientId   = $attributes['id'];

        $account = $this->finder->findAccountByProviderAndClientId($provider, $clientId);

        if ($account === null) {
            $account = \Yii::createObject([
                'class'      => Account::className(),
                'provider'   => $provider,
                'client_id'  => $clientId,
                'data'       => json_encode($attributes),
            ]);
            $account->save(false);
        }

        if (null === ($user = $account->user)) {
            if($provider == 'kd')
            {
                $this->action->successUrl = Url::to(['/user/registration/connect', 'account_id' => $account->id, 'provider' => $provider,'username' => $attributes['username'], 'email' => $attributes['email']]);
            }
            else
            {
                $this->action->successUrl = Url::to(['/user/registration/connect', 'account_id' => $account->id]);
            }
        } else {
            \Yii::$app->user->login($user, $this->module->rememberFor);
        }
    }


}
