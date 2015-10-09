<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\tovar;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by Gii.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class categoryAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/tovar/assets';
    public $css = [
        'css/style-offer.css',
    ];

    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
    public $depends = [
        'yii\web\YiiAsset',

//        'yii\bootstrap\BootstrapPluginAsset',
//        'yii\gii\TypeAheadAsset',
    ];
}
