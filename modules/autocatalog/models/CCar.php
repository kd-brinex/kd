<?php
namespace app\modules\autocatalog\models;


/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.08.15
 * Time: 8:32
 */
use yii\base\Model as BaseModel;
use Yii;


class CCar extends BaseModel
{
    public $prop;
    public $db;
    public $connect;
    public $image;

    public static function Search($find_class, $params = [])
    {

        $class = __NAMESPACE__ . '\\' . $find_class;
        $models = new $class;
        return $models;
    }

    public static function CarsSearch($params)
    {
        $models = new CarsSearch();
        return $models;
    }

    public static function ModelsSearch($params)
    {
        $models = new ModelsSearch();
        return $models;
    }

    public static function CatalogsSearch($params)
    {
        $models = new CatalogsSearch();
        return $models;
    }

    public static function CatalogSearch($params)
    {
        $models = new CatalogSearch();
        return $models;
    }

    public static function SubCatalogSearch($params)
    {
        $models = new SubCatalogSearch();
        return $models;
    }

    public static function PartsSearch($params)
    {
        $models = new PartsSearch();
        return $models;
    }

    public static function InfoSearch($params)
    {
        $models = new InfoSearch();
        return $models;
    }

    public static function VinSearch($params)
    {
        $models = new VinSearch();
        return $models;
    }


}