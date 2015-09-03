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
    public $models;
    public $catalog;
    public function init()
    {
        parent::init();
        foreach ($this->models as $key=>$model)
        {
            $this->catalog[$key]=\Yii::createObject($model);
        }

    }
public function getCatalog()
{
   return $this->catalog;
}
    public function getModelList($prm)
    {
        return $this->catalog[$prm['marka']]->__FUNCTION__;
    }
    public function searchVIN($prm)
    {
        $modelList=$this->getModelList($prm);
        $res=false;
        foreach ($modelList as $model)
        {
            $res=$model->searchVIN($prm);
            if($res){return $res;}
        }
        return $res;
    }

}