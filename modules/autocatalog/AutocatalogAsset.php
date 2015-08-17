<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\autocatalog;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by Gii.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AutocatalogAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/catalog/assets';
    public $css = [
        'css/acatalog.css',

    ];
    public $js = [
        'js/page.js'
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
