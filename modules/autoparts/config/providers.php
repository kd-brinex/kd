<?php

/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 14.09.15
 * @time: 13:50
 */

/**
 * Массив настроек модуля. Все параметры методов провайдера должны быть обязательно записаны в массив params (даже если они не заполняются по умолчанию) в регистре указанном провайдером
 * ВНИМАНИЕ!!! ВСЕ ПАРАМЕТРЫ РЕГИСТРОЗАВИСИМЫЕ!
 *
 * Пример объкта возвращаемого провайдером Emex
 *     public 'FindDetailAdv3Result' =>
 *          object(stdClass)[174]
 *              public 'Details' =>
 *                  object(stdClass)[175]
 *                      public 'SoapDetailItem' =>
 *                          array (size=59)
 *                              0 =>
 *                                  object(stdClass)[176]
 *                                  public 'GroupId' => int -370
 *                                  public 'PriceGroup' => string 'Original' (length=8)
 *                                  public 'MakeLogo' => string 'HK' (length=2)
 *                                  public 'MakeName' => string 'HDK' (length=3)
 *                                  public 'DetailNum' => string 'HY012' (length=5)
 *                                  public 'DetailNameRus' => string 'ШРУС ВНЕШНИЙ' (length=23)
 *                                  public 'PriceLogo' => string 'MSAS' (length=4)
 *                                  public 'DestinationLogo' => string 'AFL' (length=3)
 *                                  public 'PriceCountry' => string 'Москва' (length=12)
 *                                  public 'LotQuantity' => int 1
 *                                  public 'Quantity' => int 29
 *                                  public 'DDPercent' => string '100.0' (length=5)
 *                                  public 'ADDays' => int 1
 *                                  public 'DeliverTimeGuaranteed' => int 5
 *                                  public 'ResultPrice' => string '2016.9500' (length=9)
 *
 * Пример объекта возвращаемого провайдером Ixora
 *      public 'FindResult' =>
 *          object(stdClass)[174]
 *              public 'DetailInfo' =>
 *                  array (size=39)
 *                      0 =>
 *                          object(stdClass)[175]
 *                              public 'number' => string 'HY012' (length=5)
 *                              public 'maker' => string 'HDK' (length=3)
 *                              public 'name' => string 'ШРУС ВНЕШНИЙ' (length=23)
 *                              public 'quantity' => string '>4' (length=2)
 *                              public 'lotquantity' => int 2
 *                              public 'price' => float 1827.42
 *                              public 'pricedestination' => float 0
 *                              public 'days' => int 0
 *                              public 'dayswarranty' => int 0
 *                              public 'region' => string 'АВТО-ИКСОРА СКЛАД' (length=32)
 *                              public 'estimation' => string '5 5 5' (length=5)
 *                              public 'orderreference' => string '0-91001-1-64-3-43385284-2-0' (length=27)
 *                              public 'group' => string 'Original' (length=8)
 *                              public 'date' => string '2015-08-20T00:00:00' (length=19)
 *
 * Пример объекта возвращаемого провайдером Ixora
 *      0 =>
 *        array (size=22)
 *            'number' => string 'HY012' (length=5)
 *            'maker' => string 'HDK' (length=3)
 *            'makerId' => string '223' (length=3)
 *            'description' => string 'ШРУС внешний HY-12' (length=29)
 *            'providerId' => int 205
 *            'providerDescription' => string 'МСК склад' (length=17)
 *            'minQuantity' => int 10
 *            'storehouse' => boolean false
 *            'minDeliveryDays' => int 1
 *            'averageDeliveryDays' => int 1
 *            'maxDeliveryDays' => int 1
 *            'warrantedDeliv   eryDays' => int 1
 *            'lastUpdateDate' => string '2015-08-20 13:37:40' (length=19)
 *            'statProvider' => int 86
 *            'price' => float 2093
 *            'quantity' => string '2' (length=1)
 *            'detailGroup' => string 'Original' (length=8)
 *            'group' => string '223' (length=3)
 *            'lastOrderDate' => string '' (length=0)
 *            'statSuccessCount' => int 0
 *            'statRefusalCount' => int 0
 *            'statTotalOrderCount' => int 0
 */

/**
 *              ПОЯСНЕНИЯ
 * 1. Параметр "isParamsAsArray" вынужденный костыль. Некоторые провайдеры принмают параметры как массив, а некоторе как строку параметров.
 *      TODO: решить проблему с этим костылем
 */
return [
    'params' => [
        'providers' => [
            /*--------------------------------------------------------EMEX-----------------------------------------------------*/
            'Emex' => [
                'provider_name' => 'Emex',                                  // имя провайдера
                'apiType' => 'Soap',                                        // тип подключения к провайдеру (soap, rest, http и т.д)
                'authParams' => ['login','password'],
                'oldParamsNames' => false,                                                 // все uri с которыми работает провайдер, ключ - это ключ значения в массиве'. По {индекс}'у определяется к какому uri обращатсья
                'uri' => [
                    'http://ws.emex.ru/EmExInmotion.asmx?WSDL',              // uri для работы с корзиной
                    'http://ws.emex.ru/EmExService.asmx?WSDL',              // uri для поиска деталей
                ],
                'isParamsAsArray' => true,                                  // параметр говорит обрамлять ли данные в еще один массив (см. пояснение 1)
                'methods' => [                                              // связка методов api провайдера с нами, определяется как массив 'вызываемое_нами_имя_метода' => ['name' => 'имя_метода_провайдера', 'uri_index' => 'индекс_uri_где_описывается_метод_провайдера']
                    'findDetails' => [
                        'name' => 'FindDetailAdv3',
                        'uri_index' => 1,
                        // параметры метода провайдера
                        'params' => [
                            'login' => '',
                            'password' => '',
                            'makeLogo' => '',                             // Лого фирмы (не обязательно)
                            'substLevel' => 'All',                        // Фильтр по заменам (обязательно). Параметры: OriginalOnly - без замен и аналогов; All - с заменами и аналогами.
                            'substFilter' => 'FilterOriginalAndAnalogs',  // фильтр по типу деталей (обязательно). Параметры: None - не фильтровать; FilterOriginalAndReplacements - только искомый номер, новый номер и замены искомого номера; FilterOriginalAndAnalogs - только искомый номер и аналоги.
                            'deliveryRegionType' => 'PRI',                // тип доставки PRI; ALT (обязательно, по умолчанию надо указывать PRI)
                            'detailNum' => '',                            // номер детали
                        ]
                    ],
                    'getOrderState' => [
                        'name' => 'InMotion_Consumer_Get',
                        'uri_index' => 0,
                        'params' => [
                            'login' => '',                                 // - логин клиента
                            'password' => '',                              // - пароль клиента
                            'beginDate' => '',                             // - начальная дата выборки. При передаче null фильтрация по этому полю не производится.
                            'endDate' => '',                               // - конечная дата выборки. При передаче null фильтрация по этому полю не производится.
                            'greaterThenGlobalId' => '',                         // - уникальный идентификатор заказа. Движение выбирается для заказов с GlobalId большим, чем это число. При передаче null фильтрация по этому полю не производится.
                            'globalIds' => '',                             // - список заказов для фильтрации; применяется только, если greaterThenGlobalId = null. Тип long[].
                            'states' => '',                                // - список допустимых состояний для фильтрации. Тип int[].
                            'reference' => '',                             // - комментарий, который заносит потребитель
                            'detailNum' => '',                             // - номер детали
                        ]
                    ]
                ],
                'methodsOptions' => [                                       //общие параметры которые должны отправляться во всех методах (напр.: логин и пароль)

                ],
            ],
            /*--------------------------------------------------------IXORA-----------------------------------------------------*/
            'Iksora' => [
                'provider_name' => 'Iksora',
                'apiType' => 'Soap',
                'authParams' => ['authCode'],
                'authParamsTemplate' => [
                    'authCode' => 'password'
                ],
                'oldParamsNames' => true,
                'uri' => [
                    'http://ws.ixora-auto.ru/soap/ApiService.asmx?WSDL',
                ],
                'isParamsAsArray' => true,
                'methods' => [
                    'findDetails' => [
                        'name' => 'Find',
                        'uri_index' => 0,
                        'params' => [
                            'Number' => '',                                 // Номер детали запрашиваемой детали
                            'Maker' => '',                                  // Название производителя детали. Если производитель не задан, результирующий набор содержит данные по всем производителям включая не взаимозаменяемые детали. Полный перечень производителей можно получить вызвав метод GetMakers
                            'StockOnly' => false,                            // Только наличие на складе компании. False - нет, True - да
                            'SubstFilter' => 'All',                         // Фильтр по заменам. All - искомый номер с оригинальными и неоригинальными заменами; Only - только искомый номер; Originals - искомый номер с оригинальными заменами; Analogs - искомый номер с неоригинальными заменами
                            'AuthCode' => ''                                // Ключ безопасности для доступа к сервисам
                        ]
                    ],
                    'toBasket' => [
                        //метод не реализован у провайдера
                    ]
                ],
                'methodsOptions' => [
                ]
            ],
            /*-------------------------------------------------------PARTKOM-----------------------------------------------------*/
            'Partkom' => [
                'provider_name' => 'Partkom',
                'apiType' => 'Soap',
                'authParams' => ['login', 'password'],
                'oldParamsNames' => false,
                'uri' => [
                    'http://www.part-kom.ru/webservice/search.php?wsdl',
                    'http://www.part-kom.ru/webservice/order.php?wsdl',
                ],
                'isParamsAsArray' => false,
                'methods' => [
                    'findDetails' => [
                        'name' => 'findDetail',
                        'uri_index' => 0,
                        'params' => [
                            'login' => '',                                  // string Логин пользователя в системе «ПартКом».
                            'password' => '',                               // string Пароль пользователя в системе «ПартКом».
                            'number' => '',                                 // string Номер искомой детали
                            'makerId' => '',                                // integer Уникальный идентификатор производителя в системе «ПартКом». Может быть получен из справочника производителей MakersDict.
                            'findSubstitutes' => true,                      // boolean Флаг для поиска с заменами и аналогами или без них.
                            'store' => false,                                // boolean Флаг для поиска только в наличии склада «ПартКом».
                            'reCross' => false,                              // boolean Флаг для включения в результаты кроссов к найденным заменам и аналогам.
                        ]
                    ],
                    'toBasket' => [
                        'name' => 'MakeOrderTest',                          // MakeOrderTest метод тестовый для реальной работы убираем слово Test
                        'uri_index' => 1,
                        'params' => [
                            'login' => '',                                  // string Логин пользователя в системе «ПартКом».
                            'password' => '',                               // string Пароль пользователя в системе «ПартКом».
                            'OrderItem' => [
                                (object)[                                       // array of objects Коллекция объектов, описывающих детали в заказе.
                                    'detailNum' => '',                      // string Номер детали
                                    'makerId' => '',                        // integer Уникальный идентификатор производителя в системе «ПартКом». Название поставщика может быть получено из справочника производителей MakersDict.
                                    'description' => '',                    // string Описание детали (рус)
                                    'price' => '',                          // integer Цена в рублях (без копеек)
                                    'providerId' => '',                     // integer Номер поставщика в системе «ПартКом»
                                    'quantity' => '',                       // integer Количество
                                    'reorderAgreement' => '',               // boolean Флаг перезаказа. Если флаг выставлен в true, то при отказе поставщика деталь будет перезаказана.
                                    'possibleIncreasePrice' => '',          // integer Максимально возможное увеличение цены при перезаказе в процентах. Учитывается, если флаг ReorderAgreement выставлен в true.
                                    'possibleIncreaseDeliveryDate' => '',   // integer Максимально возможное увеличение срока доставки при перезаказе в рабочих днях. Учитывается, если флаг ReorderAgreement выставлен в true.
                                    'errorMessage' => '',                   // string  Сообщение ошибки при заказе.
                                    'errorCode' => '',                      // integer Код ошибки при заказе.
                                    'comment' => '',                        // string Комментарий к заказу
                                ]
                            ]
                        ]
                    ]

                ],
                'methodsOptions' => [

                ]
            ],
            /*-------------------------------------------------------BERG-----------------------------------------------------*/
            'Berg' => [
                'provider_name' => 'Berg',
                'apiType' => 'Rest',
                'authParams' => ['key'],
                'authParamsTemplate' => ['key' => 'password'],
                'oldParamsNames' => true,
                'uri' => [
                    'https://api.berg.ru/v0.9/ordering/get_stock.json',
                    'https://api.berg.ru/v0.9/ordering/place_order.json',
                ],
                'isParamsAsArray' => false,
                'methods' => [
                    'findDetails' => [
                        'name' => 'get',
                        'uri_index' => 0,
                        'params' => [
                            'items' => [                                    // Каждый элемент массива items — это ассоциативный массив, который может содержать элементы со следующими ключами.
                                [
//                                    'resource_id' => '',                    // внутренний идентификатор товара
                                    'resource_article' => '',               // артикул товара
                                    'brand_id' => '',                       // внутренний идентификатор бренда (см. /references/brands)
                                    'brand_name' => ''                      // имя бренда
                                ]
                            ],                                              // ассоциативный массив входных поисковых данных. Найденные позиции будут привязываться к ключам этого ассоциативного массива в поле source_idx результатов. (в случае если по какому-то элементу входного набора нет рехзультатов, элементов с таким source_idx в ответе не будет) Каждый элемент входных данных — это так же ассоциативный массив (ключи описаны ниже)
                            'analogs' => 1,                                 // признак-флаг, возвращать ли аналоги (0 — не возвращать[по умолчанию], 1 — возвращать).
                            'warehouse_types' => [],                        // массив, указывающий какие склады должны быть в результатах выполнения запроса. (1 - склад текущего филиала БЕРГ, 2 - ЦС БЕРГ, 3 - дополнительные склады ). По умолчанию результат генеририруется для всех типов складов
                        ],
                    ],
                    'toBasket' => [
                        'name' => 'post',
                        'uri_index' => 1,
                        'params' => [
                            'force' => 1,                                   // флаг, в случае установки в 1 — осуществить заказ даже если запрашиваемого количества гарантированно нет на складе, с которого производится заказ, либо заказывается количество товара не кратное минимальной норме отгрузки. В таком случае позиция с неверным количеством будет пропущена, а информация о ней будет добавлена в массив warnings. Все остальные заказываемые позиции, не имеющие ошибок в заказываемом количестве, будут размещены в заказ.
                            'order'	=> [                                    // Объект заказа (см. описание ниже)
                                'is_test' => 1,                             // флаг тестового заказа. В случае установки в 1, заказ будет сохранён системой и доступен в истории заказов, но не будет принят к дальнейшей обработке. (по умолчанию — 0)
                                'dispatch_type' => 3,                       // тип отгрузки ( 2 — самовывоз, 3 — доставка )
                                'dispatch_at' => '2015-08-31',                        // дата доставки ( в формате ГГГГ-ММ-ДД )
                                'dispatch_time' => 1,                       // флаг времеми доставки ( 1 — до 15:00, 2 — после 15:00)
                                'person' => '',                             // контактное лицо
                                'phone' => '',                              // контактный телефон
                                'comment' => '',		                    // комментарий
                                'shipment_address' => '',                   // адрес доставки
                                'items' => [                                // массив объектов заказываемых товаров
                                    1 => [
                                        'resource_id' => '',                // Внутренний идентификатор товара (см. /ordering/get_stock )
                                        'warehouse_id' => '',               // Внутренний идентификатор склада (см. /ordering/get_stock )
                                        'quantity' => '',                   // Заказываемое количество
                                        'comment' => ''                     // Комментарий к заказываемому товару
                                    ]
                                ]
                            ],                                              // Объект заказываемого товара (OrderItem) :


                        ],
                    ]
                ],
                'methodsOptions' => [
                ]
            ],
            /*---------------------------------------------------МОСКВОРЕЧЬЕ--------------------------------------------------*/
            'Moskvorechie' => [
                'provider_name' => 'Moskvorechie',
                'apiType' => 'Rest',
                'authParams' => ['l', 'p'],
                'uri' => [
                    'http://portal.moskvorechie.ru/portal.api'
                ],
                'authParamsTemplate' => [
                    'l' => 'login',
                    'p' => 'password'
                ],
                'oldParamsNames' => true,
                'isParamsAsArray' => false,
                'methods' => [
                    'findDetails' => [
                        'name' => 'get',
                        'uri_index' => 0,
                        'params' => [
                            'l' => '',                                      // = username - Имя пользователя, логин
                            'p' => '',                                      // = keyphrase - Ключ доступа (64 символа)
                            'act' => 'price_by_nr_firm',                    // = price_by_nr - Название функции
                            'nr' => '',                                     // = search number - Номер производителя
                            // А также дополнительные параметры:
                            'f' => '',                                      // = brand name - Название производителя, при необходимости мы сможете отфильтровать по определенному производителю.
                            'oe' => '',                                     // - По умолчанию поиск производителей производится только по номеру самого производителя, но если добавить данный параметр, тогда можно также передавать оригинальные номера, и позиции будут искаться через кросс таблицы на оригинальные номера.
                            'alt' => '',                                    // - При использовании данного параметра, в результатах данной процедуру будут также выводиться бренды на которые есть аналоги на нашем складе.
                            'avail' => '',                                  // - Отображать только позиции, которые есть в наличии на складе.
//                            'gid' => ''                                     // - Выводить дополнительное поле, идентифицирующее позицию в прайс-листе. Необходимо для выполнения операций с данной позицией, напр. "добавление в корзину".
                        ],
                    ],
                    'toBasket' => [
                        'name' => 'get',
                        'uri_index' => 0,
                        'params' => [
                            'l' => '',                                      // = username" - Имя пользователя, логин
                            'p' => '',                                      // = keyphrase" - Ключ доступа (64 символа)
                            'act' => 'to_basket',                           // = 'to_basket" - Название функции
                            'gid' => '',                                    // - ID позиции в прайс-листе.
                            // А также дополнительные параметры:
                            'q' => '',                                      // - Количество добавляемое в корзину.
                            'c' => 'API Test'                               // - 'comment' - Комментарий к позиции.
                        ]
                    ]
                ],
                'methodsOptions' => [
                    'cs' => 'utf8'
                ]
            ],
            /*---------------------------------------------------OVER--------------------------------------------------*/
            'Over' => [
                'provider_name' => 'Over',
                'apiType' => 'Model',
                'authParams' => ['login', 'password'],
                'model' => 'PartOverSearch',
                'isParamsAsArray' => false,
                'methods' => [
                    'findDetails' => [
                        'name' => 'search',
                        'uri_index' => 0,
                        'params' => [
                        ],
                    ],
                    'toBasket' => [
                    ]
                ],
                'methodsOptions' => [
                ]
            ],
            'Kd' => [
                'provider_name' => 'Kd',
                'apiType' => 'Model',
                'authParams' => ['login', 'password'],
                'model' => 'FindDetailsSearch',
                'isParamsAsArray' => false,
                'methods' => [
                    'findDetails' => [
                        'name' => 'search',
                        'uri_index' => 0,
                        'params' => [
                        ],
                    ],
                    'toBasket' => [
                    ]
                ],
                'methodsOptions' => [
                ]
            ]
        ]
    ]
];
