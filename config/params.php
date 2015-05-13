<?php
use yii\helpers\Html;

$catalog = require(__DIR__ . '/catalog.php');

return [
    'Parts' => [
        'sort' => ['attributes' => ['name', 'price'],],
        'pagination' => ['pageSize' => 25,],
        'columns' => [

            [
                'attribute' => 'name',
                'label' => 'Название',
                'format'=>'html',
                'value' => function ($model, $index, $widget) {
                    return $model['code'] . ' ' . $model['name'] . ' ' . $model['manufacture'];
                },
            ],

            [
                'attribute' => 'price',
                'label' => 'Цена',
            ],
            [
                'attribute' => 'quantity',
                'label' => 'Количество',
            ],
            [
                'attribute' => 'srok',
                'label' => 'Доставка',
                'options' => ['class' => 'col-xs-2'],
//                'value' => function ($model, $index, $widget) {
//                    return $model['srokmin'] . (($model['srokmin'] < $model['srokmax']) ? '-' . $model['srokmax'] : '');
//                },
            ],
             [
                 'attribute'=>'provider',
                 'label'=>'Поставщик',
             ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{basket}',
                'buttons' => [
                    'basket' => function ($url, $model) {
                        return Html::a('<i class="icon-shopping-cart icon-white"></i>Заказать', $url, [
                            'title' => 'Заказать',
                            'class' => 'btn btn-primary btn-xs',
                        ]);
                    },
                ],
            ],
        ],
        'PartsProvider' => [


            'Avtostels' => [
                'class' => 'app\modules\autoparts\providers\Avtostels',
                '_wsdl_uri' => 'https://allautoparts.ru/WEBService/SearchService.svc/wsdl?wsdl',   //Ссылка на WSDL-документ сервиса
                'fields' => [
                    "code" => "AnalogueCodeAsIs",//Номер
                    "name" => "ProductName", //Информация
                    "manufacture" => "AnalogueManufacturerName", //Производитель
                    "price" => "Price", //Цена
                    "quantity" => "Quantity", //Количество
                    "srokmin" => "PeriodMin", //Доставка в днях минимальная
                    "srokmax" => "PeriodMax", //Доставка в днях максимальная

//0 - Original - Искомая деталь;
//1 - ReplacementOriginal - Оригинальная замена на искомую деталь (замена того же производителя);
//2 - ReplacementNonOriginal - Не оригинальная замена (аналог) на искомую деталь (замена от другого производителя);
//3 - ReCross - Кросс к замене или аналогу искомой детали. 0 - оригинальная деталь; 1 - оригинальная замена; 2 - неоригинальная замена

                ],
                'marga' => 1.15,
                'id' => 3,
                'name' => 'Автостелс',
                'methods' => ['FindDetails' => 'SearchOffer'],

            ],
            'Iksora' => [
                'class' => 'app\modules\autoparts\providers\Iksora',
                '_wsdl_uri' => 'http://ws.auto-iksora.ru/searchdetails/searchdetails.asmx?WSDL',   //Ссылка на WSDL-документ сервиса
                'fields' => [
                    "code" => "detailnumber",//Номер
                    "name" => "detailname", //Информация
                    "manufacture" => "maker_name", //Производитель
                    "srokmin" => "days", //Доставка
                    "srokmax" => "dayswarranty", //Доставка
                    "sklad"=>"regionname",
                ],
                'marga' => 1.15,
                'id' => 1,
                'name' => 'Иксора',
                'methods' => ['FindDetails' => 'FindDetailsXML'],
            ],
            'Emex' => [
                'class' => 'app\modules\autoparts\providers\Emex',
                '_wsdl_uri' => 'http://ws.emex.ru/EmExService.asmx?WSDL',   //Ссылка на WSDL-документ сервиса
                'fields' => [
                    "code" => "DetailNum",//Номер
                    "name" => "DetailNameRus", //Информация
                    "manufacture" => "MakeName", //Производитель
                    "srokmin" => "ADDays", //Доставка
                    "srokmax" => "DeliverTimeGuaranteed", //Доставка
                    "price" => "ResultPrice",
                    "lotquantity" => "LotQuantity",
                    "quantity" => "Quantity",
                    "skladid"=>"PriceLogo",
                    "sklad"=>"PriceCountry",
                    "groupid"=>"PriceGroup",
                ],
                'marga' => 1.15,
                'id' => 4,
                'name' => 'Emex',
                'methods' => ['FindDetails' => 'FindDetailAdv3'],
                ],
            'Partkom' => [
                'class' => 'app\modules\autoparts\providers\Partkom',
                '_wsdl_uri' => 'http://www.part-kom.ru/webservice/search.php?wsdl',   //Ссылка на WSDL-документ сервиса
                'fields' => [
                    "code" => "number",//Номер
                    "name" => "description", //Информация
                    "manufacture" => "maker", //Производитель
                    "srokmin" => "minDeliveryDays", //Доставка
                    "srokmax" => "maxDeliveryDays", //Доставка
                    "lotquantity" => "minQuantity",
                    "pricedate" =>"lastUpdateDate",
                    "pricedestination"=>"PriceDestination",
                    "statSuccessCount"=>"statSuccessCount",
                    "statRefusalCount"=>"statRefusalCount",
                    "statTotalOrderCount"=>"statTotalOrderCount",
                    "skladid"=>"providerId",
                    "sklad"=>"providerDescription",
                    "groupid"=>"detailGroup",
                ],
                'marga' => 1.15,
                'id' => 2,
                'name' => 'Партком',
                'methods' => ['FindDetails' => 'findDetail'],
            ],
        ],],
    'securitykey' => 'k',
    'nouser_id' => 5,
    'adminEmail' => 'husainov.m@brinex.ru',
    'catalog' => $catalog,
    'host' => 'http://img2.kolesa-darom.ru/img/',
    'image' => [
        'disk' => [
            'normal' => 'disk/',
            'big' => 'disk/big/',
            'name' => 'category_id',
        ],
        'shina' => [
            'normal' => 'tyres/',
            'big' => 'tyres/big/',
            'name' => 'category_id',
        ],
        'bagachniki' => [
            'normal' => 'video/',
            'big' => 'video/big/',
            'name' => 'category_id',
        ],
        'quadroshina' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/',
            'name' => 'category_id',
        ],
        'kovriki' => [
            'normal' => 'video/',
            'big' => 'video/big/',
            'name' => 'category_id',
        ],
        'agriculturalshina' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/',
            'name' => 'category_id',
        ],
        'autokameri' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/',
            'name' => 'category_id',
        ],
        'industrialshina' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/',
            'name' => 'category_id',
        ],
        'velopokryshki' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/',
            'name' => 'category_id',
        ],
        'vstavka' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/',
            'name' => 'id',
            //id
        ],
        'zashity' => [
            'normal' => 'video/',
            'big' => 'video/big/',
            'name' => 'category_id',
        ],
        'autosignalizacii' => [
            'normal' => 'video/',
            'big' => 'video/big/',
            'name' => 'category_id',
        ],
        'shetki' => [
            'normal' => 'video/',
            'big' => 'video/big/',
            'name' => 'category_id',
        ],
        'kompressor' => [
            'normal' => 'radar/',
            'big' => 'radar/big/',
            'name' => 'category_id',
        ],
        'nabor' => [
            'normal' => 'video/',
            'big' => 'video/big/',
            'name' => 'category_id',
        ],
        'vetroviki' => [
            'normal' => 'video/',
            'big' => 'video/big/',
            'name' => 'category_id',
        ],
        'ustkolza' => [
            'normal' => 'video/',
            'big' => 'video/big/',
            'name' => 'category_id',
        ],
        'boatoil' => [
            'normal' => 'gruz/',
            'big' => 'gruz/big/',
            'name' => 'id',
            //id
        ],
        'motomotormasla' => [
            'normal' => 'motomasla/',
            'big' => 'motomasla/big/',
            'name' => 'category_id',
        ],
        'avtomotormasla' => [
            'normal' => 'motomasla/',
            'big' => 'motomasla/big/',
            'name' => 'category_id',
        ],
        'gruzmotormasla' => [
            'normal' => 'motomasla/',
            'big' => 'motomasla/big/',
            'name' => 'category_id',
        ],
    ],
];
