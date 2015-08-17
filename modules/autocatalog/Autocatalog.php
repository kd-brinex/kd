<?php
namespace app\modules\autocatalog;
use \yii\base\Module;
use \yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 03.08.15
 * Time: 9:54
 */
class Autocatalog extends Module
{
    public $model;
    public $car;
    public function init()
    {
        parent::init();
        $this->car= Yii::createObject($this->model);
    }
    public function getDB()
    {
        return $this->car->db;
    }


}