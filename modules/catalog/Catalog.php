<?php

/*
 * автокаталоги
 * Хусаинов М.Ф.
 * 16.06.2015
 */

namespace app\modules\catalog;

use \yii\base\Module as BaseModule;


/**
 * This is the main module class for the Yii2-user.
 *
 * @property array $modelMap
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Catalog extends BaseModule
{
//    public $controllerNamespace = 'app\modules\catalog\controllers';
    public $db;
    public $models;
    public $image;
    public function init()
    {
        parent::init();

        models\AvQuery::$db=$this->db;
        models\AvQuery::$image=$this->image;
        models\Translate::$db=$this->db;
//        $this->layout='avtocatalog';
        // custom initialization code goes here
    }
}
