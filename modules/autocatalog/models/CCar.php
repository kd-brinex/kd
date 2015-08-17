<?php
namespace app\modules\autocatalog\models;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.08.15
 * Time: 8:32
 */
use yii\db\ActiveRecord as BaseModel;
use Yii;
class CCar extends BaseModel
{
    public $lang = 'RU';          // - язык
    public $region;       // - код региона
    public $marka;          //- марка авто
    public $vin;
    public $model_name;    // - название модели
    public $prod_start;    // - начало производства
    public $prod_end;      // - окончание производства
    public $year_start;    // - год начала производства
    public $year_end;      // - год окончания производства
    public $models_codes;  // - код модели
    public $catalog_code;  // - код каталога
    public $catalog_name;  // - название каталога
    public $image_path;    //Путь к рисункам
    public $years;

    public static function getDb()
    {
        return Yii::$app->db;  // use the "db2" application component
    }
}