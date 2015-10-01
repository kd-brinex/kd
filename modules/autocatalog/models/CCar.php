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
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\data\ActiveDataProvider;

class CCar extends BaseModel
{
    public $prop;
    public $db;
    public $connect;
    public $image;

    public function getDb()
    {
//        var_dump($this->db);die;
        if (empty($this->connect)){$this->connect=\Yii::createObject($this->db);}
        return $this->connect;  // use the "db2" application component
    }
    public function getViews($name,$where='',$params=[])
    {
        $query = new Query();
        $query->select('*')->from($name)->where($where,$params);
       return $query->all($this->getDb());
    }

    public function getCars()
    {
        return $this->getViews('v_cars');
    }

    public function getModels($params)
    {
        $w_params = [':family'=>$params['family']];
        return $this->getViews('v_models','family=:family',$w_params);
    }
    public function getCatalogs($params)
    {
        $w_params = [':cat_code'=>$params['cat_code']];
        return $this->getViews('v_catalogs','cat_code=:cat_code',$w_params);
    }

    public function searchVIN($params){

        return false;
    }

}