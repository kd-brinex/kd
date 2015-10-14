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
////            [['type_code','lang_code','name','cat_code','value'], 'safe'],
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
            ->where('cat_code=:cat_code',[':cat_code'=>$params['cat_code']])
        ->andWhere("value<>''");
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);

//        $this->load($params);

        return $dataProvider;
    }
}