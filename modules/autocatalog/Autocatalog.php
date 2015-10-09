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
;

class Autocatalog extends Module
{
    public $model;
    public $catalog;
    public $db;
    public function init()
    {
        parent::init();
        $params = Yii::$app->request->queryParams;
        if (!empty($params['marka'])){
        $this->catalog=$this->model[$params['marka']];
        $this->db=$this->getDb();}
    }
public function getModel()
{
  return $this->model;
}

    public function getDb()
    {
       return Yii::createObject($this->catalog['db']);

    }
    public function getClass()
    {
        return Yii::createObject($this->catalog['class']);
    }
//    public function getCars($prm)
//    {
//        $catalog=$this->getCatalog($prm);
//        return $catalog->getCars($prm);
//    }

//    public function getModels($prm)
//    {
//        $catalog=$this->getCatalog($prm);
//        return $catalog->getModels($prm);
//    }
//    public function getCatalogs($prm)
//    {
//        $catalog=$this->getCatalog($prm);
//        return $catalog->getCatalogs($prm);
//    }
    public function searchVIN($prm)
    {

    }


}