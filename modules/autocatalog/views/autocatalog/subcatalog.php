<?php
use yii\widgets\Breadcrumbs;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 03.10.15
 * Time: 15:37
 */
echo (!empty($params['breadcrumbs']))?Breadcrumbs::widget(['links'=>$params['breadcrumbs']]):'';
echo $params['option'];
?>
<div class="models">
    <?= $this->render('listview',['dataProvider'=>$provider,'view'=>'block','params'=>$params])?>

</div>