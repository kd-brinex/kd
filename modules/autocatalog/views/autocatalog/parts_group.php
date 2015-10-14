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
//    'Артикул'=>'number',
    'Номер'=>'pnc',
//    'Дата производства'=>'production',
//    'end_date'=>'end_date',
//    'field_type'=>'field_type',
//    'add_desc'=>'add_desc',

//    'siyopt2'=>'siyopt2',
//    'x1'=>'x1',
//    'y1'=>'y1',
    'Название'=>'name',
    'Описание'=>'description',
//    'kod'=>'compatibility',
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
    $article=$m['number'];
//    if($m['number_type']==4 and count($model)>1){var_dump($model);die;}
    $row='<tr><td>'.Html::a($article,'/autocatalog/autocatalog/details?article='.$article,['target'=>'_blank']).'</td>';
    foreach ($column as $name) {
        $row .= '<td>' . $m[$name] . '</td>';
    }
    $row.='</tr>';
    echo $row;
}

echo '</table>';