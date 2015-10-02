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
use app\modules\autocatalog\models\ModelsSearch;

class CCar extends BaseModel
{
    public $prop;
    public $db;
    public $connect;
    public $image;


    public function getData($name,$where='',$params=[])
    {
        $name='app\modules\autocatalog\models\\'.$name;
        $a_record =new $name;
        $a_record->where($where,$params);
       return $a_record;
    }

    public function getCars()
    {
        return $this->getData('CarsSearch');
    }

    public function getModels($params)
    {
        $w_params = [':family'=>$params['family']];
        return $this->getData('ModelsSearch','family=:family',$w_params);
    }
    public function getCatalogs($params)
    {
        $w_params = [':cat_code'=>$params['cat_code']];
        return $this->getData('CatalogsSearch','cat_code=:cat_code',$w_params);
    }

    public function searchVIN($params)
    {
        return false;
    }
    public function search($params)
    {
        return $this->getModels($params);
    }

}