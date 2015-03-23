<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\bootstrap\Modal;
use yii\bootstrap\Button;
use yii\helpers\Url;



/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$city_name=Yii::$app->ipgeobase->getCityName(Yii::$app->request->userIP);
$menu=app\modules\tovar\models\TovarSearch::category_menu();
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
<div class="wrap">
    <div class="navbar">

<!--        <div class="top-head"></div>-->

        <?php
        NavBar::begin([
                'brandLabel' => 'Колеса даром',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

//        foreach($menu as $m)
//        {
//            echo "['label'=>'".$m['label']."','url'=>'".$m['url']."',],";
//        }
//die;
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label'=>'Информация', 'items'=>[
                        ['label' => 'Главная страница', 'url' => ['/site/index']],
                        ['label' => 'О компании', 'url' => ['/site/about']],
                        ['label' => 'Обратная связь', 'url' => ['/site/contact']],

                    ]],
                    Yii::$app->user->isGuest ?
                        ['label' => 'Личный кабинет', 'items'=>[
                        ['label' => 'Регистрация', 'url' => ['/user/registration/register']],
                        ['label' => 'Вход', 'url' => ['/user/security/login']],
                        ]]
                        :['label' => 'Личный кабинет', 'items'=>[
                        ['label'=> 'Профиль пользователя', 'url' => ['/user/settings/profile']],
                        ['label'=> 'Учетные данные', 'url' => ['/user/settings/account']],
                        ['label'=> 'Соцсети', 'url' => ['/user/settings/networks']],
                        ['label' => 'Выход' ,'url' => ['/user/security/logout'],'linkOptions' => ['data-method' => 'post']]
                    ]],
                    ['label'=>'Товар','items'=>Yii::$app->params['catalog']['items'] ],
                    ['label' => 'Корзина','url'=>['/basket/basket']],
            ]]);
            NavBar::end();
        ?>
    </div>
    <div class="col-md-10">

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
    <div class="col-md-2">

<?= Nav::widget(['items'=>Yii::$app->params['catalog']['items']]);?>
    </div>

<!--    <div class="col-md-1">-->
<!--        <a href="--><?//= url::toRoute(['/basket/basket'], true) ?><!--">Корзина</a>-->
<!--    </div>-->
    <div class="clearfix"></div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
<?php
Modal::begin ( [
'header' => '<h2>'.'Города'.'</h2>',
'toggleButton' => [
'tag' => 'button',
'class' => 'btn btn-lg btn-block btn-info',
'label' => $city_name,
'id'=>'button_city_list',

]
] );

//        \yii\widgets\Pjax::begin();


echo Button::widget ( [
    'label' => 'Выбрать город',
    'options' => [
        'class' => 'btn-lg btn-default',
        'style' => 'margin:5px',
        'onclick' => 'load_city_list()',
    ], // add a style to overide some default bootstrap css
    'tagName' => 'div'
] );
//        \yii\widgets\Pjax::end();
echo '<div id="city_list"></div>';
Modal::end ();?>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
