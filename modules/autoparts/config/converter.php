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
            22 => 'cross'
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
//                                  public 'number' => string 'HY012' (length=5)
//                                  public 'maker' => string 'HDK' (length=3)
//                                  public 'name' => string 'ШРУС ВНЕШНИЙ' (length=23)
//                                  public 'quantity' => string '>4' (length=2)
//                                  public 'lotquantity' => int 2
//                                  public 'price' => float 1827.42
//                                  public 'pricedestination' => float 0
//                                  public 'days' => int 0
//                                  public 'dayswarranty' => int 0
//                                  public 'region' => string 'АВТО-ИКСОРА СКЛАД' (length=32)
//                                  public 'estimation' => string '5 5 5' (length=5)
//                                  public 'orderreference' => string '0-91001-1-64-3-43385284-2-0' (length=27)
//                                  public 'group' => string 'Original' (length=8)
//                                  public 'date' => string '2015-08-20T00:00:00' (length=19)
                                0 =>    'Number',                               // Номер
                                2 =>    'Maker',                                // Производитель

    //                            StockOnly' => '
    //                            SubstFilter' =>
                            ],
                            'out' => [
                                0 => 'number',
                                1 => 'name',
                                2 => 'maker',
                                3 => 'price',
                                4 => 'quantity',
                                11=> 'lotquantity',
                                13=> 'pricedestination',
                                20=> 'days',
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
    //                          'statSuccessCount'=>'statSuccessCount',
    //                          'statRefusalCount'=>'statRefusalCount',
    //                          'statTotalOrderCount'=>'statTotalOrderCount',
                                14 =>   'providerId',
                                15 =>   'providerDescription',
                                16 =>   'detailGroup',
                            ],
                            'out' => [
                                0 =>    'number',
                                1 =>    'description',
                                2 =>    'maker',
                                3 =>    'price',
                                5 =>    'minDeliveryDays',
                                6 =>    'maxDeliveryDays',
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
                                0 => 'nr',
                                1 => 'name',
                                2 => 'brand',
                                3 => 'price'

//                                "nr":"334614",
//                              "brand":"Kayaba",
//                              "name":"Амортизатор BMW 3 E46 -05 пер.прав.газ.SPORT",
//                              "stock":"1",
//                              "delivery":"2 дня",
//                              "minq":"1",
//                              "upd":"25.08.15 08:02",
//                              "price":"4674.30",
//                              "currency":"руб.",
//                              "gid":"1001232170"
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
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];