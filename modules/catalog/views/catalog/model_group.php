<?php
use yii\helpers\Url;
use yii\helpers\Html;


/**
 * Created by PhpStorm.
 * User: marat
 * Date: 29.06.15
 * Time: 13:10
 */
//var_dump($model);die;
foreach ($model as $model_name => $value) {
//    var_dump($value);die;
    $column = [
//        'Vодификация' => 'model_code',
        'Период выпуска' => 'prod',
        'Кузов' => 'body_en',
        'Grade' => 'grade_en',
        'ATM,MTM' => 'tm_en',
        'Gear shift type' => 'trans_en',
        $value[0]['f1_name'] => 'f1_en',
        $value[0]['f2_name'] => 'f2_en',
        $value[0]['f3_name'] => 'f3_en',
        $value[0]['f4_name'] => 'f4_en'
//        'Transmission model'=>'tmod_en',
//        'Rear tire'=>'rt_en',
//    'destination'=>'dest_en',
    ];
    break;
}


echo '<table class="table table-bordered">';
$head = '<tr><th>Модификация</th>';
foreach ($column as $title => $name) {
    $head .= '<th>' . $title . '</th>';
}
$head .= '</tr>';
echo $head;
foreach ($model as $model_code => $rows) {
//echo '<p>'.$model_code.'</p>';
    foreach ($rows as $row) {

//        $url = \yii\helpers\Url::to(['album', 'catalog_code' => $row['catalog_code'], 'catalog' => $row['catalog'],]);

        $url = Html::a(Html::encode($row['model_code']), Url::to(['catalog',
            'catalog_code' => $row['catalog_code'],
            'catalog' => $row['catalog'],
            'model_code' => $row['model_code'],
            'compl_code' => $row['compl_code'],
            'model_name' => $row['model_name'],
            'sysopt' => $row['sysopt'],
            'vdate' => (isset($row['vdate'])) ? $row['vdate'] : '',
            'vin' => (isset($row['vin'])) ? $row['vin'] : '',
            'frame' => (isset($row['frame'])) ? $row['frame'] : '',
            'number' => (isset($row['number'])) ? $row['number'] : '',
            'user_id' => (isset($row['user_id'])) ? $row['user_id'] : 0,

        ]));


        $text = '<td>' . $url . '</td>';
        foreach ($column as $name) {
            $text .= '<td>' . $row[$name] . '</td>';
        }
        $text = '<tr>' . Html::a($text, $url) . '</tr>';
        echo $text;
//    echo $text;
    }

}
echo '</table>';