<?php
use yii\widgets\Breadcrumbs;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 03.10.15
 * Time: 15:37
 */
//var_dump($option);die;
echo (!empty($params['breadcrumbs']))?Breadcrumbs::widget(['links'=>$params['breadcrumbs']]):'';
?>
<div class="models">
    <?= $this->render('listview',['dataProvider'=>$provider,'view'=>'block','params'=>$params])?>
</div>