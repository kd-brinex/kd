<?php
namespace app\modules\autocatalog\models;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 9:50
 */

class Hyundai extends CCar
{
    public static function Cars($params)
    {
        $models = self::CarsSearch($params);
        $query =$models->search($params);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'id' => 'cat_code',
        ]);
        $provider->pagination=false;
        return $provider;
    }

    public static function Models($params)
    {
        $models = self::ModelsSearch($params);
        $models->load($params);
        $query = $models->search($params);
        $query->andFilterWhere(['like', 'region', $models->region])
            ->andFilterWhere(['like', 'family', $models->family])
            ->andFilterWhere(['like', 'from', $models->from])
        ;
        $provider = new ActiveDataProvider([
            'query' => $query ,
            'pagination' =>false,
        ]);
        return $provider;
    }
    public static function Catalogs($params)
    {
        $models = self::CatalogsSearch($params);

        $query=$models->search($params);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);
        return $provider;
    }
    public static function Info($params)
    {
        $info = self::InfoSearch($params);
        $query = $info->search($params);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);
        return $provider;
    }
    public static function Catalog($params)
    {
        $models = self::CatalogSearch($params);
        $query =$models->search($params);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);
        return $provider;
    }

    public static function SubCatalog($params)
    {
        $models = self::SubCatalogSearch($params);
        $query =$models->search($params);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);
        return $provider;
    }
    public static function Parts($params)
    {
        $models = self::PartsSearch($params);
        $query =$models->search($params)
//        $query =parent::find()
            ->distinct()
            ->where('cat_folder=:cat_folder',[':cat_folder'=>$params['cat_folder']])
            ->andWhere('sect=:sect',[':sect'=>$params['sect']])
            ->andWhere('sub_sect=:sub_sect',[':sub_sect'=>$params['sub_sect']])
        ;

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);
        return $provider;
    }
}