<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user;

use dektrium\user\Bootstrap as BaseModule;

class Bootstrap extends  BaseModule
{
    /** @var array Model's map */
//    private $_modelMap = [
//        'User'             => 'dektrium\user\models\User',
//        'Account'          => 'dektrium\user\models\Account',
//        'Profile'          => 'dektrium\user\models\Profile',
//        'Token'            => 'dektrium\user\models\Token',
//        'RegistrationForm' => 'dektrium\user\models\RegistrationForm',
//        'ResendForm'       => 'dektrium\user\models\ResendForm',
//        'LoginForm'        => 'dektrium\user\models\LoginForm',
//        'SettingsForm'     => 'dektrium\user\models\SettingsForm',
//        'RecoveryForm'     => 'dektrium\user\models\RecoveryForm',
//        'UserSearch'       => 'dektrium\user\models\UserSearch',
//    ];

    /** @inheritdoc */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    public function bootstrap($app)
    {
var_dump($app);die;
        parent::bootstrap($app);
        $app->get('i18n')->translations['user*'] = [
                'class'    => PhpMessageSource::className(),
                'basePath' => __DIR__ . '/messages',
            ];

    }
}