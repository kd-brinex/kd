<div id="BMW_ModelInfo" class="mb40">
    <div class="fl">
        <img src="<?=$modelInfo->Image?>">
    </div>
    <div class="fl tl ml50">
        Серия: <?=$modelInfo->SeriesName?><br>
        Модель: <?=$modelInfo->ModelCode?><br>
        Кузов: <?=ucfirst($modelInfo->BodyName)?><br>
        Рынок: <?=$modelInfo->MarketName?>
    </div>
    <div class="fl tl ml50">
        Рулевое управление: <?=$modelInfo->RuleName?><br>
        Коробка передач: <?=$modelInfo->GetriebeName?><br>
        Производство: <?=((int)$modelInfo->curDate>0)?substr($modelInfo->curDate,4,2).".".substr($modelInfo->curDate,0,4):"Не важно";?><br>
    </div>
    <div class="clear"></div>
</div>