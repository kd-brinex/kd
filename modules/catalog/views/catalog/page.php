<?php
use yii\grid\GridView;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 26.06.15
 * Time: 16:50
 */
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => []
    ]);