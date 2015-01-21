<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\widgets\ListView;
use app\assets\AppAsset;
use yii\bootstrap\Modal;
use \yii\grid\GridView;
//use app\modules\city\City;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
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
        <div class="top-head"></div>
        <?php
        $city_name=Yii::$app->ipgeobase->getCityName(Yii::$app->request->userIP);
//var_dump($city_name);die;
        \yii\widgets\Pjax::begin();
        Modal::begin ( [
            'header' => '<h2>'.'Выберите город'.'</h2>',
            'toggleButton' => [
                'tag' => 'button',
                'class' => 'btn btn-lg btn-block btn-info',
                'label' => $city_name,
            ]
        ] );

        echo Yii::$app->ipgeobase->getListCites(['city'=>$city_name,'limit'=>100]);

//        echo 'Надо взять на вооружение.';
        Modal::end ();
        \yii\widgets\Pjax::end();
        NavBar::begin([
                'brandLabel' => 'Колеса даром',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

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
                    ['label' => 'Товары', 'items'=>app\modules\tovar\models\TovarSearch::category_menu() ],
            ]]);
            NavBar::end();
        ?>
</div>
    <div class=""left">

    </div>
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
