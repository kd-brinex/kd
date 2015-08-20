
<?php

use yii\helpers\Html;
use yii\bootstrap\Button;
use app\modules\city\cityAsset;
cityAsset::register($this);


echo '<div id="city_list_block">';
echo '<div class="city">';

?>


<div class="row">
    <?php if (count($data['stories'])>0) { ?>
    <div class="col-lg-12 stories1">
        <p>Шинные центры:</p>
        <ul>
            <?php
            foreach ($data['stories'] as $city) {
                echo '<li><a href="#"   char="' . mb_substr($city['name'], 0, 1, 'utf-8') . '" onclick="setCookies(\'city\',\'' . $city['id'] . '\',\'cl=true\')">' . $city['name'] . '</a></li>';
            }
            ?>

        </ul>
    </div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-lg-12 delivery1">
        <p class="delivery_title">Доставка в другие города:</p>
        <?php
        if (count($data['delivery'])==0) echo '<p>Ничего не найдено</p>';
        else {
            $count = 1;
            foreach ($data['delivery'] as $region) {
                if ($count == 40) {
                    echo '</div> <div class="col-lg-3 delivery">';
                    $count = 1;
                }
                echo '<p class="delivery_name"><a href="#"   char="' . mb_substr($region['name'], 0, 1, 'utf-8') . '" onclick="setCookies(\'city\',\'' . $region['id'] . '\',\'cl=true\')">' . $region['name'] . '</a>';

                $count++;
            }
        }
        ?>

    </div>

</div>
<?= Html::button('Вернуться к списку', ['class' => 'btn btn-default','onclick'=>'load_city_list()'])?>

<?php

echo '</div>';
echo '</div>';
