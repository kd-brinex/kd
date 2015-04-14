<?php
use yii\helpers\Url;

?>

<div class="col-sm-3 offer-v1-item">
        <div class="offer-v1-code">Код: <?= $model->category_id ?>/<?= $model->id ?></div>

        <a href="<?= url::toRoute(['view', 'id' => $model->id], true) ?>">
            <div class="offer-v1-img-bg" style="background-image: url(<?= $model->image ?>);"></div>
        </a>


        <h3><a href="<?= url::toRoute(['view', 'id' => $model->id], true) ?>"><?= $model->name ?></a></h3>

    <div>
        <?= $model->srok ?>
    </div>
    <div>
        <a href="/samara/buyer/program-ball/" target="_blank"
           title="Количество начисляемых баллов. Баллы начисляются при покупке товара через сайт! Начисленные баллы становятся активными по истечении 14 дней с момента покупки.">
            <img src="http://kolesa-darom.ru/img2/goods-bonuspoint.png"> +<?= $model->ball ?></a>
    </div>
    <div>
        <?= $model->asCurrency($model->price) ?>
    </div>
    <div>
        <?= $this->render('btn_basket', ['model' => $model, 'viewtype' => 1]); ?>

    </div>
</div>
