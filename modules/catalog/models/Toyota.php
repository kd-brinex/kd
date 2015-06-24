<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 16.06.15
 * Time: 14:22
 */
namespace app\modules\catalog\models;


use yii\data\ActiveDataProvider;
use yii\db\Connection;
use yii\helpers\Url;


class Toyota
{

    public function search($params)
        /*
         * Список всех моделей
         */
    {
        $query = new ToyotaQuery();

        $query->select('*')
            ->from('shamei');
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        return $dataProvider;

    }

    public function searchVin($params)
        /*
         * Поиск модификации по ВИНу
         */
    {
        //        $query = parent::find()->andWhere(['catalog_code'=>'116520']);
        $query = new ToyotaQuery();
        $vin = $params['vin'];
        $query ->select(['johokt.catalog',
                "get_vdate_frameno(johokt.catalog, johokt.frame, SUBSTRING('" . $vin . "',-7)) vdate"
                , 'shamei.f1'
                , 'shamei.model_name'
                , 'shamei.catalog_code'
                , 'shamei.models_codes'
                , 'johokt.model_code'
                , 'johokt.compl_code'
                , 'shamei.opt'
                , 'johokt.sysopt'
                , 'shamei.prod_start'
                , 'shamei.prod_end'
            ]
        )
            ->from ('shamei')
            ->leftJoin('johokt', '(`shamei`.`catalog` = `johokt`.`catalog`) and (`shamei`.`catalog_code` = `johokt`.`catalog_code`)')
            ->andWhere("vin8<>''")
            ->andWhere("vin8 = SUBSTRING('" . $vin . "', 1, LENGTH(vin8))");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);
//        var_dump($dataProvider);die;
        return $dataProvider;

    }

    public function searchFrame($params)
        /*
         * поиск модификации по фрэйму
         */
    {

        $query = new ToyotaQuery();
        $query ->select([
        'johokt.catalog',
            'johokt.catalog_code',
            'johokt.model_code',
            'johokt.prod_start',
            'johokt.prod_end',
            'johokt.frame',
        'johokt.sysopt',
            'johokt.compl_code',
            'johokt.engine1',
            'johokt.engine2',
            'johokt.body',
            'johokt.grade',
            'johokt.atm_mtm',
            'johokt.trans',
        'johokt.f1',
            'johokt.f2',
            'johokt.f3',
            'johokt.f4',
            'johokt.f5',
        'frames.catalog f_catalog',
            'frames.frame_code',
            'serial_group',
            'frames.serial_number',
            'frames.opt_n',
            'frames.ext',
        'frames.model2',
            'frames.vdate',
            'frames.color_trim_code',
            'frames.siyopt_code',
            'frames.opt',
            'shamei.models_codes',
        'shamei.prod_start sh_prod_start',
            'shamei.prod_end sh_prod_end',
            'shamei.rec_num',
            'shamei.date'])
        ->from('frames')
        # модификации авто
        ->leftJoin('johokt',"johokt.model_code = CONCAT(frames.frame_code, frames.ext, '-', SUBSTRING_INDEX(frames.model2, '(', 1))
                OR johokt.model_code = CONCAT(frames.frame_code, frames.ext, '-', REPLACE(REPLACE(frames.model2, '(', ''),')',''))")
        ->leftJoin('shamei',"shamei.catalog = johokt.catalog and shamei.catalog_code = johokt.catalog_code")
        ->andWhere("frames.catalog IN ('OV','DM')")
        ->andWhere("frames.frame_code = :frame",[':frame' => $params['frame']])
            ->andWhere("frames.serial_number = :number",[':number' => $params['number']])
            ->andWhere("frames.catalog = IF(johokt.catalog = 'JP', 'DM', 'OV')")
            ->andWhere("frames.vdate BETWEEN johokt.prod_start AND johokt.prod_end OR IFNULL(frames.vdate, '') = ''")
            ->andWhere("SUBSTRING(frames.siyopt_code, 1, 4) = johokt.sysopt OR IFNULL(johokt.sysopt, '') = '' OR IFNULL(frames.siyopt_code, '') = ''")
        ->distinct();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        var_dump($dataProvider);die;
        return $dataProvider;

    }

    public function searchModelSelect($params)
        /*
         * Список модификаций выбраной модели
         */
    {

        $query = new ToyotaQuery();
        $query->select(['catalog',
            'catalog_code',
            'model_code',
            'prod_start',
            'prod_end',
            'frame',
            'sysopt',
            'compl_code',
            'engine1',
            'engine2',
            'body',
            'grade',
            'atm_mtm',
            'trans',
            'f1',
            'f2',
            'f3',
            'f4',
            'f5'])
            ->distinct()
            ->from('johokt')
            ->andWhere(['catalog'=>$params['catalog'],'catalog_code'=>$params['catalog_code']]);
//        ->params([':catalog'=>$params['catalog'],':catalog_code'=>$params['catalog_code']]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        return $dataProvider;
    }
//    public function searchCatalog($params)
//        /*
//         * Список модификаций выбраной модели
//         */
//    {
//        $query = new AvQuery();
//        $query->select ('*')
//        ->from('models')
//        ->andWhere(['catalog' =>$params['catalog'],
//        'catalog_code' => $params['catalog_code'],
//        'compl_code' => $params['compl_code']]);
////        $query = self::findBySql($sql, $params);
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
////        var_dump($dataProvider);die;
//        return $dataProvider;
//    }

    public function searchCatalog($params)
    {
//        $translate = new Translate();
//        $translate->translation('figmei','desc_en','desc_ru');


        $query = new ToyotaQuery();
        $query->select(['emi.*', 'figmei.desc_en', 'figmei.desc_ru'])
            ->from('emi')
            ->leftJoin('figmei','figmei.catalog=emi.catalog and figmei.part_group = emi.part_group')
            ->andWhere('emi.catalog=:catalog and emi.catalog_code=:catalog_code',[':catalog'=>$params['catalog'],':catalog_code'=>$params['catalog_code']]);
//        if ($params['vdate']==''){
//            $mod_info=$this->getModelInfo($params);
//
//        }



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

    public function getUrlCatalog()
    {
        return Url::to(['catalog',
            'catalog_code' => $this->catalog_code,
            'catalog' => $this->catalog,
            'model_name' => $this->model_name,
            'compl_code' => $this->compl_code,
        ]);
    }

    public function getUrlModel()
    {
        return Url::to(['model',
            'catalog_code' => $this->catalog_code,
            'catalog' => $this->catalog,

        ]);
    }
    public function getModelInfo($params)
    {

        $connect = new Connection(ToyotaQuery::getConnectParam());
        $mod_info=$connect->createCommand("SELECT DISTINCT
    catalog,catalog_code,model_code,prod_start,prod_end,frame,sysopt,compl_code,engine1,engine2,body,grade,atm_mtm,trans,f1,f2,f3,f4,f5
FROM johokt
WHERE catalog = :catalog
    AND catalog_code = :catalog_code
    AND model_code = :model_code
    AND sysopt = :sysopt
    AND compl_code = :compl_code")->bindValues([
            ':catalog'=>$params['catalog'],
            ':catalog_code'=>$params['catalog_code'],
            ':model_code'=>$params['model_code'],
            ':sysopt'=>$params['sysopt'],
            ':compl_code'=>$params['compl_code'],
        ])->queryAll();
//var_dump($mod_info,$params,ToyotaQuery::getConnectParam());die;
//        $query = new ToyotaQuery();
//        $query->select(['catalog',
//            'catalog_code',
//            'model_code',
//            'prod_start',
//            'prod_end',
//            'frame',
//            'sysopt',
//            'compl_code',
//            'engine1',
//            'engine2',
//            'body',
//            'grade',
//            'atm_mtm',
//            'trans',
//            'f1',
//            'f2',
//            'f3',
//            'f4',
//            'f5'])
//            ->from('johokt')
//            ->where("catalog = '".$params['catalog']
//                ."' AND catalog_code = '".$params['catalog_code']
//                ."' AND model_code = '".$params['model_code']
//                ."' AND sysopt = '".$params['sysopt']
//                ."' AND compl_code = '".$params['compl_code']."'")
//        ->distinct();
//        var_dump($query);die;
        return $mod_info;
    }
}