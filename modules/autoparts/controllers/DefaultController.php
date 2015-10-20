<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 13.08.15
 * @time: 11:59
 */

namespace app\modules\autoparts\controllers;


use app\helpers\BrxArrayHelper;

class DefaultController extends ProviderController
{


    public function actionIndex(){

        $config_1 = [
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
                ]
            ];

        $out = [
            0 =>  'article',
            1 =>  'name',
            2 =>  'brand:name',
            3 =>  ':price',
            4 =>  ':quantity',
            5 =>  ':average_period',
            6 =>  ':assured_period',
            10 => ':reliability',
            11 => ':multiplication_factor',
        ];

        $moskvorechie = [
              'result' =>[
                  0 => [
                      'nr' => 'C40002',
                      'brand' =>  'Mann-filter',
                      'name' => 'Фильтр воздушный IVECO DAILY',
                      'stock' => '3',
                      'delivery' =>  'на складе',
                      'minq' => '1',
                      'upd' =>  '16.10.15 11:31',
                      'price' => '1806.02',
                      'currency' =>  'руб.'
                    ],
                  1 => [
                      'nr' => 'C40002',
                      'brand' =>  'Mann-filter',
                      'name' => 'Фильтр воздушный IVECO DAILY',
                      'stock' => '58',
                      'delivery' => '2 дня',
                      'minq' =>  '1',
                      'upd' =>  '16.10.15 11:04',
                      'price' =>  '1805.90',
                      'currency' =>  'руб.'
                ]
            ]
        ];

        $emex = [
                  'FindDetailAdv3Result' => [
                       'Details' => [
                           'SoapDetailItem' => [
                              0 => [
                                   'GroupId' =>  -370,
                                   'PriceGroup' => 'Original',
                                   'MakeLogo' => 'HK',
                                   'MakeName' => 'HDK',
                                   'DetailNum' => 'HY012',
                                   'DetailNameRus' => 'ШРУС ВНЕШНИЙ',
                                   'PriceLogo' => 'MSAS',
                                   'DestinationLogo' => 'AFL',
                                   'PriceCountry' => 'Москва',
                                   'LotQuantity' =>  1,
                                   'Quantity' => 19,
                                   'DDPercent' => '100.0',
                                   'ADDays' =>  0,
                                   'DeliverTimeGuaranteed' => 5,
                                   'ResultPrice' => '1930.7900'
                              ],
                              1 => [
                                   'GroupId' => -370,
                                   'PriceGroup' => 'Original',
                                   'MakeLogo' => 'HK',
                                   'MakeName' =>  'HDK',
                                   'DetailNum' => 'HY012',
                                   'DetailNameRus' => 'ШРУС ВНЕШНИЙ',
                                   'PriceLogo' => 'NNAS',
                                   'DestinationLogo' =>  'AFL',
                                   'PriceCountry' =>  'Россия',
                                   'LotQuantity' => 1,
                                   'Quantity' =>  6,
                                   'DDPercent' =>  '89.0',
                                   'ADDays' => 3,
                                   'DeliverTimeGuaranteed' =>  11,
                                   'ResultPrice' => '2133.4400',
                                ],
                              2 =>[
                                   'GroupId' =>  -370,
                                   'PriceGroup' =>  'Original',
                                   'MakeLogo' => 'HK',
                                   'MakeName' =>  'HDK',
                                   'DetailNum' =>  'HY012',
                                   'DetailNameRus' =>  'ШРУС ВНЕШНИЙ',
                                   'PriceLogo' => 'TRAS',
                                   'DestinationLogo' => 'AFL',
                                   'PriceCountry' => 'Москва',
                                   'LotQuantity' => 1,
                                   'Quantity' =>  50,
                                   'DDPercent' =>  '96.0',
                                   'ADDays' => 2,
                                   'DeliverTimeGuaranteed' =>  4,
                                   'ResultPrice' =>  '1970.6200',
                                ]
                            ]
                        ]
                  ]
        ];

        $ixora = [ 'FindResult' => [
            'DetailInfo' => [
                    0 => [
                         'number' => 'HY012',
                         'maker' => 'HDK',
                         'name' => 'ШРУС ВНЕШНИЙ',
                         'quantity' => '>4',
                         'lotquantity' => 2,
                         'price' =>  1801.47,
                         'pricedestination' => 0,
                         'days' =>  0,
                         'dayswarranty' =>  0,
                         'region' => 'АВТО-ИКСОРА СКЛАД',
                         'estimation' => '5 5 5',
                         'orderreference' => '0-91001-1-64-3-43385284-2-0',
                         'group' => 'Original',
                         'date' => '2015-10-16T00:00:00',
                    ],
                    1 => [
                         'number' => 'HY012',
                         'maker' => 'HDK',
                         'name' => 'ШРУС ВНЕШНИЙ',
                         'quantity' => '>4',
                         'lotquantity' => 1,
                         'price' => 1857.19,
                         'pricedestination' => 0,
                         'days' =>  0,
                         'dayswarranty' => 0,
                         'region' =>  'АВТО-ИКСОРА СКЛАД',
                         'estimation' => '5 5 5',
                         'orderreference' => '0-91001-1-64-3-43385284-1-0',
                         'group' => 'Original',
                         'date' => '2015-10-16T00:00:00',
                    ],
                    2 => [
                         'number' => 'HY012',
                         'maker' => 'HDK',
                         'name' => 'ШРУС ВНЕШНИЙ',
                         'quantity' => '50',
                         'lotquantity' => 1,
                         'price' => 1870.62,
                         'pricedestination' =>  0,
                         'days' =>  2,
                         'dayswarranty' =>  5,
                         'region' => 'МОСКВА СКЛАД - 322',
                         'estimation' => '5 - 5',
                         'orderreference' => '0-91001-395-538-3-43385284-1-0',
                         'group' => 'Original',
                         'date' => '2015-10-16T08:49:00',
                    ]
                ]
               ]
            ];
        $berg = [
            'o' => [
                'resources' => [
                    0 => [
                        'id' => 40386,
                        'name' => 'Колодки тормозные дисковые GDB1044',
                        'article' => 'GDB1044',
                        'brand' => [
                            'id' => 3,
                            'name' => 'TRW',
                        ],
                        'offers' => [
                            0 => [
                                'price' => 1573.82,
                                'quantity' => 10,
                                'reliability' => 101,
                                'multiplication_factor' => 10,
                                'average_period' => 50,
                                'assured_period' => 15,
                                'warehouse' => [
                                    'id' => 46,
                                    'name' => 'BERG',
                                    'type' => 1
                                ]
                            ],
                            1 => [
                                'price' => 1572.82,
                                'quantity' => 7,
                                'reliability' => 90,
                                'multiplication_factor' => 3,
                                'average_period' => 4,
                                'assured_period' => 8,
                                'warehouse' => [
                                    'id' => 27820,
                                    'name' => 'BERG MSK',
                                    'type' => 2
                                  ]
                            ],
                            2 => [
                                'price' => 1574.82,
                                'quantity' => 6,
                                'reliability' => 150,
                                'multiplication_factor' => 1,
                                'average_period' => 1,
                                'assured_period' => 1,
                                'warehouse' => [
                                    'id' => 27820,
                                    'name' => 'BERG MSK',
                                    'type' => 2
                                ]
                            ],
                            3 => [
                                'price' => 1600.82,
                                'quantity' => 6,
                                'reliability' => 100,
                                'multiplication_factor' => 1,
                                'average_period' => 1,
                                'assured_period' => 1,
                                'warehouse' => [
                                    'id' => 27820,
                                    'name' => 'BERG MSK',
                                    'type' => 2
                                ]
                            ],
                            4 => [
                                'price' => 1300.82,
                                'quantity' => 6,
                                'reliability' => 100,
                                'multiplication_factor' => 1,
                                'average_period' => 1,
                                'assured_period' => 1,
                                'warehouse' => [
                                    'id' => 27820,
                                    'name' => 'BERG MSK',
                                    'type' => 2
                                ]
                            ]
                        ],
                        'source_idx' => '0',
                    ],
                    1 => [
                        'id' => 40558,
                        'name' => 'Колодки тормозные дисковые GDB1268',
                        'article' => 'GDB1268',
                        'brand' => [
                            'id' => 3,
                            'name' => 'TRW'
                        ],
                        'offers' =>[
                            0 => [
                                'price' => 2103.03,
                                'quantity' => 0,
                                'reliability' => 100,
                                'multiplication_factor' => 1,
                                'average_period' => 0,
                                'assured_period' => 0,
                                'warehouse' => [
                                    'id' => 46,
                                    'name' => 'BERG',
                                    'type' => 1
                                ],
                            ],
                            1 => [
                                'price' => 2103.03,
                                'quantity' => 77,
                                'reliability' => 100,
                                'multiplication_factor' => 0,
                                'average_period' => 0,
                                'assured_period' => 0,
                                'warehouse' => [
                                    'id' => 46,
                                    'name' => 'BERG',
                                    'type' => 1
                                ]

                            ]
                        ],
                        'source_idx' => '0'
                    ],
                    2 => [
                        'id' => 40558,
                        'name' => 'Колодки тормозные дисковые GDB1266',
                        'article' => 'GDB1266',
                        'brand' => [
                            'id' => 3,
                            'name' => 'TRW'
                        ],
                        'offers' =>[
                            0 => [
                                'price' => 2009.03,
                                'quantity' => 0,
                                'reliability' => 100,
                                'multiplication_factor' => 1,
                                'average_period' => 0,
                                'assured_period' => 0,
                                'warehouse' => [
                                    'id' => 46,
                                    'name' => 'BERG',
                                    'type' => 1
                                ]
                            ],
                            1 => [
                                'price' => 2907.03,
                                'quantity' => 1,
                                'reliability' => 100,
                                'multiplication_factor' => 1,
                                'average_period' => 1,
                                'assured_period' => 1,
                                'warehouse' => [
                                    'id' => 27820,
                                    'name' => 'BERG MSK',
                                    'type' => 2
                                ]
                            ],
                            2 => [
                                'price' => 22307.03,
                                'quantity' => 1,
                                'reliability' => 100,
                                'multiplication_factor' => 1,
                                'average_period' => 1,
                                'assured_period' => 1,
                                'warehouse' => [
                                    'id' => 27820,
                                    'name' => 'BERG MSK',
                                    'type' => 2
                                ]
                            ]
                        ],
                        'source_idx' => '0'
                    ]
                ]
            ]
        ];

        $partkom = [
                0 => [
                    'number' => 'CO3600',
                    'maker' => 'LYNX',
                    'makerId' => '322',
                    'description' => 'ШРУС',
                    'providerId' => 3899,
                    'providerDescription' => 'МСК склад',
                    'minQuantity' => 2,
                    'storehouse' => false,
                    'minDeliveryDays' => 0,
                    'averageDeliveryDays' =>  0,
                    'maxDeliveryDays' => 1,
                    'warrantedDeliveryDays' => 1,
                    'lastUpdateDate' => '2015-10-15 19:20:49',
                    'statProvider' =>  85,
                    'price' =>  1619,
                    'quantity' =>  '3',
                    'detailGroup' => 'ReplacementNonOriginal',
                    'group' => '322',
                    'lastOrderDate' =>  '',
                    'statSuccessCount' =>  0,
                    'statRefusalCount' =>  0,
                    'statTotalOrderCount' =>  0,
                ],
                1 => [
                    'number' => 'CO3600',
                    'maker' => 'LYNX',
                    'makerId' => '322',
                    'description' =>  'комплект ШРУСа наружный',
                    'providerId' => 4647,
                    'providerDescription' =>  'МСК склад',
                    'minQuantity' =>  1,
                    'storehouse' =>  false,
                    'minDeliveryDays' =>  3,
                    'averageDeliveryDays' =>  3,
                    'maxDeliveryDays' =>  4,
                    'warrantedDeliveryDays' =>  4,
                    'lastUpdateDate' => '2015-10-16 08:27:40',
                    'statProvider' =>  94,
                    'price' =>  1642,
                    'quantity' =>  '1',
                    'detailGroup' =>  'ReplacementNonOriginal',
                    'group' =>  '322',
                    'lastOrderDate' =>  '',
                    'statSuccessCount' =>  0,
                    'statRefusalCount' =>  0,
                    'statTotalOrderCount' =>  0,
                ],
                2 =>[
                    'number' =>  'CO3600',
                    'maker' =>  'LYNX',
                    'makerId' => '322',
                    'description' => 'ШРУС наружный | перед прав/лев |',
                    'providerId' => 405,
                    'providerDescription' =>  'МСК склад',
                    'minQuantity' => 1,
                    'storehouse' =>  false,
                    'minDeliveryDays' =>  1,
                    'averageDeliveryDays' =>  1,
                    'maxDeliveryDays' => 2,
                    'warrantedDeliveryDays' =>  2,
                    'lastUpdateDate' =>  '2015-10-16 09:42:32',
                    'statProvider' => 90,
                    'price' =>  1659,
                    'quantity' => '100',
                    'detailGroup' => 'ReplacementNonOriginal',
                    'group' => '322',
                    'lastOrderDate' => '',
                    'statSuccessCount' =>  0,
                    'statRefusalCount' =>  0,
                    'statTotalOrderCount' =>  0
            ]
        ];

        // ищем значение по ключу
//        function array_search_values_recursive($key, &$haystack, $removeItem = false){
//            $haystack = is_object($haystack) ? (array)$haystack : $haystack;
//            static $result = [];
//            static $index = 0;
//
//            foreach ($haystack as $k => &$v) {
//                $v = is_object($v) ? (array)$v : $v;
//                if(is_array($v)){
//                    if(array_key_exists($key, $v)){
////                        var_dump($result, $v[$key]);
//                        $result[$k][$index] = $v[$key];
//                        if($removeItem) unset($v[$key]);
//                    } else
//                        array_search_values_recursive($key, $v, $removeItem);
//                }
//            }
//
//            return $result;
//        }

        /**
         * Функция ищет массив с запчастями и возвращает только его, без лишних артефактов
         * @param array $array массив - ответ от поставщика
         * @return array результирующий массив
         */
        function find_details_array($array = [])
        {
            if(count($array) > 1) return $array;

            foreach($array as $innerArray){
                return is_array($innerArray) && count($innerArray) > 1 ? $innerArray : find_details_array($innerArray);
            }
        }


        /**
         * Функция рекурсивный помощник для функции rooting_array_values_recursive()
         * @param $arr
         * @param $result
         * @param $rootKey
         * @return mixed
         */
        function recursive($arr, &$result, $rootKey)
        {
            static $ak;
            foreach($arr as $k => $v){
                if(is_array($v)) {
                    $ak .= $k.':';
                    recursive($v, $result, $rootKey);

                } else {
                    $result[$rootKey][$ak . $k] = $v;
                }
            }
            $ak = '';
            return $arr;
        }

        /**
         * Функция делает многоменрный массив линейным. К ключам вложенных массивов присоединяются ключи родительского
         * массива во избежании перезаписи дублирующихся имен.
         * @param array $array массив для обработки
         * @return array обработанный массив
         */
        function rooting_array_values_recursive($array)
        {
            $result = [];
            foreach($array as $key => $value){
                if(!empty($value))
                    recursive($value, $result, $key);
            }

            return $result;
        }

        /**
         * Функция ищет в массиве деталий вложенные повторения в подмассивах и помещает их в корень как уникальные
         * @param array $array массив запчастей
         * @param array $template массив шаблон конфигурации
         */
        function multiplyIfNeed(&$array, $template)
        {
            $nextIndex = count($array);
            $firstIndex = $nextIndex;
            foreach($template as $item){
                $nextIndex = $firstIndex;
                if($item{0} === ':'){
                    foreach($array as $partIndex => &$part){
                        if(is_array($part)){
                            foreach($part as $attributeIndex => &$attribute){
                                if($needle = stripos($attributeIndex, $item)){
                                    if($attributeIndex{$needle - 1} == 0){
                                        unset($part[$attributeIndex]);
                                        $part[substr($item, 1)] = $attribute;
                                        $array[$nextIndex]['parentArrayIndex'] = $partIndex;
                                    } else {
                                        $array[$nextIndex][substr($item, 1)] = $attribute;
                                        $array[$nextIndex]['parentArrayIndex'] = $partIndex;
                                        unset($part[$attributeIndex]);
                                        $nextIndex++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        /**
         * Функция заполняет все массивы недостающими атрибутами
         * @param array $array
         */
        function fillUp(&$array)
        {
            foreach($array as $key => &$value){
                if(is_array($value) && array_key_exists('parentArrayIndex', $value)){
                    foreach($array[$value['parentArrayIndex']] as $k => $v){
                        if(!isset($value[$k]))
                            $value[$k] = $v;
                    }
                    unset($value['parentArrayIndex']);
                }
            }
        }

        /**
         * Функция удаляет созданные для обработки метки, ключи, значения
         * @param array $array
         */
        function removeArtifacts(&$array)
        {
            foreach ($array as $key => &$value){
                if(is_array($value)){
                    foreach($value as $k => $v){
                        if(preg_match('/(\d+):/', $k)){
                            unset($value[$k]);
                        }
                    }
                }
            }
        }



//        var_dump($data);




//        function toTemplate($data, $conf, $fromT){
//            $result = [];
//            $index = 0;
//            foreach($conf as $index => $param){
//                foreach($data as $k => $v){
//                    if(!empty($v)){
//                        if(is_array($v)){
//                            if(isset($fromT[$index])){
//                                if($fromT[$index]{0} === ':'){
//
//                                }
//
//                                if(isset($v[$fromT[$index]])){
//
//                                    $result[$k][$param] = $v[$fromT[$index]];
//                                }
//                            }
//                            else $result[$k][$param] = '';
//                        }
//                    }
//                }
//                $index++;
//            }
//
//            return $result;
//        }

//        var_dump(toTemplate($data, $config_1, $out));
        function dataToTemplate(&$data, $provider = null, $beforeParseData = [], $afterParseData = [], /*пдо*/ $conf, /*пдо*/ $fromT){
//            var_dump($data);
            $config = $conf;// \Yii::$app->getModule('autoparts')->params;

            $fromTemplate = $fromT;//$config['providersFieldsParams'][$provider->provider_name]['method'][$provider->method]['params']['out'];
            $data = is_object($data) ? (array)$data : $data;
            $items = [];
            // перебираем все атрибуты шаблона под который идет подгонка данных
            foreach($config['paramsTemplate'] as $key => $value){
                // ищем параметр шаблона в возвращенных дынных
                if(isset($fromTemplate[$key])){
                    if(isset($data[0]) && $data[0] instanceof ActiveRecord){
                        foreach($data as $k => $model){
                            $values[$k] = $model->$fromTemplate[$key];
                        }
                    } else {
                        $root_array = find_details_array($data);
                        $data = rooting_array_values_recursive($root_array);
                        multiplyIfNeed($data, $fromTemplate);
                        removeArtifacts($data);
                        fillUp($data);
                    }
                    for($i = 0; $i <= count($data)-1; $i++){
                        $index = $fromTemplate[$key]{0} === ':' ? substr($fromTemplate[$key], 1) : $fromTemplate[$key];
                        $items[$i][$value] = $data[$i][$index];
                    }
                }
            }
//        var_dump($items);die;
            foreach($items as $item){
                foreach($config['paramsTemplate'] as $key => $value){
                    if(!array_key_exists($value, $item))
                        $item[$value] = '';
                }
            }

            for($i = 0; $i <= count($items)-1; $i++){
                foreach($config['paramsTemplate'] as $key => $value){
                    if(!array_key_exists($value, $items[$i]))
                        $items[$i][$value] = '';
                }
            }

            if(!empty($beforeParseData))
                $items = $this->beforeParse($beforeParseData, $items);


            if(!empty($afterParseData))
                $items = $this->afterParse($afterParseData, $items);

            return $items;
        }

        var_dump(dataToTemplate($berg, null, [], [], $config_1, $out));




































        function array_search_values_recursive($needle, $array)
        {
            static $result = [];
            static $k = '';$k = key($array);
            var_dump($k);
            foreach($array as $key => $value)
            {
                if($key === $needle){
                    $result[$key][] = $value;
                } else if(is_array($value)){
                    array_search_values_recursive($needle, $value);
                }
            }

            return $result;
        }

//        $res = array_search_values_recursive('price', find_details_array($arr));
//        $result2 = array_search_values_recursive('name', $arr);
//        $result3= array_search_values_recursive('article', $arr);
//        var_dump($res);
//        var_dump($result2);
//        var_dump($result3);

        function parser($data, $template, /*параметр для отладки*/$config_1){
            $result = [];
            foreach($template as $idx => $template_item){
                $attribute = $config_1[$idx];
                $result = array_search_values_recursive($attribute, $data);
            }

            return $result;
        }

//        var_dump(parser(find_details_array($arr), $out, $config_1));

        // ищем ключи по их значению
        function array_search_recursive($needle, $haystack, $strict = false, $path = []){
            $haystack = is_object($haystack) ? (array)$haystack : $haystack;
            foreach($haystack as $key => $val){
                $val = is_object($val) ? (array)$val : $val;
                if(is_array($val) && $subPath = array_search_recursive($needle, $val, $strict, $path)) {
                    $path = array_merge($path, array($key), $subPath);
                    return $path;
                } else if((!$strict && $val == $needle) || ($strict && $val === $needle)) {
                    $path[] = $key;
                    return $path;
                }
            }
            return false;
        }
//        var_dump(array_search_recursive('BERG', $arr, true));





    /*EMEX*/
//       var_dump($this->provider('Emex')->findDetails(['code' => '32-D88-F']));

//        Ответ
        //public 'GroupId' => int -370
        //public 'PriceGroup' => string 'Original' (length=8)
        //public 'MakeLogo' => string 'HK' (length=2)
        //public 'MakeName' => string 'HDK' (length=3)
        //public 'DetailNum' => string 'HY012' (length=5)
        //public 'DetailNameRus' => string 'ШРУС ВНЕШНИЙ' (length=23)
        //public 'PriceLogo' => string 'MSAS' (length=4)
        //public 'DestinationLogo' => string 'AFL' (length=3)
        //public 'PriceCountry' => string 'Москва' (length=12)
        //public 'LotQuantity' => int 1
        //public 'Quantity' => int 30
        //public 'DDPercent' => string '100.0' (length=5)
        //public 'ADDays' => int 1
        //public 'DeliverTimeGuaranteed' => int 5
        //public 'ResultPrice' => string '1965.6100' (length=9)*/

//        var_dump($this->provider('Emex')->toBasket(['ePrices' => ['EPrice' => [
//                'Num' => '2',                     // номер по порядку (произвольный номер)
//                'MLogo' => 'HK',                   // лого изготовителя (MakeLogo)
//                'DNum' => 'HY012',                    // уникальный номер детали (DetailNum)
//                'Name' => 'ШРУС ВНЕШНИЙ',                    // наименование детали русское (DetailNameRus все большие)
//                'Quan' => '2',                    // количество деталей (max Quantity)
//                'Price' => '1965.6100',                   // цена рублевая (пример: 0000.0000)
//                'PLogo' => 'MSAS',                   // лого прайса детали (PriceLogo)
//                'DLogo' => 'AFL',                   // лого доставки (DestinationLogo)
//                'DeliveryRegionType' => 'PRI',      // тип доставки по региону. Если этот элемент не задан, то сохраняется значение PRI
//                'Ref' => 'API тест',                     // поле reference (это и есть комментарий)
//                'Com' => 'API тест',                     // поле комментария. Также в этом поле можно задавать разрешенные признаки детали. Вводить их в любом порядке, через пробел. Например BRAND ONLY
//                'Notc' => '',                    // признак безналичной оплаты
//                'Error' => ''                    // поле ошибки, с описанием причины ошибки.
//        ]]]));
        //Ответ
        //public 'Num' => string '2' (length=1)
        //public 'GlobalId' => string '81072955' (length=8)
        //public 'Comment' => string '' (length=0)

        /*Iksora*/
//        var_dump($this->provider('Iksora')->findDetails(['code' => '32-D88-F']));
//        Ответ
//        public 'FindResult' =>
//            object(stdClass)[174]
//              public 'DetailInfo' =>
//                array (size=39)
//                  0 =>
//                    object(stdClass)[175]
//                      public 'number' => string 'HY012' (length=5)
//                      public 'maker' => string 'HDK' (length=3)
//                      public 'name' => string 'ШРУС ВНЕШНИЙ' (length=23)
//                      public 'quantity' => string '>4' (length=2)
//                      public 'lotquantity' => int 2
//                      public 'price' => float 1827.42
//                      public 'pricedestination' => float 0
//                      public 'days' => int 0
//                      public 'dayswarranty' => int 0
//                      public 'region' => string 'АВТО-ИКСОРА СКЛАД' (length=32)
//                      public 'estimation' => string '5 5 5' (length=5)
//                      public 'orderreference' => string '0-91001-1-64-3-43385284-2-0' (length=27)
//                      public 'group' => string 'Original' (length=8)
//                      public 'date' => string '2015-08-20T00:00:00' (length=19)
//
        /*Partkom*/
//        var_dump($this->provider('Partkom')->findDetails(['code'=> '32-D88-F'])); // основная проблема поиска в трех последних параметрах
//        Ответ
//        0 =>
//          array (size=22)
//              'number' => string 'HY012' (length=5)
//              'maker' => string 'HDK' (length=3)
//              'makerId' => string '223' (length=3)
//              'description' => string 'ШРУС внешний HY-12' (length=29)
//              'providerId' => int 205
//              'providerDescription' => string 'МСК склад' (length=17)
//              'minQuantity' => int 10
//              'storehouse' => boolean false
//              'minDeliveryDays' => int 1
//              'averageDeliveryDays' => int 1
//              'maxDeliveryDays' => int 1
//              'warrantedDeliveryDays' => int 1
//              'lastUpdateDate' => string '2015-08-20 18:58:50' (length=19)
//              'statProvider' => int 86
//              'price' => float 2093
//              'quantity' => string '1' (length=1)
//              'detailGroup' => string 'Original' (length=8)
//              'group' => string '223' (length=3)
//              'lastOrderDate' => string '' (length=0)
//              'statSuccessCount' => int 0
//              'statRefusalCount' => int 0
//              'statTotalOrderCount' => int 0

//        var_dump($this->provider('Partkom')->toBasket(['OrderItem' => [
//                (object)[
//                      'detailNum' => '90915YZZE1',
//                      'makerId' => 920,
//                      'description' => 'ШРУС внешний HY-12',
//                      'price' => 247,
//                      'providerId' => 5,
//                      'quantity' => 1,
//                      'comment' => 'API тест',
//                      'errorMessage' => ''
//                ]
//          ]
//        ]
//        ));
//        Ответ пустой масив

//        MOSKVORECHIE
//        var_dump($this->provider('Moskvorechie')->findDetails(['code' => '32-D88-F'])); //32-D88-F

//        Ответ
//        string '{"result":
//                      [
//                          {
//                              "nr":"334614",
//                              "brand":"Kayaba",
//                              "name":"Амортизатор BMW 3 E46 -05 пер.прав.газ.SPORT",
//                              "stock":"1",
//                              "delivery":"2 дня",
//                              "minq":"1",
//                              "upd":"25.08.15 08:02",
//                              "price":"4674.30",
//                              "currency":"руб.",
//                              "gid":"1001232170"
//                          }
//                      ]
//                  }'
//
//        var_dump($this->provider('Moskvorechie')->toBasket(['gid' => '1001232170']));
//        Ответ
//        {"result":
//                  {
//                      "status":"0",
//                      "msg":""
//                  }
//        }'
//
//        BERG
//        $items = [
//                    'items' => [
//                        [
//                            'resource_article' => 'HY012',
//                            'brand_id' => 672,
//                            'brand_name' => 'HDK'
//                        ]
//                    ]
//                 ];
//        var_dump($this->provider('Berg')->findDetails(['code' => '32-D88-F']));//32-D88-F
//        Ответ
//        {"resources":
//              [
//                  {
//                      "id":40386,
//                      "name":"\u041a\u043e\u043b\u043e\u0434\u043a\u0438 \u0442\u043e\u0440\u043c\u043e\u0437\u043d\u044b\u0435 \u0434\u0438\u0441\u043a\u043e\u0432\u044b\u0435 GDB1044",
//                      "article":"GDB1044",
//                      "brand":{
//                                  "id":3,
//                                  "name":"TRW"
//                              },
//                      "offers":
//                              [
//                                  {
//                                      "price":1799.53,
//                                      "quantity":0,
//                                      "reliability":100,
//                                      "multiplication_factor":1,
//                                      "average_period":0,
//                                      "assured_period":0,
//                                      "warehouse":
//                                                  {
//                                                      "id":46,
//                                                      "name":"BERG",
//                                                      "type":1
//                                                  }
//                                  }
//                              ],
//                      "source_idx":"0"
//                  }
//              ]
//          }'

//        $order = [
//            'order'	=> [
//                'items' => [
//                    1 => [
//                        'resource_id' => '40386',
//                        'warehouse_id' => '46',
//                        'quantity' => 1,
//                        'comment' => 'API Test'
//                    ]
//                ]
//            ],
//        ];
//        var_dump($this->provider('Berg')->toBasket($order));

//object(linslin\yii2\curl\Curl)[172]
//        public 'response' =>
//           array (size=2)
//               'warnings' =>
//                 array (size=2)
//                   0 =>
//                     array (size=3)
//                       'code' => string 'ERR_ORDER_ITEMS_MULTIPLICATION_FACTOR' (length=37)
//                       'text' => string 'Quantity must be a multiple of the minimum normal shipping' (length=58)
//                       'data' =>
//                         array (size=4)
//                           'resource_id' => int 703
//                           'warehouse_id' => int 46
//                           'quantity' => int 1
//                           'comment' => string 'произвольный комментарий 1' (length=49)
//                   1 =>
//                     array (size=3)
//                       'code' => string 'ERR_ORDER_ITEMS_QUANTITY_EXCEED_AVAILABLE' (length=41)
//                       'text' => string 'Requested item quantity exceed available on warehouse' (length=53)
//                       'data' =>
//                         array (size=4)
//                           'resource_id' => int 50716
//                           'warehouse_id' => int 46
//                           'quantity' => int 1
//                           'comment' => string 'произвольный комментарий 2' (length=49)
//               'order' =>
//                 array (size=9)
//                   'id' => int 7252443
//                   'dispatch_type' => int 3
//                   'dispatch_at' => string '2015-09-11T23:00:00+0300' (length=24)
//                   'dispatch_time' => int 1
//                   'person' => string 'Женя Лукашин' (length=23)
//                   'shipment_address' => string 'Москва, 3-я улица Строителей, дом 25, квартира 12' (length=82)
//                   'comment' => string 'запчасти для Ипполита' (length=40)
//                   'is_test' => boolean true
//                   'items' =>
//                     array (size=1)
//                       0 =>
//                         array (size=9)
//                           'sequence' => int 1
//                           'resource_id' => int 50716
//                           'warehouse_id' => int 6
//                           'quantity' => int 1
//                           'price' => float 1596.39
//                           'average_time' => string '2015-08-28T10:33:51+0300' (length=24)
//                           'assured_time' => string '2015-08-31T10:33:51+0300' (length=24)
//                           'state_id' => int 296
//                           'comment' => string 'произвольный комментарий 3' (length=49)
    }
}