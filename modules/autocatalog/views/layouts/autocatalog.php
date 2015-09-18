<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 15.04.15
 * Time: 15:06
 */
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

use yii\helpers\Url;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;

$asset = app\modules\autocatalog\AutocatalogAsset::register($this);

$items=Yii::$app->params['navbar']['all'];
if (Yii::$app->user->isGuest){$items=array_merge($items,Yii::$app->params['navbar']['quest']);}
else
{$items=array_merge($items,Yii::$app->params['navbar']['user']);}

$navbar =['options' => ['class' => 'navbar-nav navbar-right'],'items' =>$items ];



$this->beginPage() ?>

    <!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
<body>

<?php $this->beginBody() ?>

<?php
NavBar::begin([
    'brandLabel' => 'Колеса даром',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-fixed-top navbar-inverse',
    ],
]);

echo Nav::widget($navbar);
NavBar::end();
?>
git
    <div class="container">
        <div class="row row-offcanvas row-offcanvas-left">
        <?=$content ?>
            </div>
    </div>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>