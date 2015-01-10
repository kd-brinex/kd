<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 09.01.15
 * Time: 11:25
 */

echo yii\widgets\ListView::widget([

    'dataProvider' => $dataProvider,

//    'pager'        => [
//
//        'firstPageLabel'    => Glyph::icon(Glyph::ICON_FAST_BACKWARD),
//
//        'lastPageLabel'     => Glyph::icon(Glyph::ICON_FAST_FORWARD),
//
//        'nextPageLabel'     => Glyph::icon(Glyph::ICON_STEP_FORWARD),
//
//        'prevPageLabel'     => Glyph::icon(Glyph::ICON_STEP_BACKWARD),
//
//    ],

    'itemOptions' => ['style' => 'float:left;width:300px;height:300px;margin:5px !impotant'],

    'itemView' => function ($model, $key, $index, $widget) {

        return $this->render('tovar_block_view',['model' => $model]);

    },

]);
