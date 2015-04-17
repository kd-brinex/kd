<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 15.04.15
 * Time: 15:06
 */
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$asset = app\modules\auto\autoAsset::register($this);
 $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="container">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">A Product of <a href="http://www.yiisoft.com/">Yii Software LLC</a></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
