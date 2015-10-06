<?php
namespace app\modules\autocatalog\models;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 02.10.15
 * Time: 16:02
 */
class CatalogSearch extends ActiveRecord
{
    public function rules()
    {
        return [
            [['cat_folder','name','cat_code','img'], 'safe'],
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
        return 'v_catalog';
    }

    public function search($params=[])
    {
        $query =parent::find()
            ->where('cat_code=:cat_code',[':cat_code'=>$params['cat_code']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);

//        $this->load($params);

        return $dataProvider;
    }
}