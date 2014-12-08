<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'JC6KdmGRa0LYiO6GyGxZTwqoq3kqMjzk',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'husainov.m@brinex.ru',
                'password' => 'HuMa250773-brin',
                'port' => '465',
                'encryption' => 'ssl',
                ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOpenId',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '1459039357681938',
                    'clientSecret' => '9eb99520dea4dc08049c94ddf014cfdd',
                ],
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '4441364',
                    'clientSecret' => 'T5yQkhI0zcg1Dn5uamKz',
                ],
            ],
        ],
    ],
    'params' => $params,
    'modules' =>[
        'user' =>[
            'class' => 'dektrium\user\Module',
            'components' =>[

             /*   'manager' => [
                    'userClass'    => 'dektrium\user\models\User',
                    'tokenClass'   => 'dektrium\user\models\Token',
                    'profileClass' => 'dektrium\user\models\Profile',
                    'accountClass' => 'dektrium\user\models\Account',
                    // Model that is used on user search on admin pages
                    'userSearchClass' => 'dektrium\user\models\UserSearch',
                    // Model that is used on registration
                    'registrationFormClass' => 'dektrium\user\models\RegistrationForm',
                    // Model that is used on resending confirmation messages
                    'resendFormClass' => 'dektrium\user\models\ResendForm',
                    // Model that is used on logging in
                    'loginFormClass' => 'dektrium\user\models\LoginForm',
                    // Model that is used on password recovery
                    'passwordRecoveryFormClass' => 'dektrium\user\models\RecoveryForm',
                    // Model that is used on requesting password recovery
                    'passwordRecoveryRequestFormClass' => 'dektrium\user\models\RecoveryRequestForm',
                ]*/
            ],
            'enableUnconfirmedLogin'=>true,
            'confirmWithin'=>21600,
            'admins' => ['admin'],

        ]
    ]
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
