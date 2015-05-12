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
//        ['label' => 'Информация', 'items' => [
//            ['label' => 'Главная страница', 'url' => ['/site/index']],
//            ['label' => 'О компании', 'url' => ['/site/about']],
//            ['label' => 'Обратная связь', 'url' => ['/site/contact']],
//
//        ]],

//        ['label' => 'Выход', 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']],

        ['label' => 'Запчасти', 'items' => [
            ['label' => 'Поставщики', 'url' => ['autoparts/provideruser']],
            ['label' => 'Учетные записи'],

        ]],
//                ['label' => 'Товар', 'items' => Yii::$app->params['catalog']['items']],
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

        <div class="col-xs-12 col-sm-9">
            <p class="pull-right visible-xs col-xs-12">
                <button type="button" class="btn btn-toolbar btn-group-xs" data-toggle="offcanvas">Товары</button>
            </p>
            <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
            <?= $content ?>
        </div>


        <?php
//        Modal::begin([
//            'header' => '<h2>' . 'Города' . '</h2>',
//            'toggleButton' => [
//                'tag' => 'button',
//                'class' => 'btn btn-lg btn-block btn-info',
//                'label' => $city_name,
//                'id' => 'button_city_list',
//
//            ]
//        ]);
//        echo Button::widget([
//            'label' => 'Выбрать город',
//            'options' => [
//                'class' => 'btn-lg btn-default',
//                'style' => 'margin:5px',
//                'onclick' => 'load_city_list()',
//            ],
//            'tagName' => 'div'
//        ]);

//        echo '<div id="city_list"></div>';
//        Modal::end(); ?>
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











