<?php
namespace app\modules\autocatalog\models;
use yii\data\ActiveDataProvider;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 03.10.15
 * Time: 13:18
 */
class InfoSearch extends ActiveRecord
{
    public function rules()
    {
        return [
            [['cat_code','cat_name','family','vehicle_type'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cat_code'=>'Код каталога',
            'cat_name'=>'Автокаталог',
            'family'=>'Модельный ряд',
            'vehicle_type'=>'Тип автомобиля',
            'marka' => 'Марка автомобиля'
        ];
    }

    public static function tableName()
    {
        return 'v_catalog_info';
    }

    public function search($params=[])
    {
        $query =parent::find()->where('cat_code=:cat_code',[':cat_code'=>$params['cat_code']]);
        return $query;
    }
}