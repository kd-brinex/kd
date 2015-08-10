<?php
use yii\helpers\Url;

?>

<div class="col-sm-12 offer-v1-item borders-lite">
        <div class="offer-v1-code">Код: <?= $model->category_id ?>/<?= $model->id ?></div>

        <div class="offer-v1-img">
            <div class="offer-v1-bonus">
                <a href="/samara/buyer/program-ball/" target="_blank"
                   title="Количество начисляемых баллов. Баллы начисляются при покупке товара через сайт! Начисленные баллы становятся активными по истечении 14 дней с момента покупки.">
                    <img src="http://kolesa-darom.ru/img2/goods-bonuspoint.png"> +<?= $model->ball ?></a>
            </div>
            <a href="<?= url::toRoute(['view', 'id' => $model->id], true) ?>">
                <div class="offer-v1-img-bg" style="background-image: url(<?= $model->image ?>);"></div>
            </a>
        </div>

        <h3><a href="<?= url::toRoute(['view', 'id' => $model->id], true) ?>"><?= $model->name ?></a></h3>

    <div class="offer-v1-deliv">
        <span><?= $model->srok ?></span>
    </div>

    <div class="offer-v1-price">
        <div class="offer-v1-price-new">
            <?= $model->asCurrency($model->price) ?>
        </div>

    </div>
    <div class="offer-v1-order">
        <?= $this->render('btn_basket', ['model' => $model, 'viewtype' => 1]); ?>

    </div>
</div>
