<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\auto;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by Gii.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class autoAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/auto/assets';
    public $css = [
        'css/fw.css',
        'css/adc.css',
        'css/style.css',
    ];
    public $js = [
        'js/base.js',
        'js/dataTable.js',
//        'js/jquery.dataTables.min.js',
        'js/myTree.js',
//        'js/jquery-2.1.0.min.js',
//        'js/ZeroClipboard.js'

    ];
    public $depends = [
//        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
//        'yii\gii\TypeAheadAsset',
    ];
}
