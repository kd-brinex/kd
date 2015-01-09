<?php
return [
    'prod' => [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=brinex1',
    'username' => 'brinex',
    'password' => 'G90BqaKJ',
    'charset' => 'utf8'],

    'dev' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=brinex1;port=1111',
        'username' => 'madmin',
        'password' => 'c91Jm0YL1KAa',
        'charset' => 'utf8'],

    'test' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=brinex1',
        'username' => 'brinex',
        'password' => 'G90BqaKJ',
        'charset' => 'utf8'],
];
