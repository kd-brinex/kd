<?php
$db =
    [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=kd-brinex',
        'username' => 'brinexdev',
        'password' => 'QwFGHythju8',
        'charset' => 'utf8',
        'enableSchemaCache' => true,
        'schemaCacheDuration' => 3600,
        'schemaCache' => 'cache',
    ];
return $db;
