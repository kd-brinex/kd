<?php
$db = ['components'=>[
    'dev' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=Kolesa;port=3306',
        'username' => 'root',
        'password' => 'Вщи1ук2ьфт3гы4',
        'charset' => 'utf8',
        'enableSchemaCache' => true,
        'schemaCacheDuration' => 3600,
        'schemaCache' => 'cache',],


    'prod' => [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=brinex1',
    'username' => 'brinexdev',
    'password' => 'QwFGHythju8',
    'charset' => 'utf8'],


    'test' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=brinex1',
        'username' => 'brinexdev',
        'password' => 'QwFGHythju8',
        'charset' => 'utf8'],
]];
//var_dump(YII_ENV);die;
return $db;
