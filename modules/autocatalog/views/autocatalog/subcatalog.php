<?php
use kartik\grid\GridView;
use yii\bootstrap\Tabs;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 03.10.15
 * Time: 15:37
 */

?>
<div class="models">
    <?= $this->render('listview',['dataProvider'=>$provider,'view'=>'block','params'=>$params])?>

</div>