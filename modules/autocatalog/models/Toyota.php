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
        $query
            ->andWhere('region=:region', [':region' => $params['region']])
            ->groupBy('cat_name')
            ->addSelect('*,cat_name,min(`from`) as \'from\',max(`to`) \'to\'')
            ->orderBy('from desc');
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $provider;
    }

    public static function Catalogs($params)
    {
        $models = self::CatalogsSearch($params);

        $query = $models->search($params);
        $query
//            ->distinct()
            ->where('cat_code=:cat_code', [':cat_code' => $params['cat_code']])
            ->andWhere('region=:region', [':region' => $params['region']])//            ->andWhere("value<>''")
        ;
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
        $params['option'] = (empty($params['option'])) ? '' : $params['option'];
        $option = str_replace(' ', '', str_replace('|', '', $params['option']));
        $query->select(['region', 'cat_code', 'cat_folder', 'option', 'model_code']);
        $query->andWhere('option=:option', [':option' => $option]);
        $query->andWhere('region=:region', [':region' => $params['region']]);
        $query->andWhere('cat_code=:cat_code', [':cat_code' => $params['cat_code']]);
        $query->distinct();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

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
//        $option = implode('', $params['post']);
        $models = self::CatalogSearch($params);
        $query = $models->search($params);
        $query->groupby('name');
        $query->where('cat_code=:cat_code',
            [':cat_code' => $params['cat_code']]);
        $query->andWhere('cat_folder=:cat_folder',
            [':cat_folder' => $params['cat_folder']]);
//            ->andWhere('option=:option', [':option' => $option]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $provider;
    }

    public static function SubCatalog($params)
    {
        $option = (empty($params['option'])) ? '' : $params['option'];
        $option = str_replace(' ', '',str_replace('|', '', $option));
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
            ->andWhere('option=:option', [':option' => $option])
            ->andWhere('cat_folder=:cat_folder', [':cat_folder' => $params['cat_folder']])
            ->andWhere('region=:region',[':region'=>$params['region']]);

        return $provider;
    }

    public static function Parts($params)
    {
//var_dump($params);die;
        $info = self::InfoSearch($params);
        $query = $info->search($params)
            ->andWhere(['cat_folder'=>$params['cat_folder']]);
        $value=$query->all();
//        var_dump($value);die;
        $models = self::PartsSearch($params);
        $query = $models->search($params)
            ->distinct()
            ->andWhere('cat_code=:cat_code', [':cat_code' => $params['cat_code']])
            ->andWhere('region=:region', [':region' => $params['region']])
            ->andWhere('cat_folder=:cat_folder', [':cat_folder' => $params['cat_folder']])
            ->andWhere('sub_sect=:sub_sect', [':sub_sect' => $params['sub_sect']])
            ->andWhere(['between','from',$value[0]->attributes['from'],$value[0]->attributes['to']])
            ->andWhere(['sysopt'=>$value[0]->attributes['sysopt']])
            ->andWhere(['compl_code'=>$value[0]->attributes['compl_code']])





        ;

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $provider;
    }

    public static function Vin($params)
    {

        if (strpos($params['vin'], '-') == 0) {
            $models = self::VinSearch($params);
            $query = $models->search($params);
            $query->andWhere("vin8<>''")
                ->andWhere("vin8 = SUBSTRING('" . $params['vin'] . "', 1, LENGTH(vin8))");
        } else {
            $models = self::FrameSearch($params);
            $query = $models->search($params);
        }


        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $provider;
    }

    public static function FrameSearch($params)
    {
        $models = new FrameSearch();
        return $models;
    }
    public static function Frame($params)
    {
        $query = new ActiveRecord();
        $frame = substr($params['vin'], 0, 5);
        $serial = substr($params['vin'], 6, 7);
        $query
            ->find()
            ->select('*')
            ->from('v_frame')
            ->where('frame_code=:frame_code and serial_number=:serial_number', [':frame_code' => $frame, ':serial_number' => $serial])
            ->limit(1);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $provider;
    }

    public static function Images($params)
    {
        $models = self::ImagesSearch($params);
        $query = $models->search($params);
        $query
            ->Where('cat_code=:cat_code', [':cat_code' => $params['cat_code']])
            ->andWhere('sub_sect=:sub_sect', [':sub_sect' => $params['sub_sect']])
            ->andWhere('cat_folder=:cat_folder', [':cat_folder' => $params['cat_folder']])
            ->andWhere('region=:region', [':region' => $params['region']])
            ->groupBy('pic_code')
            ->orderBy('page');

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