<?php

namespace app\modules\tovar\controllers;

use app\controllers\MainController;

class DefaultController extends MainController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
