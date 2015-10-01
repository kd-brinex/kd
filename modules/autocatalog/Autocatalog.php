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
    public function init()
    {
        parent::init();
    }
public function getModel()
{
  return $this->model;
}
    public function getCatalog($prm)
    {
            return Yii::createObject($this->model[$prm['marka']]);
    }

    public function getCars($prm)
    {
        $catalog=$this->getCatalog($prm);
        return $catalog->getCars($prm);
    }

    public function getModels($prm)
    {
        $catalog=$this->getCatalog($prm);
        return $catalog->getModels($prm);
    }
    public function getCatalogs($prm)
    {
        $catalog=$this->getCatalog($prm);
        return $catalog->getCatalogs($prm);
    }
    public function searchVIN($prm)
    {

    }

}