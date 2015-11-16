<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 12.11.15
 * Time: 16:42
 */
use dosamigos\chartjs\ChartJs;
?>
<button onclick="function(){}">+</button>
<?= ChartJs::widget([
    'type' => 'Line',
    'clientOptions'=>[
        'pointDotRadius' => 2,

    ],
    'options' => [
        'height' => 800,
        'width' => 800,
    ],
    'data' => [
        'labels' => $data['labels'],
        'datasets' => [
            [
                'fillColor' => "rgba(220,220,220,0.5)",
                'strokeColor' => "rgba(220,220,220,1)",
                'pointColor' => "rgba(220,220,220,1)",
                'pointStrokeColor' => "#fff",
                'data' => $data['data'],
            ],
//            [
//                'fillColor' => "rgba(151,187,205,0.5)",
//                'strokeColor' => "rgba(151,187,205,1)",
//                'pointColor' => "rgba(151,187,205,1)",
//                'pointStrokeColor' => "#fff",
//                'data' => [28, 48, 40, 19, 96, 27, 100]
//            ]
        ]
    ]
]);?>
