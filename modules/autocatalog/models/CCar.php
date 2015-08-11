<?php
namespace app\modules\autocatalog\models;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.08.15
 * Time: 8:32
 */
use yii\base\Model as BaseModel;

class CCar extends BaseModel implements ICar
{
    public $model_name;    // - название модели
    public $prod_start;    // - начало производства
    public $prod_end;      // - окончание производства
    public $models_codes;  // - код модели
    public $catalog_code;  // - код каталога
    public $catalog_name;  // - название каталога
    public $region;       // - код региона
    public $lang;          // - язык

    public function getModelList($prm)
    {
        return [];
    }

    public function getRegionList()    //Список регионов
    {
        return [];
    }

    public function getVehicle($prm)
    {
        return [];
    }
}