<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.08.15
 * Time: 10:43
 */
use yii\grid\GridView;
use yii\widgets\ListView;
?>
<div class="container">
<?= ListView::widget([
    'dataProvider'=>$data['dataProvider'],
    'itemView' => function ($model, $key, $index, $widget) {
        return $this->render(
            '_model_view', ['model' => $model]);
    },
]);?>
</div>