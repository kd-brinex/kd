<?php
use yii\helpers\Html;

$catalog = require(__DIR__ . '/catalog.php');

return [
    'Api'=>[
        'tovar_tip'=>['tip_id',"category_id","name","store_id","price","count","ball","description",'image']
    ],
    /**
     * Настройки для модуля autoparts
     */
    'Parts' => [
        'sort' => ['attributes' => ['name', 'price'],],
        'pagination' => ['pageSize' => 25,],
        'columns' => [
            'sklad',
            [
                'attribute'=>'code',
                'label'=>'Артикул',
            ],
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
                'attribute' => 'srokdays',
                'label' => 'srokdays',
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
                 'label'=>'Провайдер',
             ],
            [
                'attribute'=>'flagpostav',
                'label'=>'Поставщик',
            ],
            [
                'attribute'=>'estimation',
                'label'=>'Надежность',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{basket}',
                'buttons' => [
                    'basket' => function ($url, $model, $key) {
                         return Html::a('<i class="icon-shopping-cart icon-white "></i>Заказать', '#', [
                            'title' => 'Заказать',
                             'class' => 'btn btn-primary btn-xs orderbud'.$key.'',
                            'onClick' => '$.ajax({ type :"POST", "data" : '.\yii\helpers\JSON::encode($model).', url : "'.\yii\helpers\Url::to(['tovar/basket']).'", success : function(d) { $(".orderBud'.$key.'").parent().html(d) } });return false;'
                        ]);
                    },
                ],
            ],
        ],
        'PartsProvider' => [
            'Kd' => [
                'class' => 'app\modules\autoparts\providers\Kd',
//                '_wsdl_uri' => 'http://new.kolesa-darom.ru/api/api/search',   //Ссылка на WSDL-документ сервиса
                'fields' => [
                    'code'=>'detailnumber',
                    "name" => "detailname", //Информация
                    "manufacture" => "maker_name", //Производитель
                    "srokmin" => "srokmin", //Доставка
                    "srokmax" => "srokmax", //Доставка
                    "sklad"=>"storeid",


                ],
                'marga' => 1,
                'id' => 5,
                'name' => 'Колеса даром',
                'methods' => ['FindDetails' => 'FindDetails'],

            ],
            'Over' => [
                'class' => 'app\modules\autoparts\providers\Over',
                'internal_day'=>4,
//                '_wsdl_uri' => 'http://new.kolesa-darom.ru/api/api/search',   //Ссылка на WSDL-документ сервиса
                'fields' => [
                    'code' => 'code',
                    'name' => 'name',
                    'manufacture' => 'manufacture',
                    'price' => 'price',
                    'quantity' => 'quantity',
                    'srokmin' => 'srokmin',
                    'srokmax' => 'srokmax',
                    'estimation' =>'0',
                    'lotquantity' => 'lotquantity',
                    'pricedate' => 'pricedate',
                    'skladid' => 'skladid',
                    'sklad' => 'sklad',
                    'flagpostav' => 'flagpostav',
                    'storeid' => 'storeid',

                ],
                'marga' => 1,
                'id' => 7,
                'name' => 'Over',
                'methods' => ['FindDetails' => 'FindDetails'],

            ],

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
                'methods' => ['FindDetails' => 'SearchOffer3'],
                'options' => ['soap_version' => SOAP_1_1],

            ],
            'Iksora' => [
                'class' => 'app\modules\autoparts\providers\Iksora',
                '_wsdl_uri' => 'http://ws.auto-iksora.ru:83/searchdetails/searchdetails.asmx?WSDL',   //Ссылка на WSDL-документ сервиса
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
//                'methods' => ['FindDetails' => 'FindDetailsXML'],
                'methods' => ['FindDetails' => 'FindDetailsStockXML'],
                'options' => ['soap_version' => SOAP_1_1],
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
                    "estimation"=>"DDPercent",
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
    'nouser_id' => 7,
    'adminEmail' => 'husainov.m@brinex.ru',
    'catalog' => $catalog,
    'host' => 'http://img2.kolesa-darom.ru/img/',
    'image' => [
        'disk' => [
            'normal' => 'disk/',
            'big' => 'disk/big/',
            'name' => 'category_id',
        ],
        'zapchasti_VAZ' => [
            'normal' => 'zapchastivaz/',
            'big' => 'zapchastivaz/big/',
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
