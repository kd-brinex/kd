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
    public function getDb()
    {
//        var_dump($this->db);die;
        if (empty($this->connect)){$this->connect=\Yii::createObject($this->db);}
        return $this->connect;  // use the "db2" application component
    }
    public function searchVIN($params){
        return false;
    }

}