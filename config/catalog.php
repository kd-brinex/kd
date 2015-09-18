<?php

return [

    'label'=>'Товары',
    'options'=>['tag'=>'ul', 'class'=>'nav nav-sidebar'],
    'itemoptions'=>['tag'=>'li'],
    'items' => [
        ['label' => 'ЗАПЧАСТИ',
            'items'=>  [
                ['label'=>'Поиск','url' => '/finddetails',],
                ['label'=>'Запчасти ВАЗ','url' => '/tovars/zapchasti_VAZ',],
            ]
        ],
        ['label' => 'ШИНЫ',
            'items' => [
                ['label' => 'АВТОШИНЫ', 'url' => '/tovars/shina',],
                ['label' => 'ШИНЫ ДЛЯ КВАДРОЦИКЛОВ', 'url' => '/tovars/quadroshina',],
                ['label' => 'ШИНЫ ДЛЯ СЕЛЬХОЗТЕХНИКИ', 'url' => '/tovars/agriculturalshina',],
                ['label' => 'КАМЕРЫ', 'url' => '/tovars/autokameri',],
                ['label' => 'ШИНЫ ДЛЯ СПЕЦТЕХНИКИ', 'url' => '/tovars/industrialshina',],
                ['label' => 'ШИНЫ ГРУЗОВЫЕ', 'url' => '/tovars/gruz',],
                ['label'=>'МОТОШИНЫ','url'=>'/tovars/moto',],
                ['label'=>'ВЕЛОПОКРЫШКИ','url'=>'/tovars/velopokryshki',],
            ]
        ],
        ['label' => 'ДИСКИ',
            'items' => [
                ['label' => 'ДИСКИ', 'url' => '/tovars/disk',],
                ['label' => 'СЕКРЕТКИ И КРЕПЕЖ', 'url' => '/tovars/sekretki',],
                ['label' => 'УСТАНОВОЧНЫЕ КОЛЬЦА', 'url' => '/tovars/ustkolza',],
                ['label' => 'ПРОСТАВКИ', 'url' => '/tovars/vstavka',],
            ]
        ],
        ['label' => 'АККУМУЛЯТОРЫ',
            'url' => '/tovars/akkumulytor',
//            'items'=>  [['label'=>'Диски','url'=>'/tovars/disk',]]
        ],
        ['label' => 'АВТОТОВАРЫ',
            'items' => [
                ['label' => 'ВСЕ ДЛЯ ЗИМЫ', 'url' => '/tovars/zima',],
                ['label' => 'АВТОБАГАЖНИКИ', 'url' => '/tovars/bagachniki',],
                ['label' => 'АВТОЗАЩИТА', 'url' => '/tovars/zashity',],
                ['label' => 'АВТОСИГНАЛИЗАЦИИ', 'url' => '/tovars/autosignalizacii',],
                ['label' => 'ВИДЕОРЕГИСТРАТОРЫ', 'url' => '/tovars/video',],
                ['label' => 'ДВОРНИКИ', 'url' => '/tovars/shetki',],
                ['label' => 'КОВРИКИ', 'url' => '/tovars/kovriki',],
                ['label' => 'КОМПРЕССОРЫ', 'url' => '/tovars/kompressor',],
                ['label' => 'НАБОР АВТОМОБИЛИСТА', 'url' => '/tovars/nabor',],
                ['label' => 'РАДАР-ДЕТЕКТОРЫ', 'url' => '/tovars/radar',],
                ['label' => 'СЕКРЕТКИ И КРЕПЕЖ', 'url' => '/tovars/sekretki',],
                ['label' => 'ПРОСТАВКИ', 'url' => '/tovars/vstavka',],
                ['label' => 'ВЕТРОВИКИ', 'url' => '/tovars/vetroviki',],
                ['label' => 'УСТАНОВОЧНЫЕ КОЛЬЦА', 'url' => '/tovars/ustkolza',],
//                ['label'=>'АВТОИНСТРУМЕНТ','url'=>'/tovars/nabor',],

            ]

        ],
        ['label'=>'МОТОШИНЫ','url'=>'/tovars/moto',],
        ['label' => 'ШИНЫ ДЛЯ КВАДРОЦИКЛОВ', 'url' => '/tovars/quadroshina',],
        ['label'=>'ВЕЛОПОКРЫШКИ','url'=>'/tovars/velopokryshki',],
        ['label' => 'ШИНЫ ДЛЯ СПЕЦТЕХНИКИ', 'url' => '/tovars/industrialshina',],
        ['label' => 'ГРУЗОВЫЕ АВТОШИНЫ', 'url' => '/tovars/gruz',],
        ['label' => 'МАСЛО',
            'items' => [
                ['label' => 'ЛОДОЧНЫЕ МАСЛА', 'url' => '/tovars/boatoil',],
                ['label' => 'МОТОМАСЛА', 'url' => '/tovars/motomotormasla',],
                ['label' => 'АВТОМАСЛА', 'url' => '/tovars/avtomotormasla',],
                ['label' => 'ГРУЗОВЫЕ МАСЛА', 'url' => '/tovars/gruzmotormasla',],
            ],
        ],
        ['label'=>'АВТОКАМЕРЫ','url'=>'/tovars/autokameri',],
    ]

];
/*
 ['label'=>'agriculturalshina','url'=>'/tovars/agriculturalshina',],
 ['label'=>'akkumulytor','url'=>'/tovars/akkumulytor',],
 ['label'=>'akkumulytor-gruz','url'=>'/tovars/akkumulytor-gruz',],
 ['label'=>'akkumulytor-moto','url'=>'/tovars/akkumulytor-moto',],
 ['label'=>'autokameri','url'=>'/tovars/autokameri',],
 ['label'=>'autosignalizacii','url'=>'/tovars/autosignalizacii',],
 ['label'=>'avtomotormasla','url'=>'/tovars/avtomotormasla',],
 ['label'=>'avtotransmissmasla','url'=>'/tovars/avtotransmissmasla',],
 ['label'=>'bagachniki','url'=>'/tovars/bagachniki',],
 ['label'=>'boatoil','url'=>'/tovars/boatoil',],
 ['label'=>'disk','url'=>'/tovars/disk',],
 ['label'=>'gruz','url'=>'/tovars/gruz',],
 ['label'=>'gruzmotormasla','url'=>'/tovars/gruzmotormasla',],
 ['label'=>'gruztransmissmasla','url'=>'/tovars/gruztransmissmasla',],
 ['label'=>'industrialshina','url'=>'/tovars/industrialshina',],
 ['label'=>'karting','url'=>'/tovars/karting',],
 ['label'=>'koleso','url'=>'/tovars/koleso',],
 ['label'=>'kompressor','url'=>'/tovars/kompressor',],
 ['label'=>'kovriki','url'=>'/tovars/kovriki',],
 ['label'=>'moto','url'=>'/tovars/moto',],
 ['label'=>'motokameri','url'=>'/tovars/motokameri',],
 ['label'=>'motomotormasla','url'=>'/tovars/motomotormasla',],
 ['label'=>'mototransmissmasla','url'=>'/tovars/mototransmissmasla',],
 ['label'=>'motoximiy','url'=>'/tovars/motoximiy',],
 ['label'=>'nabor','url'=>'/tovars/nabor',],
 ['label'=>'nabor-bolt','url'=>'/tovars/nabor-bolt',],
 ['label'=>'odeyla','url'=>'/tovars/odeyla',],
 ['label'=>'quadroshina','url'=>'/tovars/quadroshina',],
 ['label'=>'radar','url'=>'/tovars/radar',],
 ['label'=>'rasx','url'=>'/tovars/rasx',],
 ['label'=>'sekretki','url'=>'/tovars/sekretki',],
 ['label'=>'shetki','url'=>'/tovars/shetki',],
 ['label'=>'shina','url'=>'/tovars/shina',],
 ['label'=>'ustkolza','url'=>'/tovars/ustkolza',],
 ['label'=>'velopokryshki','url'=>'/tovars/velopokryshki',],
 ['label'=>'vetroviki','url'=>'/tovars/vetroviki',],
 ['label'=>'video','url'=>'/tovars/video',],
 ['label'=>'vstavka','url'=>'/tovars/vstavka',],
 ['label'=>'zashity','url'=>'/tovars/zashity',],
 ['label'=>'zima','url'=>'/tovars/zima',],

*/