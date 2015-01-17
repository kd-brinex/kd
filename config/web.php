<?php
$params = require(__DIR__ . '/params.php');
$db_connect=require(__DIR__ . '/db.php');
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'name'=>'Колеса-даром',
    'components' => [
        'ipgeobase' => [
            'class' => 'himiklab\ipgeobase\IpGeoBase',
            'useLocalDB' => true,
        ],
        'urlManager'=>[
            'enablePrettyUrl' => true,
            'showScriptName' => false,
//            'enableStrictParsing' => true,
            'suffix' => '',
            'rules' => [
                '' => 'site/index',
                'login' => 'user/security/login',
                'register'=> 'user/registration/register',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'profile'=>'user/settings/profile',
                'ugb'=>'site/ugb',
//                'gii' => 'yii/gii',
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
//                'module/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
//                'module/<module:\w+>/<controller:\w+>/<action:\w+>/<id:\w+>' => '<module>/<controller>/<action>/<id>',
            'tovar/<id:\w+>'=>'tovar/tovar/view',
            'tovars/<tip_id:\w+>'=>'tovar/tovar/category'
            ],
           ],

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
        'db' => $db_connect,
        'view' => [
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    // set cachePath to false in order to disable template caching
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => ['html' => '\yii\helpers\Html'],
                    'uses' => [
                        'yii\helpers\Html',
                        'yii\bootstrap\Nav',
                        'yii\bootstrap\NavBar',
                        'yii\widgets\Breadcrumbs',
                        'app\assets\AppAsset',

                    ],
                    'options' => [


                    ],
                    // ... see ViewRenderer for more options
                ],
            ],
        ],
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
    'modules' => [
        'tovar'=>[
            'class'=>'app\modules\site\tovar\Tovar',
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'components' => [

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
            'enableUnconfirmedLogin' => true,
            'confirmWithin' => 21600,
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
