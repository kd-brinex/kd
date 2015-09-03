<?php

namespace app\modules\autoparts;

class Provideruser extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\autoparts\controllers';

    public function init()
    {
        parent::init();
        \Yii::configure($this, require(__DIR__ . '/config/config.php'));

        // custom initialization code goes here
    }
}
