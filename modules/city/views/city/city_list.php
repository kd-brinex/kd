
<?php

use yii\helpers\Html;
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use app\modules\city\cityAsset;
cityAsset::register($this);


echo '<div id="city_list_block">';
//foreach ($chars as $key=> $c){
//   echo '<a href="#" onclick="'.$c['onclick'].'">'. $key .'</a> ';
//}

echo '<div class="city">';

?>
<div class="row">
    <div class="col-lg-3 stories">
        <p>Шинные центры:</p>
        <ul>
            <?php
                foreach ($data['stories'] as $city)
                {
                    echo '<li><a href="#"   char="'.mb_substr($city['name'],0,1,'utf-8').'" onclick="setCookies(\'city\',\''.$city['id'].'\',\'cl=true\')">'.$city['name'].'</a></li>';
                }
            ?>
        </ul>
    </div>
    <div class="col-lg-3 delivery">
        <p class="delivery_title">Доставка в другие города:</p>

            <?php
            $count = 1;
            foreach ($data['regions'] as $region)
            {
                if ($count==40)
                {
                    echo '</div> <div class="col-lg-3 delivery">';
                    $count=1;
                }
                echo '<p class="delivery_name"><a href="#" onclick="setCookies(\'region\',\''.$region['id'].'\');load_city_list_region();return false;">'.$region['name'].'</a></p>';
                $count++;
            }
            ?>

    </div>

</div>
<?php

echo '</div>';
echo '</div>';
