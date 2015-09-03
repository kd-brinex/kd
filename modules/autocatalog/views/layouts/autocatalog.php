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

$asset = app\modules\autocatalog\AutocatalogAsset::register($this);


$this->beginPage() ?>
<?php $this->head() ?>
<?php $this->beginBody() ?>
    <div class="container">



        <?=$content ?>
    </div>

<?php $this->endBody() ?>


<?php $this->endPage() ?>