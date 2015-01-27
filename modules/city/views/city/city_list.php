
<?php

use yii\helpers\Html;
use yii\bootstrap\Button;
//var_dump($chars);die;
foreach ($chars as $key=> $c){
   echo '<button onclick="hide_button()">'. $key .'</button>';
}
echo '<div class="city">';
foreach ($data as $city) {
    echo '<button onclick="setCookies(\'city\',\''.$city->attributes['id'].'\')">'.$city->attributes['name'].'</button>';
}
echo '</div>';
