<?php
namespace app\modules\autocatalog\models;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 02.10.15
 * Time: 16:02
 */
class PartsSearch extends ActiveRecord
{
    public function rules()
    {
        return [
//            [['cat_folder','name','cat_code','img'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
//            '' => 'Регион',
        ];
    }

    public static function tableName()
    {
        return 'v_parts';
    }

    public function search($params=[])
    {
    $query =parent::find();
    return $query;
    }
}