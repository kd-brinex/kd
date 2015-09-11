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
$navbar = [
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        ['label' => 'Информация', 'items' => [
            ['label' => 'Главная страница', 'url' => ['/site/index']],
            ['label' => 'О компании', 'url' => ['/site/about']],
            ['label' => 'Обратная связь', 'url' => ['contact']],
            ['label' => 'Партнеры', 'url' => ['partner']],

        ]],
        Yii::$app->user->isGuest ?
            ['label' => 'Личный кабинет', 'items' => [
                ['label' => 'Регистрация', 'url' => ['/user/registration/register']],
                ['label' => 'Вход', 'url' => ['/user/security/login']],
            ]]
            : ['label' => 'Личный кабинет', 'items' => [
//            ['label' => 'Профиль пользователя', 'url' => ['/user/settings/profile']],
//            ['label' => 'Учетные данные', 'url' => ['/user/settings/account']],
//            ['label' => 'Соцсети', 'url' => ['/user/settings/networks']],
            ['label' => 'Заказы', 'url' => ['/user/settings/orders']],
            ['label' => 'Выход', 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']]
        ]],
//                ['label' => 'Товар', 'items' => Yii::$app->params['catalog']['items']],
        ['label' => 'Корзина', 'url' => ['/basket/basket']],
    ]];
?>
<?php $this->beginPage() ?>
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
            <?= $content ?>
        </div>


        <?php

        Modal::begin([
            'header' => '<div class="header_img"><img src="/img/kolesa-darom_logo.png"/></div><div class="cities pull-right"><input id="city_select" type="text" placeholder="Выберите город">

            <ul class="cities_select pull-right invisible">
            </ul>

            </div>
            <div class="clearfix"></div>
            ',
            'toggleButton' => [
                'tag' => 'button',
                'class' => 'btn btn-lg btn-block btn-info',
                'label' => $city_name,
                'id' => 'button_city_list',
                'onclick'=>'load_city_list()',
            ],
        ]);


        echo '<div id="city_list"></div>';
        Modal::end(); ?>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>











