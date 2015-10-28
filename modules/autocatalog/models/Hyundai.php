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
        $query->andFilterWhere(['like','region',$params['region']])
        ->groupBy('family')
        ->addSelect(['*', 'region'=>'concat(\''.$params['region'].'\')']);
        $provider->pagination=false;
        return $provider;
    }

    public static function Models($params)
    {
        $models = self::ModelsSearch($params);
        $models->load($params);
        $query = $models->search($params);
//        var_dump($params);die;
        $query->andFilterWhere(['like', 'region', $models->region])
            ->andFilterWhere(['like', 'family', $models->family])
            ->andFilterWhere(['like', 'from', $models->from])
            ->groupBy(['cat_code','from','to'])
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
        $query->distinct()
        ->where('cat_code=:cat_code',[':cat_code'=>$params['cat_code']])
//        ->andWhere("value<>''")
        ;
        return $provider;
    }
    public static function Podbor($params)
    {
        $models = self::PodborSearch($params);
        $params['option'] = (empty($params['option'])) ? '||||||' : base64_decode($params['option']);
//        $params['option']=(empty($params['option']))?'||||||':$params['option'];
        $query=$models->search($params);
        $query->select(['cat_code','cat_folder']);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);
        $option=explode('|',$params['option']);
//        var_dump($option);die;
//        $query->andWhere("f01=:f01 or f01=''",[':f01'=>$option[0]]);
//        $query->andWhere("f02=:f02 or f02=''",[':f02'=>$option[1]]);
//        $query->andWhere("f03=:f03 or f03=''",[':f03'=>$option[2]]);
//        $query->andWhere("f04=:f04 or f04=''",[':f04'=>$option[3]]);
//        $query->andWhere("f05=:f05 or f05=''",[':f05'=>$option[4]]);
        $query->andWhere("cat_code=:cat_code",[':cat_code'=>$params['cat_code']]);
//        if(!empty($params['family'])){$query->andWhere('family=:family',[':family'=>$params['family']]);}
//        if(!empty($params['year'])){$query->andWhere($params['year'].' between from_year and to_year')->andWhere(['type_code'=>'03']);}
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
        $params['option']=implode('|',$params['post']);
        $models = self::CatalogSearch($params);
        $query =$models->search($params);
        $query ->where('cat_code=:cat_code',
            [':cat_code'=>$params['cat_code']])->distinct();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
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
        $option=explode('|',$params['option']);
        $models = self::SubCatalogSearch($params);
        $query =$models->search($params);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);
        $query->select([
            'cat_folder',
            'img','name',
            'cat_code',
            'sect',
            'url',
            'compatibility',
        ])            ->where('cat_code=:cat_code',[':cat_code'=>$params['cat_code']])
            ->andWhere('sect=:sect',[':sect'=>$params['sect']])
            ->andWhere('cat_folder=:cat_folder',[':cat_folder'=>$params['cat_folder']])
            ->groupBy(['sector']);
//        $query->distinct();
        $query->andWhere("f01=:f01 or f01=''",[':f01'=>$option[0]]);
        $query->andWhere("f02=:f02 or f02=''",[':f02'=>$option[1]]);
        $query->andWhere("f03=:f03 or f03=''",[':f03'=>$option[2]]);
        $query->andWhere("f04=:f04 or f04=''",[':f04'=>$option[3]]);
        $query->andWhere("f05=:f05 or f05=''",[':f05'=>$option[4]]);
//        $query->orWhere('f02=:f02',[':f02'=>'']);
        return $provider;
    }
    public static function Parts($params)
    {
        $option=explode('|',$params['option']);
//        var_dump($option);die;
        $models = self::PartsSearch($params);
        $query =$models->search($params)
//        $query =parent::find()
            ->distinct()
            ->andWhere('cat_folder=:cat_folder',[':cat_folder'=>$params['cat_folder']])
            ->andWhere('sect=:sect',[':sect'=>$params['sect']])
            ->andWhere('sector=:sector',[':sector'=>substr($params['sub_sect'],0,-2)])
//            ->andWhere('sub_sect=:sub_sect',[':sub_sect'=>$params['sub_sect']]);
        ;
//        ->groupBy(['number']);
//        $query->andWhere("f01=:f01 or f01=''",[':f01'=>$option[0]]);
//        $query->andWhere("f02=:f02 or f02=''",[':f02'=>$option[1]]);
//        $query->andWhere("f03=:f03 or f03=''",[':f03'=>$option[2]]);
//        $query->andWhere("f04=:f04 or f04=''",[':f04'=>$option[3]]);
//        $query->andWhere("f05=:f05 or f05=''",[':f05'=>$option[4]]);


        ;

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);
        return $provider;
    }
    public static function Vin($params)
    {
//        var_dump($params);die;
        $models = self::VinSearch($params);
        $query =$models->search($params);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);
        $query->andWhere('vin=:vin',[':vin'=>$params['vin']]);

        return $provider;
    }
    public static function Images($params)
    {
//        var_dump(substr($params['sub_sect'],0,-2),$params['cat_folder']);die;
        $models = self::ImagesSearch($params);
        $query =$models->search($params);
        $query
            ->groupBy(['page'])
        ->Where('cat_folder=:cat_folder',[':cat_folder'=>$params['cat_folder']])
//        ->andWhere('sub_sect=:sub_sect',[':sub_sect'=>$params['sub_sect']])
        ->andWhere('sect=:sect',[':sect'=>$params['sect']])
        ->andWhere('sector=:sector',[':sector'=>substr($params['sub_sect'],0,-2)])
//        ->andWhere('region=:region',[':region'=>$params['region']])
        ;
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);

        return $provider;
    }
    public static function Regions($params)
    {
        $models = self::RegionsSearch($params);
        $query =$models->search($params);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);

        return $provider;
    }
}