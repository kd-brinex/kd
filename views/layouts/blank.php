<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 15.04.15
 * Time: 15:06
 */
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
$asset = app\modules\catalog\catalogAsset::register($this);
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
    <?php $this->beginBody();
//var_dump($this->params['breadcrumbs']);die;
    ?>
    <div class="col-lg-12 container">
        <?= Breadcrumbs::widget([
            'homeLink'=>[
                'label'=>'Каталог',
                'url'=>'http://www.kolesa-darom.ru/auto-parts/autocatalog/'],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
        <?= $content ?>
    </div>

    <?php $this->endBody() ?>
    </body>

    </html>
<?php $this->endPage() ?>