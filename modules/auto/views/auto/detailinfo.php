<?php
use yii\bootstrap\Alert;

/// Получаем переменные из своего окружения
$sTreeID  = $params['tree'];
$sModelID = $params['model'];

/// Получаем информацию о модели
$aInfo = $oAdcpi->getDetailInfo($sModelID,$sTreeID); ///$oA2D->e($aInfo);
$body='<div><span class="col-md-4">Название</span><span class="col-md-7">'.$aInfo->auto.'</span>'
    .'<span class="col-md-4">Модификация</span><span class="col-md-7">'.$aInfo->modification.'</span></div>';

echo Alert::widget ( [
    'options' => [
        'class' => 'alert-info'
    ],
    'body' => $body,
] );

