<?php
namespace app\modules\autocatalog\models;
use yii\data\ActiveDataProvider;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 03.10.15
 * Time: 13:18
 */
class RegionsSearch extends ActiveRecord
{


    public static function tableName()
    {
        return 'v_regions';
    }

    public function search($params=[])
    {
        $query =parent::find();
        return $query;
    }
    public function getName()
    {
        return $this->region;
    }

}