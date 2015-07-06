<?php
$params = require(__DIR__ . '/params.php');
$db_connect= require(__DIR__ . '/db.php');
//$db_connect = $db_config['components'][YII_ENV];
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'name' => 'Колеса-даром',
//    'layout' => 'main.twig',
    'components' => [
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'appendTimestamp' => true,
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/modules/user/views'
                ],
            ],
//            'class' => 'yii\web\View',
//            'renderers' => [
//                'twig' => [
//                    'class' => 'yii\twig\ViewRenderer',
//                    'cachePath' => '@runtime/Twig/cache',
//                    'options' => ['auto_reload' => true], /*  Array of twig options */
//                    'globals' => ['html' => '\yii\helpers\Html'],
//                ],
//            ],
        ],

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
        'authManager' => [
            'class' => 'dektrium\rbac\components\DbManager',
//            'defaultRoles' => [
//                'user',
//                'manager',
//                'Admin',
//                'SA',
//                'Parts',
//
//            ],
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
                'partner' => 'site/partner',
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
                'finddetails' => 'tovar/tovar/finddetails',
                'tovar/<id:\w+>' => 'tovar/tovar/view',
                'tovars/<tip_id:\w+>' => 'tovar/tovar/category',

                //Админка
                'admin/partsprovider' => 'autoparts/provider',
                'admin/partsprovider/view' => 'autoparts/provider/view',
                'admin/partsprovider/create' => 'autoparts/provider/create',
                'admin/partsprovider/update' => 'autoparts/provider/update',
                'admin/partsprovider/delete' => 'autoparts/provider/delete',
                'admin/partsprovider/index' => 'autoparts/provider/index',

                'admin/partsuser' => 'autoparts/provideruser',
                'admin/partsuser/index' => 'autoparts/provideruser/index',
                'admin/partsuser/view' => 'autoparts/provideruser/view',
                'admin/partsuser/create' => 'autoparts/provideruser/create',
                'admin/partsuser/update' => 'autoparts/provideruser/update',
                'admin/partsuser/delete' => 'autoparts/provideruser/delete',

                'admin/partssrok' => 'autoparts/providersrok',
                'admin/partssrok/view' => 'autoparts/providersrok/view',
                'admin/partssrok/create' => 'autoparts/providersrok/create',
                'admin/partssrok/update' => 'autoparts/providersrok/update',
                'admin/partssrok/delete' => 'autoparts/providersrok/delete',
                //Пользователи
                'admin/user' => '/user/admin/index',
                'admin/roles' => '/rbac/role/index',
                'admin/permissions' => '/rbac/permissions/index',
                // Автокаталоги
                'toyota'=>'toyota/catalog',
                'toyota/model'=>'toyota/catalog/model',
                'toyota/catalog'=>'toyota/catalog/catalog',
                'toyota/album'=>'toyota/catalog/album',
                'toyota/page'=>'toyota/catalog/page',


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
                'port' => '587',
                'encryption' => 'tls',
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
        'db' => $db_connect['components'][YII_ENV],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views/settings' => '@app/modules/user/views/settings',

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

        'rbac' => [
            'class' => 'dektrium\rbac\Module',
            'layout'=> '/admin.php',
        ],
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
        'autoparts' => [
            'class' => 'app\modules\autoparts\Provideruser',
        ],
        'toyota' => [
            'class' => 'app\modules\catalog\Catalog',
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => (YII_ENV=='dev')?'mysql:host=127.0.0.1;dbname=toyota;port=1111':'mysql:host=localhost;dbname=toyota',
                'username' => 'brinexdev',
                'password' => 'QwFGHythju8',
                'charset' => 'utf8'],
            'image'=>'http://new.kolesa-darom.ru:8080/image/toyota/',

        ],
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
            'layout' => '/admin.php',
            'modelMap' => [
                'User' => 'app\modules\user\models\User',
                'Profile' => 'app\modules\user\models\Profile',

            ],
            'controllerMap' => [
                'settings' => 'app\modules\user\controllers\SettingsController',
//                'admin' => 'app\modules\user\controllers\AdminController'
            ],

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

    $config['components']['assetManager']['forceCopy'] = true;
}

return $config;
