
<div class="store-box">
    <div class="deliveryChoiceClicker" onClick="deliveryChoiceClicker(this)"></div>
    <div class="store-name store-row-cell">
        <div class="store-row-cell-header">
            Название
        </div>
        <?=$model->name?>
    </div>
    <div class="store-addr store-row-cell">
        <div class="store-row-cell-header">
            Адрес
        </div>
        <?=$model->addr?>
    </div>
    <div class="store-phone store-row-cell">
        <div class="store-row-cell-header">
            Телефон
        </div>
        <?=$model->tel?>
    </div>
    <div class="store-check store-row-cell">
        <?=\yii\helpers\Html::radio('deliveryStore', false, ['value' => $model->id])?>
    </div>
</div>
