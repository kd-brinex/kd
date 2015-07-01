<?php
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.07.15
 * Time: 10:53
 */
//var_dump($model);die;
$column=[
//    'Артикул'=>'part_code',
    'Номер'=>'pnc',
    'Дата производства'=>'prod',
//    'end_date'=>'end_date',
//    'field_type'=>'field_type',
//    'add_desc'=>'add_desc',

//    'siyopt2'=>'siyopt2',
    'Название'=>'desc_en',
//    'ipic_code'=>'ipic_code',
];
$head = '<tr><th>Артикул</th>';
foreach ($column as $title => $name) {
    $head .= '<th>' . $title . '</th>';
}
$head .= '</tr>';
echo '<table class="table table-bordered">';
echo $head;
//var_dump($model);die;
foreach ($model as $m){
$row='<tr><td>'.Html::a($m['part_code'],Url::to(['/finddetails','article'=>$m['part_code']]),['target'=>'blank']).'</td>';
    foreach ($column as $name) {
        $row .= '<td>' . $m[$name] . '</td>';
    }
$row.='</tr>';
    echo $row;
}

echo '</table>';