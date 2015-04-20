<?php

use yii\helpers\Html;

$sTypeName = $oAdcpi->property($oMarkList, 'typeName');

$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/auto']];
$this->params['breadcrumbs'][] = $sTypeName;

echo Html::tag("h1", "Марки в группе " . $sTypeName);
$body="";
foreach ($aMarkList AS $oMark) {
    $html = Html::tag("span", Html::img($oMark->mark_img_url, ["alt" => $oMark->mark_name])."<br>", ["class" => "markLogo"]);
    $html.= Html::tag("span", $oMark->mark_name, ["class" => "markName"]);
    $html = Html::a($html, $oAdcpi->getMarkUrl($oMark));
    $html = Html::tag("div", $html, ['class' => "markItem"]);
    $body.= Html::tag("div", $html, ["class" => "col-xs-4 col-md-3 col-lg-2"]);
}

$html = Html::tag("div", $body, ["id" => "AutoDealer"]);
echo $html;


