<?php
$converter = require('converter.php');
$providers = require('providers.php');
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 12.08.15
 * @time: 9:22
 */
return yii\helpers\ArrayHelper::merge([
        'components' => [
            'Soap' => [
                'class' => 'app\modules\autoparts\components\BrxSoap',
                'uri' => '',
                'wsdl_cache' => true,
                'trace' => false
            ],
            'Rest' => [
                'class' => 'app\modules\autoparts\components\BrxRest',
                'uri' => ''
            ],
            'Model' => [
                'class' => 'app\modules\autoparts\components\BrxModel'
            ],
            'provider' => [
                'class' => 'app\modules\autoparts\components\BrxProvider'
            ],
            'converter' => [
                'class' => 'app\modules\autoparts\components\BrxDataConverter'
            ],
            'run' => [
                'class' => 'app\modules\autoparts\components\Run'
            ]
        ],

    ],
    $converter,
    $providers
);