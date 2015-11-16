<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\bootstrap\Modal;
use yii\bootstrap\Button;


AppAsset::register($this);
$city_name = Yii::$app->ipgeobase->getCityName(Yii::$app->request->userIP);
$menu = app\modules\tovar\models\TovarSearch::category_menu();
//var_dump(Yii::$app->params);die;
$items=Yii::$app->params['navbar']['all'];
if (Yii::$app->user->isGuest){$items=array_merge($items,Yii::$app->params['navbar']['quest']);}
    else
    {$items=array_merge($items,Yii::$app->params['navbar']['user']);}

$navbar =['options' => ['class' => 'navbar-nav navbar-right'],'items' =>$items ];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    $setCanonical = false;
    $checkDb = true;
    Yii::$app->seotools->setMeta([], $setCanonical, $checkDb);
    ?>
    <?= Html::csrfMetaTags() ?>

    <title><?= $this->title ?></title>

    <?= $this->head() ?>

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
Modal::begin([
    'header' => '<div class="header_img"><img src="/img/kolesa-darom_logo.png"/></div><div class="cities pull-right"><input id="city_select" type="text" placeholder="Выберите город">

            <ul class="cities_select pull-right invisible">
            </ul>

            </div>
            <div class="clearfix"></div>
            ',
    'toggleButton' => [
        'tag' => 'button',
        'class' => 'btn btn-info btn-city',
        'label' => $city_name,
        'id' => 'button_city_list',
        'onclick'=>'load_city_list()',
    ],
]);
echo '<div id="city_list"></div>';
Modal::end();
echo Nav::widget($navbar);
NavBar::end();
?>


<div class="container">

    <div class="row row-offcanvas row-offcanvas-left">
        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
            <?= Nav::widget(['options' => ['class' => 'nav nav-sidebar'], 'items' => Yii::$app->params['catalog']['items']]); ?>
        </div>
        <div class="col-xs-12 col-sm-9">
            <p class="pull-right visible-xs col-xs-12">
                <button type="button" class="btn btn-toolbar btn-group-xs" data-toggle="offcanvas">Товары</button>
            </p>
            <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
            <?= Yii::$app->seotools->getH1title() ?>
            <?= Yii::$app->seotools->getInfotextbefore() ?>
            <?= $content ?>
            <?= Yii::$app->seotools->getInfotextafter() ?>
        </div>

    </div>
    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>











