<?php
namespace app\modules\autocatalog\models;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 02.10.15
 * Time: 16:02
 */
class CatalogsSearch extends ActiveRecord
{
//    public function rules()
//    {
//        return [
//            [['marka'], 'safe'],
//        ];
//    }
//
//    public function attributeLabels()
//    {
//        return [
////            '' => 'Регион',
//        ];
//    }

    public static function tableName()
    {
        return 'v_catalogs';
    }

    public function search($params=[])
    {
        $query =parent::find()
            ->distinct()
            ->where('cat_code=:cat_code',[':cat_code'=>$params['cat_code']])
        ->andWhere("value<>''");

        return $query;
    }
}