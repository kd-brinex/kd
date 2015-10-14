<?php
namespace app\modules\autocatalog\models;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 02.10.15
 * Time: 16:02
 */
class SubCatalogSearch extends ActiveRecord
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
        return 'v_subcatalog';
    }

    public function search($params=[])
    {
        $query =parent::find()
            ->where('cat_code=:cat_code',[':cat_code'=>$params['cat_code']])
        ->andWhere('sect=:sect',[':sect'=>$params['sect']])
        ->andWhere('cat_folder=:cat_folder',[':cat_folder'=>$params['cat_folder']]);


        return $query;
    }
}