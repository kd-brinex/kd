
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
<ul class="stories_s" style="display: none;">
    <p class="ul_title">Шинные центры:</p>
    <p class="not_found ul_title">Ничего не найдено.</p>
    <?php
    foreach ($data['stories'] as $city)
    {
        echo '<li><a href="#"  onclick="setCookies(\'city\',\''.$city['id'].'\',\'cl=true\')">'.$city['name'].'</a></li>';
    }
    ?>
</ul>
<ul class="stories_all" style="display: none">
    <p class="ul_title">Доставка в другие города:</p>
    <?php
    foreach ($data['stories_all'] as $city)
    {
        echo '<li><a href="#"   onclick="setCookies(\'city\',\''.$city['id'].'\',\'cl=true\')">'.$city['name'].'</a></li>';
    }
    ?>
</ul>

<div class="row">
    <div class="col-lg-3 stories">

        <ul>
            <p>Шинные центры:</p>
            <?php
                foreach ($data['stories'] as $city)
                {
                    echo '<li><a href="#"  onclick="setCookies(\'city\',\''.$city['id'].'\',\'cl=true\')">'.$city['name'].'</a></li>';
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
