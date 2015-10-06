<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 13.07.15
 * Time: 15:37
 */
namespace app\modules\parser;

use \yii\base\Module as BaseModule;


/**
 * This is the main module class for the Yii2-user.
 *
 * @property array $modelMap
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Parser extends BaseModule
{
    public $controllerNamespace = 'app\modules\parser\controllers';

    public function init()
    {
        parent::init();
       // custom initialization code goes here
    }
}
