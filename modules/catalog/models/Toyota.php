<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 16.06.15
 * Time: 14:22
 */
namespace app\modules\catalog\models;


use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\db\Connection;
use yii\helpers\Url;
use yii\base\Model;

class Toyota
{
    public function getMarka()
    {
        return "Toyota";
    }

    public function search($params)
        /*
         * Список всех моделей
         */
    {
//        var_dump($params);die;
        $query = new ToyotaQuery($params);
//        var_dump($query);die;
        $query->select('*')
            ->from('shamei')
            ->orderBy(['model_name' => 'asc', 'prod_start' => 'asc'])
            ->andFilterWhere(['catalog'=>$params['catalog']]);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
//        var_dump($dataProvider->models);die;
//        $model=$query->all();
//        foreach($model as $m){
//
//        }
//        var_dump($model);die;
        $dataProvider->pagination = false;
        $model = $dataProvider->models;


        $arr = [];

        foreach ($model as $item) {
            $arr[$item['model_name']][] = $item;
        }

        return $arr;

    }
public function searchVin2($params)
{
    $vin_list=$this->TOY_VIN_info($params);
    $pnc = (isset($params['pnc']) ? $params['pnc'] : "86841"); // PNC
    $res=[];
//    var_dump($vin_list);die;
    $res_VIN=$vin_list[0];
//    foreach($vin_list as $res_VIN) {
        $siyopt_code = $res_VIN['siyopt_code'];
        $model_code = $res_VIN['model_code'];
        $models_codes = $res_VIN['models_codes'];
        $catalog = $res_VIN['catalog'];
        $catalog_code = $res_VIN['catalog_code'];
        $compl_code = $res_VIN['compl_code'];
        $sysopt = $res_VIN['sysopt'];
        $vdate = $res_VIN['vdate'];

//----------------------------------------------
// ТУПО КУСОК  из Parts_Number_Translation_Results.php
//----------------------------------------------

        /* Запрос EPC Toyota */
//        $query = new ToyotaQuery($params);
        $vin = $params['vin'];
        $query = $this->searchModel($params);
        $query->addSelect(['j.catalog',
            "get_vdate_frameno(j.catalog, j.frame, SUBSTRING('" . $vin . "',-7)) vdate"
            , 's.f1'
            , 's.model_name'
            , 's.catalog_code'
            , 's.models_codes'
            , 'j.model_code'
            , 'j.compl_code'
            , 's.opt'
            , 'j.sysopt'])
            ->leftJoin('shamei s', '(s.catalog = j.catalog) and (s.catalog_code = j.catalog_code)')
            ->andWhere("vin8<>''")
            ->andWhere("vin8 = SUBSTRING('" . $vin . "', 1, LENGTH(vin8))")
        ->andWhere("j.catalog_code = :catalog_code",[":catalog_code"=>$catalog_code])
//        ->andWhere("get_vdate_frameno(j.catalog, j.frame, SUBSTRING('" . $vin . "',-7)) = :date",[":date"=>$vdate])
//        ->andWhere("s.prod_start <= :vdate",[":vdate"=>$vdate])
        ->andWhere(":vdate BETWEEN j.prod_start AND j.prod_end",[":vdate"=>(int) $vdate])
        ->andWhere("j.model_code = :model_code",[":model_code"=>$model_code])
        ->andWhere("j.compl_code = :compl_code",[":compl_code"=>$compl_code]);
    $dataProvider = new ActiveDataProvider([
        'query' => $query,

    ]);
    $dataProvider->pagination = false;
    $model = $dataProvider->models;
    $arr = [];
//var_dump($model);die;
    foreach ($model as $item) {
//var_dump($item);die;

        $arr[$this-> getMarka().' / '.$item['model_name'].' / ' .$item['engine_en']][$item['model_code']][] = $item;
//            $arr[$this->getMarka().' '.$item['model_name'].' '.$item['engine1'] . '_' . $item['engine_en']][$item['model_code']][] = $item;
    }
//var_dump($arr);die;
    return $arr;
//    }

}
    public function searchVin($params)
        /*
         * Поиск модификации по ВИНу
         */
    {
        //        $query = parent::find()->andWhere(['catalog_code'=>'116520']);
//        $query = new ToyotaQuery($params);
//        $query->setData($params);

        $vin = $params['vin'];
        $query = $this->searchModel($params);
        $query->addSelect(['j.catalog',
            "get_vdate_frameno(j.catalog, j.frame, SUBSTRING('" . $vin . "',-7)) vdate"
            , 's.f1'
            , 's.model_name'
            , 's.catalog_code'
            , 's.models_codes'
            , 'j.model_code'
            , 'j.compl_code'
            , 's.opt'
            , 'j.sysopt'])
            ->leftJoin('shamei s', '(s.catalog = j.catalog) and (s.catalog_code = j.catalog_code)')
            ->andWhere("vin8<>''")
            ->andWhere("vin8 = SUBSTRING('" . $vin . "', 1, LENGTH(vin8))");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);
        $dataProvider->pagination = false;
        $model = $dataProvider->models;
        $arr = [];
//var_dump($model);die;
        foreach ($model as $item) {
//var_dump($item);die;

            $arr[$this-> getMarka().' / '.$item['model_name'].' / ' .$item['engine_en']][$item['model_code']][] = $item;
//            $arr[$this->getMarka().' '.$item['model_name'].' '.$item['engine1'] . '_' . $item['engine_en']][$item['model_code']][] = $item;
        }
//var_dump($arr);die;
        return $arr;

    }

    public function searchFrame($params)
        /*
         * поиск модификации по фрэйму
         */
    {

        $query = $this->searchModel($params);
        $query->leftJoin('frames f',

            "j.model_code = CONCAT(f.frame_code, f.ext, '-', SUBSTRING_INDEX(f.model2, '(', 1)) OR j.model_code = CONCAT(f.frame_code, f.ext, '-', REPLACE(REPLACE(f.model2, '(', ''),')',''))")
            ->leftJoin('shamei s', '(s.catalog = j.catalog) and (s.catalog_code = j.catalog_code)')
            ->andWhere("f.catalog IN ('OV','DM')")
            ->andWhere("f.frame_code = :frame_code", [':frame_code' => $params['frame']])
            ->andWhere("f.serial_number = :number", [':number' => $params['number']])
            ->andWhere("f.catalog = IF(j.catalog = 'JP', 'DM', 'OV')")
            ->andWhere("f.vdate BETWEEN j.prod_start AND j.prod_end OR IFNULL(f.vdate, '') = ''")
            ->andWhere("SUBSTRING(f.siyopt_code, 1, 4) = j.sysopt OR IFNULL(j.sysopt, '') = '' OR IFNULL(f.siyopt_code, '') = ''")
            ->distinct()->addSelect('s.model_name');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        var_dump($dataProvider);die;
        $dataProvider->pagination = false;
        $model = $dataProvider->models;
        $arr = [];
//var_dump($model);die;
        foreach ($model as $item) {
//var_dump($item);die;

            $arr[$this->getMarka().' / '.$item['model_name'].' / ' . $item['engine_en']][$item['model_code']][] = $item;
//            $arr[$this->getMarka().' '.$item['engine1'] . '_' . $item['engine_en']][$item['model_code']][] = $item;
        }
//var_dump($arr);die;
        return $arr;

    }

    public function searchModel($params)
    {
        $query = new ToyotaQuery($params);
        $query->select(['j.catalog',
            'j.catalog_code',
            'j.model_code',
            'j.prod_start',
            'j.prod_end',
            "case j.prod_end
            when '999999' then concat(substring(j.prod_start,1,4),'/',substring(j.prod_start,5,2))
            else concat(substring(j.prod_start,1,4),'/',substring(j.prod_start,5,2),'-',substring(j.prod_end,1,4),'/',substring(j.prod_start,5,2))
            end prod",
            'j.frame',
            'j.sysopt',
            'j.compl_code',
            'j.engine1',
            'j.engine2',
            'j.body',
            'j.grade',
            'j.atm_mtm',
            'j.trans',
            'j.f1',
            'j.f2',
            'j.f3',
            'j.f4',
            'j.f5',
//            'tkm.desc_en tdesc',
//            'en.type ktype',
//            'en.sign',
            'en.desc_en engine_en',
            'body.desc_en body_en',
            'grade.desc_en grade_en',
            'tm.desc_en tm_en',
            'trans.desc_en trans_en',
            'f1.desc_en f1_en',
            'tkm_f1.desc_en f1_name',
            'f2.desc_en f2_en',
            'tkm_f2.desc_en f2_name',
            'f3.desc_en f3_en',
            'tkm_f3.desc_en f3_name',
            'f4.desc_en f4_en',
            'tkm_f4.desc_en f4_name',
//            'dest.desc_en dest_en',
        ])
            ->distinct()
            ->from('johokt j')
            ->leftJoin('kig en', "en.catalog = j.catalog and en.catalog_code=j.catalog_code and en.sign=j.engine1")
            ->leftJoin('kig body', "body.catalog = j.catalog and body.catalog_code=j.catalog_code and body.sign=j.body")
            ->leftJoin('kig grade', "grade.catalog = j.catalog and grade.catalog_code=j.catalog_code and grade.sign =j.grade")
            ->leftJoin('kig tm', "tm.catalog = j.catalog and tm.catalog_code=j.catalog_code and tm.sign=j.atm_mtm")
            ->leftJoin('kig trans', "trans.catalog = j.catalog and trans.catalog_code=j.catalog_code and trans.sign=j.trans")
            ->leftJoin('kig f1', "f1.catalog = j.catalog and f1.catalog_code=j.catalog_code and f1.sign=j.f1")
            ->leftJoin('tkm tkm_f1', 'tkm_f1.catalog = f1.catalog AND tkm_f1.catalog_code = f1.catalog_code AND tkm_f1.type = f1.type')
            ->leftJoin('kig f2', "f2.catalog = j.catalog and f2.catalog_code=j.catalog_code and f2.sign=j.f2")
            ->leftJoin('tkm tkm_f2', 'tkm_f2.catalog = f2.catalog AND tkm_f2.catalog_code = f2.catalog_code AND tkm_f2.type = f2.type')
            ->leftJoin('kig f3', "f3.catalog = j.catalog and f3.catalog_code=j.catalog_code and f3.sign=j.f3")
            ->leftJoin('tkm tkm_f3', 'tkm_f3.catalog = f3.catalog AND tkm_f3.catalog_code = f3.catalog_code AND tkm_f3.type = f3.type')
            ->leftJoin('kig f4', "f4.catalog = j.catalog and f4.catalog_code=j.catalog_code and f4.sign=j.f4")
            ->leftJoin('tkm tkm_f4', 'tkm_f4.catalog = f4.catalog AND tkm_f4.catalog_code = f4.catalog_code AND tkm_f4.type = f4.type');
        return $query;
    }
    public function searchModelAll($params)
    {
        $query = $this->searchModel($params);
        $query->andWhere('j.catalog = :catalog and j.catalog_code = :catalog_code',[':catalog' => $params['catalog'], ':catalog_code' => $params['catalog_code']])
            ->orderBy(['model_code' => 'asc', 'prod_start' => 'ask']);
        return $query;

    }
    public function searchModelOne($params)
    {
        $query=$this->searchModelAll($params);
//        var_dump($query->all());die;
        $query->andWhere('j.model_code = :model_code',[':model_code'=>$params['model_code']]);
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query
//        ]);
        return $query->all();
    }
    public function searchModelSelect($params)
        /*
         * Список модификаций выбраной модели
         */
    {

//        $query = $this->searchModel($params);
//        $query->andWhere(['j.catalog' => $params['catalog'], 'j.catalog_code' => $params['catalog_code']])
//            ->orderBy(['model_code' => 'asc', 'prod_start' => 'ask']);
        $query=$this->searchModelAll($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->pagination = false;
        $model = $dataProvider->models;
        $arr = [];

        foreach ($model as $item) {
            $arr[$this->getMarka().' / '.$item['model_name'].' / ' . $item['engine_en']][$item['model_code']][] = $item;
//            $arr[$this->getMarka().' '.$item['engine1'] . '_' . $item['engine_en']][$item['model_code']][] = $item;
        }
        return $arr;
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


        $query = new ToyotaQuery($params);
        $query->select(['emi.*', 'figmei.desc_en', 'figmei.desc_ru'])
            ->distinct()
            ->from('emi')
            ->leftJoin('figmei', 'figmei.catalog=emi.catalog and figmei.part_group = emi.part_group')
            ->leftJoin('johokt j', 'j.catalog=emi.catalog and j.catalog_code=emi.catalog_code')
            ->andWhere('emi.catalog=:catalog and emi.catalog_code=:catalog_code and j.model_code=:model_code', [
                ':catalog' => $params['catalog'],
                ':catalog_code' => $params['catalog_code'],
                ':model_code' => $params['model_code']
            ])
            ->orderBy(['model_code' => 'asc']);
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

    function getShameiInfo($params)
    {
        $connect = new Connection(ToyotaQuery::getConnectParam());
        $res = $connect->createCommand("SELECT * FROM shamei WHERE catalog = :catalog AND catalog_code = :catalog_code;")
            ->bindValues([
                ':catalog' => $params['catalog'],
                ':catalog_code' => $params['catalog_code'],])
            ->queryAll();
        return $res[0];
    }

    public function getModelInfo($params)
    {

        $connect = new Connection(ToyotaQuery::getConnectParam());
        $mod_info = $connect->createCommand("SELECT DISTINCT
    catalog,catalog_code,model_code,prod_start,prod_end,frame,sysopt,compl_code,engine1,engine2,body,grade,atm_mtm,trans,f1,f2,f3,f4,f5
FROM johokt
WHERE catalog = :catalog
    AND catalog_code = :catalog_code
    AND model_code = :model_code
    AND sysopt = :sysopt
    AND compl_code = :compl_code")->bindValues([
            ':catalog' => $params['catalog'],
            ':catalog_code' => $params['catalog_code'],
            ':model_code' => $params['model_code'],
            ':sysopt' => $params['sysopt'],
            ':compl_code' => $params['compl_code'],
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


    public function searchAlbum($params)
    {
        if (!empty($params['vdate']) or $params['vdate'] == '') {
            $mod_info = $this->getShameiInfo($params);
            $params = array_merge($params, $mod_info);
        }

//var_dump($mod_info);die;
        $query = "SELECT DISTINCT(compl_code) FROM johokt WHERE catalog = :catalog AND catalog_code = :catalog_code AND model_code = :model_code";
        $connect = new Connection(ToyotaQuery::getConnectParam());
        $compl_code = $connect->createCommand($query, [':catalog' => $params['catalog'], ':catalog_code' => $params['catalog_code'], ':model_code' => $params['model_code']])->queryAll();
        $compl_code_array = [];
        foreach ($compl_code as $cc) {
            $compl_code_array[] = $cc['compl_code'];
        }
//        var_dump($params,$compl_code);die;

        $images = new ToyotaQuery($params);
        $images->setUrlParams(['rec_num' => $params['rec_num']]);
//        $images->setUrlParams(['url_action'=>$images->getUrlAction('page')]);
        $images->select([
            'bzi.*',
            'kpt.compl_code',
            'inm.op1',
            'inm.op2',
            'inm.op3',
            'inm.ftype',
            'inm.desc_en',

        ])->from('bzi')
            ->leftJoin('kpt', 'kpt.catalog = bzi.catalog
        AND kpt.catalog_code = bzi.catalog_code
        AND kpt.ipic_code = bzi.ipic_code')
            ->join('LEFT OUTER JOIN', 'inm', 'inm.catalog = bzi.catalog
        AND inm.catalog_code = bzi.catalog_code
        AND inm.pic_desc_code = bzi.pic_desc_code
        AND inm.op1 = bzi.op1')
            ->andWhere(['kpt.compl_code' => $compl_code_array])
            ->andWhere('bzi.catalog=:catalog AND
  bzi.catalog_code=:catalog_code AND
  bzi.part_group=:part_group'
                , [
//                    ':compl_code'=>$compl_code_array,
                    ':catalog' => $params['catalog'],
                    ':catalog_code' => $params['catalog_code'],
                    ':part_group' => $params['part_group'],
                ]);
//var_dump($images->all());die;
        if (empty($params['vdate']) and !empty($mod_info)) {
//            # При поиске по дате модели в приделах даты модели
//            $images->andWhere("bzi.start_date<=:prod_end",[':prod_end'=>$mod_info[0]['prod_end']])
//                ->andWhere("bzi.end_date>=:prod_start",[':prod_start'=>$mod_info[0]['prod_start']]);
            $images
                ->andWhere("bzi.start_date<=:prod_end", [':prod_end' => ($mod_info['prod_end']==0)?'999999':$mod_info['prod_end'] ])
                ->andWhere("bzi.end_date>=:prod_start", [':prod_start' => $mod_info['prod_start']]);
//            var_dump($mod_info,$images->all());die;
        }
// 	отработать VIN --
        if (!empty($params['vdate'])) {

//        # При поиске по VIN - в приделах даты выпуска авто
            $images->andWhere(':vdate BETWEEN start_date AND end_date',
                [':vdate' => $params['vdate']]);
        }

//        $query="SELECT
//  bzi.*,
//  kpt.compl_code,
//  inm.op1, inm.op2, inm.op3, inm.ftype, inm.desc_en
//
//FROM
//  bzi #список всех подгрупп(иллюстраций)
//
//    # применяемость подгруппы запчастей
//	LEFT JOIN kpt
//	  ON kpt.catalog = bzi.catalog
//        AND kpt.catalog_code = bzi.catalog_code
//        AND kpt.ipic_code = bzi.ipic_code # маска применяемости
//
//	# описания к подгрупп(иллюстраций)
//	LEFT OUTER JOIN inm
//	  ON inm.catalog = bzi.catalog
//        AND inm.catalog_code = bzi.catalog_code
//        AND inm.pic_desc_code = bzi.pic_desc_code
//        AND inm.op1 = bzi.op1
//
//WHERE
//  bzi.catalog = :catalog
//  AND bzi.catalog_code = :catalog_code
//  AND bzi.part_group = :part_group
//
//  # применяемость подгруппы запчастей относительно типу комплектации выбранной модели авто
//  AND kpt.compl_code IN (
//            SELECT DISTINCT(compl_code) FROM johokt WHERE catalog = :catalog AND catalog_code = :catalog_code AND model_code = :model_code)
//";
//        $param=[
//            ':catalog'=>$params['catalog'],
//            ':catalog_code'=>$params['catalog_code'],
//            ':part_group'=>$params['part_group'],
//            ':model_code'=>$params['model_code'],
//        ];
//// 	отработать по дате модели, если не известна дата VIN --
//// 		пустых дат нету	, поэтому споконо берем эти поля
////			SELECT * FROM johokt WHERE IFNULL(prod_start,'') = ''
////			SELECT * FROM johokt WHERE IFNULL(prod_end,'') = ''
//// 	!empty($mod_info) - проверим вдруг модель пустая
//if (empty($params['vdate']) and !empty($mod_info)) {
//    $query .= "
//    # При поиске по дате модели в приделах даты модели
//AND (bzi.start_date <= :prod_end AND bzi.end_date >=:prod_start)";
//    $mod_info[':prod_end']=$params['prod_end'];
//    $mod_info[':prod_start']=$params['prod_start'];
//}
//// 	отработать VIN --
//if (!empty($params['vdate'])) {
//    $query .= "
//        # При поиске по VIN - в приделах даты выпуска авто
//        AND (:vdate BETWEEN start_date AND end_date)";
//    $param[':vdate']=$params['vdate'];
//}

//var_dump($picture);die;
        $dataProvider = new ActiveDataProvider([
            'query' => $images,
        ]);
        $dataProvider->pagination = false;
        return $dataProvider;

    }

    public function searchAlbumNext($params)
    {

        $images = new ToyotaQuery($params);

        $images->select('*')
            ->from('images')
            ->andWhere(['catalog = :catalog', 'disk = :disk_num', 'pic_code = :pic_code']);

        var_dump($images);
        die;

        $query = new ToyotaQuery($params);
        $query->select(['i.*'])
            ->from('img_nums i')
            ->leftJoin('hinmei h', 'h.catalog = i.catalog AND h.pnc = i.number')
            ->andWhere(['img_nums.catalog = :catalog',
                'disk = :$disk_num',
                'pic_code = :pic_code']);

    }

    public function searchPage($params)
    {
//        var_dump($params);die;
//        $query = "
//SELECT
//	shamei.*, # информация про модель
//	johokt.*  # информация про серюю модели
//  FROM johokt
//
//	JOIN shamei
//	  ON shamei.catalog = johokt.catalog
//	  AND shamei.catalog_code = johokt.catalog_code
//  WHERE johokt.catalog = '$catalog'
//    AND johokt.catalog_code = '$catalog_code'
//    AND johokt.model_code = '$model_code'
//    # условие выбранной sysopt модели
//    AND johokt.sysopt = '$sysopt'
//";
        $page = new ToyotaQuery($params);
        $page::$pref = 'old_';
//        $ulr_main = "vin=$vin&vdate=$vdate&siyopt_code=$siyopt_code&catalog=$catalog&catalog_code=$catalog_code&model_code=$model_code&sysopt=$sysopt&compl_code=$compl_code&";
        $ulr_main = '';
        $page->select([
            "imgn.*",
            "(imgn.x2-imgn.x1) width",
            "(imgn.y2-imgn.y1) height",
            "case (imgn.number_type)
            when '4' then '** Std Parts'
            else h.desc_en
            end desc_en",
            "hnb.*",
            "kpt.ipic_code",
            "case hnb.end_date
            when '999999' then concat(substring(hnb.start_date,1,4),'/',substring(hnb.start_date,5,2))
            else concat(substring(hnb.start_date,1,4),'/',substring(hnb.start_date,5,2),'-',substring(hnb.end_date,1,4),'/',substring(hnb.end_date,5,2))
            end prod",
        ])
//            ->from('kpt')
//            ->leftJoin('hnb','kpt.catalog=hnb.catalog and kpt.catalog_code=hnb.catalog_code')
//            ->leftJoin('hinmei h','hnb.pnc=h.pnc and h.catalog=kpt.catalog')
//            ->leftJoin('img_nums imgn','h.pnc = imgn.number and imgn.catalog=kpt.catalog')
//            ->andWhere('kpt.catalog=:catalog
//  and kpt.catalog_code=:catalog_code
//  and kpt.compl_code=:compl_code
//      and imgn.disk=:disk
//      and imgn.pic_code=:pic_code
//      and (hnb.field_type=1 AND substring(hnb.add_desc,1,6)=kpt.ipic_code or imgn.number_type=4);',
//                [':catalog' => $params['catalog'], ':disk' => $params['rec_num'], ':pic_code' => $params['pic_code'],':catalog_code'=> $params['catalog_code'],':compl_code' => $params['compl_code']]
//            );
            ->from('img_nums imgn')
//            ->leftJoin('img_nums imgn','img.disk=imgn.disk and img.pic_code=imgn.pic_code')
            ->leftJoin('hinmei h', 'h.catalog = imgn.catalog AND h.pnc = imgn.number')
            ->leftJoin('hnb', 'hnb.catalog=:catalog and hnb.catalog_code=:catalog_code and hnb.pnc=h.pnc', [':catalog' => $params['catalog'], ':catalog_code' => $params['catalog_code']])
            ->leftJoin('kpt', 'kpt.catalog=hnb.catalog and kpt.catalog_code=hnb.catalog_code and compl_code=:compl_code', [':compl_code' => $params['compl_code']])
            ->andWhere('imgn.catalog=:catalog and imgn.disk=:disk and imgn.pic_code=:pic_code and (hnb.field_type=1 and substring(hnb.add_desc,1,6)=kpt.ipic_code or imgn.number_type=4)',
//            ->andWhere('imgn.catalog=:catalog and imgn.disk=:disk and imgn.pic_code=:pic_code and hnb.field_type=1 and substring(hnb.add_desc,1,6)=kpt.ipic_code',
                [':catalog' => $params['catalog'], ':disk' => $params['rec_num'], ':pic_code' => $params['pic_code']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $page,
        ]);
        $dataProvider->pagination = false;
//        return $dataProvider;
        $model = $dataProvider->models;
        $arr = [];
        $arr['params'] = $page->url_params;
//var_dump($model);die;
        foreach ($model as $item) {
            $arr['models'][$item['number']][] = $item;
            $arr['labels'][$item['number']][$item['x1'] . 'x' . $item['y1']] = $item;
        }
        return $arr;

    }

    public function searchCharact($params)
    {
        $charact = new ToyotaQuery($params);
        $charact->select(['kig.*', 'tkm.desc_en'])
            ->from('kig')
            ->leftJoin('tkm', 'tkm.catalog = kig.catalog
        AND tkm.catalog_code = kig.catalog_code
        AND tkm.type = kig.type')
            ->andWhere("kig.catalog = :catalog and kig.catalog_code = :catalog_code", [':catalog' => $params['catalog'], ':catalog_code' => $params['catalog_code']]);
        return $charact->all();
    }

    public function getBreadcrumbs($params)
    {
        $b = [];
//        var_dump($params);die;

        $action = \Yii::$app->controller->action->id;
        if ($action == "index") {
            return $b;
        }
        $b['marka'] = ['label' => 'Toyota', 'url' => ['/toyota',
            'user_id'=>$params['user_id']
        ]];

        if ($action == "indexframe") {
            return $b;
        }

        if ($action == "indexvin") {
            return $b;
        }

        $b['model'] = ['label' => $params['model_name']];

        if ($action == 'model') {
            return $b;
        }

        $b['model'] = ['label' => $params['model_name'], 'url' => ['model',
            'catalog_code' => $params['catalog_code'],
            'catalog' => $params['catalog'],
            'model_name' => $params['model_name'],
            'user_id'=>$params['user_id']
        ]];
        $b['catalog'] = ['label' => $params['model_code'],];

        if ($action == "catalog") {
            return $b;
        }


//                var_dump($p);die;
        $b['catalog'] = ['label' => $params['model_code'], 'url' => ['catalog',
            'catalog_code' => $params['catalog_code'],
            'catalog' => $params['catalog'],
            'model_code' => $params['model_code'],
            'compl_code' => $params['compl_code'],
            'model_name' => $params['model_name'],
            'sysopt' => (isset($params['sysopt'])) ? $params['sysopt'] : '',
            'vdate' => (isset($params['vdate'])) ? $params['vdate'] : '',
            'user_id'=>$params['user_id']
        ]];
        $b['album'] = ['label' => $params['part_group'],];

        if ($action == "album") {
            return $b;
        }


        $b['album'] = ['label' => $params['part_group'], 'url' => ['album',
            'catalog_code' => $params['catalog_code'],
            'catalog' => $params['catalog'],
            'model_code' => $params['model_code'],
            'compl_code' => $params['compl_code'],
            'model_name' => $params['model_name'],
            'sysopt' => (isset($params['sysopt'])) ? $params['sysopt'] : '',
            'part_group' => (isset($params['part_group'])) ? $params['part_group'] : '',
            'vdate' => (isset($params['vdate'])) ? $params['vdate'] : '',
            'user_id'=>$params['user_id']
        ]];
        $b['page'] = ['label' => $params['compl_code'],];
        if ($action == "page") {
            return $b;
        }

//            $b[] = ['label' => $name, 'url' => ['post/edit', 'id' => 1]];

        return $b;
    }
    public function TOY_VIN_info($prms){
        // VIN - проверка
        $vin = trim($prms['vin']);
        if(empty($vin)){
            echo "_TOY_VIN_info - пустой VIN";
            return array();
        }
        // VIN - serial number
        $vin_sn = substr($vin, -7);

        // mysql_fetch_array($r_q, MYSQL_ASSOC) - будет глатать поля с одинаковыми именами, поэтму нужно предусмотреть для frames.catalog имя f_catalog
        $query = new ToyotaQuery($prms);
        $query->select('f.id,
        f.catalog f_catalog,
        f.frame_code,
        f.serial_group,
        f.serial_number,
        f.ext,
        f.model2,
        f.vdate,
        f.color_trim_code,
        f.siyopt_code,
        f.opt,
        j.*,
        s.*')
            ->from('johokt j')
            ->leftJoin('frames f','f.frame_code = j.frame')
            ->leftJoin('shamei s','s.catalog = j.catalog  AND s.catalog_code = j.catalog_code')
            ->where("j.vin8 LIKE CONCAT(SUBSTRING('$vin', 1, 8), '%')")
            ->andWhere("j.vin8 = SUBSTRING('$vin', 1, LENGTH(vin8))")
            ->andWhere("f.catalog = IF(j.catalog = 'JP', 'DM', 'OV')")
            ->andWhere("f.serial_number = '$vin_sn'")
            ->andWhere("CONCAT(f.frame_code, f.ext, '-', SUBSTRING_INDEX(f.model2, '(', 1)) = j.model_code")
            ->andWhere("(f.vdate BETWEEN j.prod_start AND j.prod_end or IFNULL(f.vdate, '') = '')")
            ->andWhere("(SUBSTRING(f.siyopt_code, 1, 4) = j.sysopt OR IFNULL(j.sysopt, '') = '' OR IFNULL(f.siyopt_code, '') = '')");
        if(isset($prms['catalog']))
            $query->andWhere("j.catalog = :catalog",[':catalog'=>$prms['catalog']]);

        $res = $query->all(); // выполним

        // просимофорим разработчика что с VIN получился не однозначный поиск
        // результатом должна быть только одна строчка, будем ловить если не так
//        if(isset($prms['catalog']) and count($res)>1) var_dump('БЛЯХА - почемуто больше чем одна запись, а хочется однозначно найти VIN!',$res);

        return $res;
    }
    /*~~~
     По идее VIN-запрос будет получать однозначные записи из johokt

         Проверка
            Немного грубовато с prod_start, но дата участвует в отборе frames.vdate BETWEEN johokt.prod_start AND johokt.prod_end
        SELECT catalog,catalog_code,vin8,model_code,prod_start,sysopt, COUNT(compl_code)
        FROM johokt
        WHERE vin8 <> '' # при VIN поиске будет всегда задано
        GROUP BY catalog,catalog_code,vin8,model_code,prod_start,sysopt
        HAVING COUNT(compl_code) > 1


        некоторые frames имеют в этом поле 1-й непонятный символ, например JTEHT05J202087962
        2013.06.01 = 2 символа в конце 1 части MHFFMRWK30K066683

    // 		а также
    //		OV ! ACV30 ! U160 ! U160344 ! 6 ! L ! CEANKA ! 200601 ! 4Q2FB45 ! 303WZ01E ! 0
    //		OV ! ACV30 ! U160 ! U160345 ! 3 ! L ! CEPNKA ! 200212 ! 8Q0FB13 ! 184W ! 0
    //
    // Обязательно SUBSTRING_INDEX(frames.model2, '(', 1)) - бо бывают вот такие frames
    //		DM ! CE121 ! 3001 ! 3001563 !  ! AEPNE(A) ! 200105 ! 040YG17 ! 186WZ07H ! 0
    //		DM ! CE121 ! 3001 ! 3001564 !  ! AEMNE(A) ! 200105 ! 040YG17 ! 186WZ07H ! 0
    // 		вот все возможные, для них johokt : CE121-AEMEE, CE121-AEMNE, CE121-AEPEE, CE121-AEPNE, CE121G-AWPNE
    //		потому предлагается тупо убирать все что в собках (A)
    ~~~*/

}