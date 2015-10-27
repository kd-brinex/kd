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
use yii\helpers\Url;


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

    public static function PodborSearch($params)
    {
        $models = new PodborSearch();
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

    public static function ImagesSearch($params)
    {
        $models = new ImagesSearch();
        return $models;
    }

    public static function RegionsSearch($params)
    {
        $models = new RegionsSearch();
        return $models;
    }

    public static function Breadcrumbs($params)
    {
        $links=[];
        if (isset($params['marka'])) {
            $url=Url::to('/autocatalogs');
            $links[] = [
                'label' => 'Автокаталог',
                'url' => $url,
            ];
        }
        if (isset($params['region'])) {
            $url.='/'.$params['marka'].'/'.$params['region'];
            $links[] = [
                'label' => $params['marka'],
                'url' => $url,
            ];
        }
        if (isset($params['family'])) {
            $url.='/'.$params['family'];
            $links[] = [
                'label' => $params['family'],
                'url' => $url,
            ];
        }
        if (isset($params['cat_code'])) {
            $url.='/'.$params['cat_code'].'/'.$params['option'];
            $links[] = [
                'label' => $params['cat_code'],
                'url' => $url,
            ];
        }
        if (isset($params['cat_folder'])) {
            $url.='/'.$params['cat_folder'];
            $links[] = [
                'label' => $params['cat_folder'],
                'url' => $url,
            ];
        }
        if (isset($params['sect'])) {
            $url.='/'.$params['sect'];
            $links[] = [
                'label' => $params['sect'],
                'url' => $url,
            ];
        }
        if (isset($params['sub_sect'])) {
            $url.='/'.$params['sub_sect'];
            $links[] = [
                'label' => $params['sub_sect'],
                'url' => $url,
            ];
        }
        return $links;
    }

}