<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 26.08.15
 * @time: 11:54
 */

return [
    'params' => [
        'paramsTemplate' => [
             0 => 'code',
             1 => 'name',
             2 => 'manufacture',
             3 => 'price',
             4 => 'quantity',
             5 => 'srokmin',
             6 => 'srokmax',
             7 => 'provider',
             8 => 'reference',
             9 => 'srok',
            10 => 'estimation',
            11 => 'lotquantity',
            12 => 'pricedate',
            13 => 'pricedestination',
            14 => 'skladid',
            15 => 'sklad',
            16 => 'groupid',
            17 => 'flagpostav',
            18 => 'storeid',
            19 => 'pid',
            20 => 'srokdays',
            21 => 'weight',
            22 => 'cross',
            23 => 'ball',
        ],
        'providersFieldsParams' => [
            'Emex' => [
                'method' => [
                    'findDetails' => [
                        'params' => [
                            'in' => [
                                0 => 'DetailNum',                            // Номер
                                1 => 'DetailNameRus',                        // Информация
                                2 => 'MakeName',                             // Производитель
                                3 => 'ResultPrice',
                                4 => 'Quantity',
                                5 => 'ADDays',                               // Доставка
                                6 => 'DeliverTimeGuaranteed',                // Доставка
                               10 => 'DDPercent',
                               11 => 'LotQuantity',
                               14 => 'PriceLogo',
                               15 => 'PriceCountry',
                               16 => 'PriceGroup',
                            ],
                            'out' => [
                                0 => 'DetailNum',
                                1 => 'DetailNameRus',
                                2 => 'MakeName',
                                3 => 'ResultPrice',
                                4 => 'Quantity',
                                5 => 'ADDays',
                                6 => 'DeliverTimeGuaranteed',
                               10 => 'DDPercent',
                               11 => 'LotQuantity',
                               14 => 'PriceLogo',
                               15 => 'PriceCountry',
                               16 => 'PriceGroup',
                            ]
                        ]
                    ]
                ]
            ],
            'Iksora' => [
                'method' => [
                    'findDetails' => [
                        'params' => [
                            'in' => [
                                0 =>    'Number',                               // Номер
                                2 =>    'Maker',                                // Производитель
                            ],
                            'out' => [
                                0 => 'number',
                                1 => 'name',
                                2 => 'maker',
                                3 => 'price',
                                4 => 'quantity',
                                5 => 'days',
                                6 => 'dayswarranty',
                                8 => 'orderrefernce',
                                11=> 'lotquantity',
                                12=> 'date',
                                13=> 'pricedestination',
                                15=> 'region',
                                10=> 'estimation',
                                16=> 'group',

                            ]
                        ]
                    ]
                ]
            ],
            'Partkom' => [
                'method' => [
                    'findDetails' => [
                        'params' => [
                            'in' => [
                                0 =>    'number',                               // Номер
                                1 =>    'description',                          // Информация
                                2 =>    'maker',                                // Производитель
                                5 =>    'minDeliveryDays',                      // Доставка
                                6 =>    'maxDeliveryDays',                      // Доставка
                                11 =>   'minQuantity',
                                12 =>   'lastUpdateDate',
                                13 =>   'PriceDestination',
                                14 =>   'providerId',
                                15 =>   'providerDescription',
                                16 =>   'detailGroup',
                            ],
                            'out' => [
                                0 =>    'number',
                                1 =>    'description',
                                2 =>    'maker',
                                3 =>    'price',
                                4 =>    'quantity',
                                5 =>    'minDeliveryDays',
                                6 =>    'warrantedDeliveryDays',
                                10 =>   'statProvider',
                                11 =>   'minQuantity',
                                12 =>   'lastUpdateDate',
                                13 =>   'PriceDestination',
                                14 =>   'providerId',
                                15 =>   'providerDescription',
                                16 =>   'detailGroup',

                            ]
                        ]
                    ]
                ]
            ],
            'Moskvorechie' => [
                'method' => [
                    'findDetails' => [
                        'params' => [
                            'in' => [
                                0 => 'nr',                                     // = search number" - Номер производителя
                                2 => 'f',                                      // = brand name - Название производителя, при необходимости мы сможете отфильтровать по определенному производителю.
                            ],
                            'out' => [
                                0  => 'nr',
                                1  => 'name',
                                2  => 'brand',
                                3  => 'price',
                                4  => 'stock',
                                6  => 'delivery',
                                8  => 'gid',
                                11 => 'minq',
                                12 => 'upd',
                            ]
                        ]
                    ]
                ]
            ],
            'Berg' => [
                'method' => [
                    'findDetails' => [
                        'params' => [
                            'in' => [
                                'items' => [
                                    [
                                        0  => 'resource_article',
                                        2 => 'brand_id',
        //                              2  => 'brand_name',
                                    ]
                                ]
                            ],
                            'out' => [
                                0 => 'article',
                                1 => 'name',
                                3 => 'price',
                                4 => 'quantity',
                                5 => 'average_period',
                                6 => 'assured_period',
                                10 => 'reliability',
                                11 => 'multiplication_factor'
                            ]
                        ]
                    ]
                ]
            ],
            'Over' => [
                'method' => [
                    'findDetails' => [
                        'params' => [
                            'in' => [
                                0 => 'code',                               // Номер
                                2 => 'manufacture',                                // Производитель
                            ],
                            'out' => [
                                0 => 'code',
                                1 => 'name',
                                2 => 'manufacture',
                                3 => 'price',
                                4 => 'quantity',
                                5 => 'srokmin',
                                6 => 'srokmax',
                                11 => 'lotquantity',
                                12 => 'pricedate',
                                14 => 'skladid',
                                15 => 'sklad',
                                17 => 'flagpostav',
                            ]
                        ]
                    ]
                ]
            ],
            'Kd' => [
                'method' => [
                    'findDetails' => [
                        'params' => [
                            'in' => [
                                0 => 'detailnumber',
                            ],
                            'out' => [
                                0 => 'detailnumber',
                                1 => 'detailname',
                                3 => 'price',
                                4 => 'quantity',
                                6 => 'srokmax',
                                10 => 'estimation',
                                16 => 'groupid',
                                18 => 'storeid'
                            ]
                        ]
                    ]
                ]
            ],
        ]
    ]
];