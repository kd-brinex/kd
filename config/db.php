<?php
$db =(YII_ENV=='prod')?
    [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=brinex1',
    'username' => 'brinexdev',
    'password' => 'QwFGHythju8',
    'charset' => 'utf8'
    ]
    :
    [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=brinex_dev',
        'username' => 'brinexdev',
        'password' => 'QwFGHythju8',
        'charset' => 'utf8',
        'enableSchemaCache' => true,
        'schemaCacheDuration' => 3600,
        'schemaCache' => 'cache',
    ];
return $db;
