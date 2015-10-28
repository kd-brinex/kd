<?php
namespace app\modules\autocatalog\models;

use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 9:50
 */
class Toyota extends CCar
{
    public static function Cars($params)
    {
        $models = self::CarsSearch($params);
        $query = $models->search($params);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'id' => 'cat_code',
        ]);
        $query->andWhere('region like :region', [':region' => '%' . $params['region'] . '%'])
            ->addSelect(['*', 'region' => 'concat(\'' . $params['region'] . '\')']);
        $provider->pagination = false;
        return $provider;
    }

    public static function Models($params)
    {
        $models = self::ModelsSearch($params);
        $models->load($params);
        $query = $models->search($params);
        $query->andFilterWhere(['like', 'region', $models->region])
//            ->andFilterWhere(['like', 'family', $models->family])
//            ->andFilterWhere(['like', 'from', $models->from])
            ->groupBy('cat_name')
            ->addSelect('*,cat_name,min(`from`) as \'from\',max(`to`) \'to\'')
            ->orderBy('from');;
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $provider;
    }

    public static function Catalogs($params)
    {
//        var_dump($params);die;
        $models = self::CatalogsSearch($params);

        $query = $models->search($params);
        $query
//            ->distinct()
            ->where('cat_code=:cat_code', [':cat_code' => $params['cat_code']])
            ->andWhere('region=:region', [':region' => $params['region']]);


//            ->andWhere("value<>''");
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $provider;
    }

    public static function Podbor($params)
    {
        $models = self::SubCatalogSearch($params);

        $query = $models->search($params);
        $params['option'] = (empty($params['option'])) ? '' : base64_decode($params['option']);
        $option =  str_replace('|','',$params['option']);
        var_dump($option);
        $query->select(['region','cat_code','cat_folder','option','model_code']);
        $query->andWhere('option=:option',[':option'=>$option]);
        $query->andWhere('cat_code=:cat_code', [':cat_code' => $params['cat_code']]);
        $query->distinct();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
//        if (!empty($params['cat_code'])) {
//            $query->andWhere('cat_code=:cat_code', [':cat_code' => $params['cat_code']]);
//        }
//        if (!empty($params['year'])) {
//            $query->andWhere($params['year'] . ' between from_year and to_year')->andWhere(['type_code' => '03']);
//        }
        return $provider;
    }

    public static function Info($params)
    {
        $info = self::InfoSearch($params);
        $query = $info->search($params);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $provider;
    }

    public static function Catalog($params)
    {
        $option = implode('', $params['post']);
        $models = self::CatalogSearch($params);
        $query = $models->search($params);
        $query->groupby('name');
        $query->where('cat_code=:cat_code',
            [':cat_code' => $params['cat_code']]) ;
        $query->andWhere('cat_folder=:cat_folder',
            [':cat_folder' => $params['cat_folder']]);
//            ->andWhere('option=:option', [':option' => $option]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $provider;
    }
//    public static function compatibility($source,$find)
//    {
//        $source=str_replace(';','|',$source);
//        return strpos($source,$find);
//    }
    public static function SubCatalog($params)
    {
        $option = (empty($params['option'])) ? '' : base64_decode($params['option']);
        $option =  str_replace('|','',$option);
        $models = self::SubCatalogSearch($params);
        $query = $models->search($params);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $query->select([
            'cat_folder',
            'img', 'name',
            'cat_code',
            'sect',
            'url',
//            'compatibility',
        ]);
        $query->distinct()
            ->where('cat_code=:cat_code', [':cat_code' => $params['cat_code']])
            ->andWhere('sect=:sect', [':sect' => $params['sect']])
        ->andWhere('option=:option',[':option'=>$option])
        ->andWhere('cat_folder=:cat_folder',[':cat_folder'=>$params['cat_folder']])
        ;
//            ->andWhere('cat_folder=:cat_folder',[':cat_folder'=>$params['cat_folder']]);
//        $query->andwhere
//        $query->andWhere("f01=:f01 or f01=''",[':f01'=>$option[0]]);
//        $query->andWhere("f02=:f02 or f02=''",[':f02'=>$option[1]]);
//        $query->andWhere("f03=:f03 or f03=''",[':f03'=>$option[2]]);
//        $query->andWhere("f04=:f04 or f04=''",[':f04'=>$option[3]]);
//        $query->andWhere("f05=:f05 or f05=''",[':f05'=>$option[4]]);
//        $query->orWhere('f02=:f02',[':f02'=>'']);
        return $provider;
    }

    public static function Parts($params)
    {
//        $option = explode('|', $params['option']);
//        var_dump($params);die;
        $models = self::PartsSearch($params);
        $query = $models->search($params)
//        $query =parent::find()
            ->distinct()
            ->andWhere('cat_code=:cat_code', [':cat_code' => $params['cat_code']])
//            ->andWhere('cat_code=:cat_folder',[':cat_folder'=>$params['cat_folder']])
            ->andWhere('region=:region', [':region' => $params['region']])
            ->andWhere('cat_folder=:cat_folder', [':cat_folder' => $params['cat_folder']])
            ->andWhere('sub_sect=:sub_sect', [':sub_sect' => $params['sub_sect']])
//        ->groupBy(['number']);
//        $query->andWhere("f01=:f01 or f01=''",[':f01'=>$option[0]]);
//        $query->andWhere("f02=:f02 or f02=''",[':f02'=>$option[1]]);
//        $query->andWhere("f03=:f03 or f03=''",[':f03'=>$option[2]]);
//        $query->andWhere("f04=:f04 or f04=''",[':f04'=>$option[3]]);
//        $query->andWhere("f05=:f05 or f05=''",[':f05'=>$option[4]]);


        ;

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $provider;
    }

    public static function Vin($params)
    {
//        var_dump($params);die;
        $models = self::VinSearch($params);
        $query = $models->search($params);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $query->andWhere("vin8<>''")
        ->andWhere("vin8 = SUBSTRING('" . $params['vin'] . "', 1, LENGTH(vin8))");
        return $provider;
    }

    public static function Images($params)
    {
//        var_dump($params);die;
        $models = self::ImagesSearch($params);
        $query = $models->search($params);
        $query
            ->distinct()
            ->Where('cat_code=:cat_code', [':cat_code' => $params['cat_code']])
            ->andWhere('sub_sect=:sub_sect', [':sub_sect' => $params['sub_sect']])
            ->andWhere('cat_folder=:cat_folder', [':cat_folder' => $params['cat_folder']])
            ->andWhere('region=:region', [':region' => $params['region']])
            ->orderBy('page');
//        ->andWhere('sect=:sect',[':sect'=>$params['sect']]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $provider;
    }

    public static function Regions($params)
    {
        $models = self::RegionsSearch($params);
        $query = $models->search($params);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $provider;

    }
}