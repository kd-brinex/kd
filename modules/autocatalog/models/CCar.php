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
    public $year_start;     // - год начала производства
    public $year_end;       // - год окончания производства
    public $models_codes;  // - код модели
    public $catalog_code;  // - код каталога
    public $catalog_name;  // - название каталога
    public $region;       // - код региона
    public $lang = 'RU';          // - язык
    public $image_path;      //Путь к рисункам
    public $marka;      //Марка авто
    public $years;

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
    public function getYears()
    {
        return $this->years;
    }
    public function setYears($value)
    {
//        var_dump($value);die;
        if(is_array($value)){$this->years=$value;}
        else
        {
        for($i=(int)$this->year_start;$i<= $this->Year_end;$i++)
        {
            $car_year[]=$i;
        }
        $this->years= $car_year;}
    }
    public function getYear_end()
    {
        return (int)(($this->year_end>=$this->year_start)?$this->year_end:date('Y'));
    }
    public function setRegion($value)
    {
        $this->region=$value;
    }
//    public function setData($params)
//    {
//        foreach ($params as $property => $value) {
//            if (property_exists($this, $property)) {
//                if (is_array($this->$property)) {
//                    $this->$property = array_merge($this->$property, $value);
//                } else {
//                    $this->$property = $value;
//                }
//            }
//        }
//    }
}