<?php
$catalog = require(__DIR__ . '/catalog.php');
return [
    'adminEmail' => 'husainov.m@brinex.ru',
    'catalog' => $catalog,
    'host' => 'http://img2.kolesa-darom.ru/img/',
    'image' => [
        'disk' => [
            'normal' => 'disk/',
            'big' => 'disk/big/',
        ],
        'shina' => [
            'normal' => 'tyres/',
            'big' => 'tyres/big/',
        ],
        'bagachniki' => [
            'normal' => 'video/',
            'big' => 'video/big/'
        ],
        'quadroshina' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/'
        ],
        'kovriki' => [
            'normal' => 'video/',
            'big' => 'video/big/'
        ],
        'agriculturalshina' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/'
        ],
        'autokameri' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/'
        ],
        'industrialshina' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/'
        ],
        'velopokryshki' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/'
        ],
        'vstavka' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/'
        ],
        'zashity' => [
            'normal' => 'video/',
            'big' => 'video/big/'
        ],
        'autosignalizacii' => [
            'normal' => 'video/',
            'big' => 'video/big/'
        ],
    ],
];
