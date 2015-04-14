<?php
$params = require(__DIR__ . '/params.php');
$db_connect = require(__DIR__ . '/db.php');
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'name' => 'Колеса-даром',
    'components' => [

        'session' => [
            'class' => 'yii\web\Session',
//            'timeout'=>28800,
//            'cookieParams' => array (
//                'lifetime' =>86400,
//                'path' => '/',
//                'httponly' => 'on',
//            ),
            // 'db' => 'mydb',
//             'sessionTable' => 'session',

        ],
        'city' => [
            'class' => 'app\modules\city\City',
        ],
        'ipgeobase' => [
            'class' => 'app\modules\city\IpGeoBase',
            'useLocalDB' => true,
        ],
        'a2d' => [
            'class' => 'app\modules\auto\A2d',

        ],
        'adcpi' => [
            'class' => 'app\modules\auto\Adcapi',
//            'login'=>'kd',
//            'pass'=>'JVBDhGpejncE',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
//            'enableStrictParsing' => true,
            'suffix' => '',
            'rules' => [
                '' => 'site/index',
                'login' => 'user/security/login',
                'register' => 'user/registration/register',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'profile' => 'user/settings/profile',
                'ugb' => 'site/ugb',
                'ugb' => 'site/ugb',//перегружает данные в таблицы городов
                'citys' => 'city/city',
                'cities/<id:\w+>' => 'city/city/view',
                'clist' => 'city/city/list',
                'basket' => 'basket/basket',
                'basket/<mode:\w+>' => 'basket/basket/put',

                'auto' => 'auto/auto',
                'auto/marks/<typeid:\w+>' => 'auto/auto/marks',
                'auto/models/<typeid:\w+>_<markid:\w+>' => 'auto/auto/models',
                'auto/tree/<modelid:\w+>' => 'auto/auto/tree',
//                'auto/map/<modelid:\w+>/<treeid:\w+>' => 'auto/auto/map',
                'finddetails'=>'tovar/tovar/finddetails',
                 'tovar/<id:\w+>' => 'tovar/tovar/view',
                'tovars/<tip_id:\w+>' => 'tovar/tovar/category',


            ],
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'BrinexKolesaDarom2015',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
//        'user' => [
//            'identityClass' => 'app\models\User',
//            'enableAutoLogin' => true,
//
//
//        ],
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
                'host' => 'smtp.gmail.com',
                'username' => 'maratjobmail@gmail.com',
                'password' => 'HuMa250773-gmail',
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
//                'twig' => [
//                    'class' => 'yii\twig\ViewRenderer',
//                    // set cachePath to false in order to disable template caching
//                    'cachePath' => '@runtime/Twig/cache',
//                    // Array of twig options:
//                    'options' => [
//                        'auto_reload' => true,
//                    ],
//                    'globals' => ['html' => '\yii\helpers\Html'],
//                    'uses' => [
//                        'yii\helpers\Html',
//                        'yii\bootstrap\Nav',
//                        'yii\bootstrap\NavBar',
//                        'yii\widgets\Breadcrumbs',
//                        'app\assets\AppAsset',
//
//                    ],
//                    'options' => [
//
//
//                    ],
//                    // ... see ViewRenderer for more options
//                ],
            ],

            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views/settings' => '@app/modules/user/views/settings'
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
        'auto' => [
            'class' => 'app\modules\auto\Auto',
            'catalog' => 'auto2d'
        ],
        'basket' => [
            'class' => 'app\modules\basket\Basket'
        ],
        'city' => [
            'class' => 'app\modules\city\City'
        ],
        'tovar' => [
            'class' => 'app\modules\tovar\Tovar',
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'modelMap' => [
                'User' => 'app\modules\user\models\User',
                'Profile' => 'app\modules\user\models\Profile',
            ],
            'controllerMap' => [
                'settings' => 'app\modules\user\controllers\SettingsController'
            ],


//            'class' => 'app\modules\user\Module',
            /*       'components' => [

                              'manager' => [
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
                              ],
                       ],*/
            'enableUnconfirmedLogin' => true,
            'confirmWithin' => 21600,
            'admins' => ['marat'],

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
