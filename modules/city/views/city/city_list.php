
<?php

use yii\helpers\Html;
use yii\bootstrap\Button;

echo '<div id="city_list_block">';
foreach ($chars as $key=> $c){
   echo '<button onclick="'.$c['onclick'].'">'. $key .'</button>';
}

echo '<div class="city">';
foreach ($data as $city) {
    echo '<button class="city_button"  char="'.mb_substr($city['name'],0,1,'utf-8').'" onclick="setCookies(\'city\',\''.$city['id'].'\')">'.$city['name'].'</button>';
}
echo '</div>';
echo '</div>"';
