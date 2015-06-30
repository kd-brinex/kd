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
use yii\db\Connection;
use yii\helpers\Url;
use yii\base\Model;

class Toyota
{

    public function search($params)
        /*
         * Список всех моделей
         */
    {
        $query = new ToyotaQuery($params);
//        var_dump($query);die;
        $query->select('*')
            ->from('shamei')
            ->orderBy(['model_name'=>'asc','prod_start'=>'asc'])
        ->andFilterWhere($params);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
//        var_dump($dataProvider->models);die;
//        $model=$query->all();
//        foreach($model as $m){
//
//        }
//        var_dump($model);die;
        $dataProvider->pagination=false;
        $model=$dataProvider->models;


        $arr = array();

        foreach($model as $item)
        {
            $arr[$item['model_name']][] = $item ;
        }

        return $arr;

    }

    public function searchVin($params)
        /*
         * Поиск модификации по ВИНу
         */
    {
        //        $query = parent::find()->andWhere(['catalog_code'=>'116520']);
        $query = new ToyotaQuery($params);
//        $query->setData($params);
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
        return $dataProvider;

    }

    public function searchFrame($params)
        /*
         * поиск модификации по фрэйму
         */
    {

        $query = new ToyotaQuery($params);
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
        $charact=$this->searchCharact($params);

        $query = new ToyotaQuery($params);
//        $query::$pref='old_';
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
//            ->distinct()
            ->from('johokt j')
            ->leftJoin('kig en',"en.catalog = j.catalog and en.catalog_code=j.catalog_code and en.sign=j.engine1")
            ->leftJoin('kig body',"body.catalog = j.catalog and body.catalog_code=j.catalog_code and body.sign=j.body")
            ->leftJoin('kig grade',"grade.catalog = j.catalog and grade.catalog_code=j.catalog_code and grade.sign =j.grade")
            ->leftJoin('kig tm',"tm.catalog = j.catalog and tm.catalog_code=j.catalog_code and tm.sign=j.atm_mtm")
            ->leftJoin('kig trans',"trans.catalog = j.catalog and trans.catalog_code=j.catalog_code and trans.sign=j.trans")
            ->leftJoin('kig f1',"f1.catalog = j.catalog and f1.catalog_code=j.catalog_code and f1.sign=j.f1")
            ->leftJoin('tkm tkm_f1','tkm_f1.catalog = f1.catalog AND tkm_f1.catalog_code = f1.catalog_code AND tkm_f1.type = f1.type')
            ->leftJoin('kig f2',"f2.catalog = j.catalog and f2.catalog_code=j.catalog_code and f2.sign=j.f2")
            ->leftJoin('tkm tkm_f2','tkm_f2.catalog = f2.catalog AND tkm_f2.catalog_code = f2.catalog_code AND tkm_f2.type = f2.type')
            ->leftJoin('kig f3',"f3.catalog = j.catalog and f3.catalog_code=j.catalog_code and f3.sign=j.f3")
            ->leftJoin('tkm tkm_f3','tkm_f3.catalog = f3.catalog AND tkm_f3.catalog_code = f3.catalog_code AND tkm_f3.type = f3.type')
            ->leftJoin('kig f4',"f4.catalog = j.catalog and f4.catalog_code=j.catalog_code and f4.sign=j.f4")
            ->leftJoin('tkm tkm_f4','tkm_f4.catalog = f4.catalog AND tkm_f4.catalog_code = f4.catalog_code AND tkm_f4.type = f4.type')
//            ->leftJoin('kig dest',"dest.catalog = j.catalog and dest.catalog_code=j.catalog_code and dest.sign=j.f5")
//            ->leftJoin('kig dest',"dest.catalog = j.catalog and dest.catalog_code=j.catalog_code and find_in_set(sign,replace(f5,' ',','))>0")
//            ->leftJoin('tkm','tkm.catalog = en.catalog AND tkm.catalog_code = en.catalog_code AND tkm.type = en.type')
//            ->groupBy(['j.catalog','j.catalog_code','j.model_code','kig.desc_en'])
            ->andWhere(['j.catalog'=>$params['catalog'],'j.catalog_code'=>$params['catalog_code']])

//            ->andWhere('kig.catalog = :catalog and kig.catalog_code = :catalog_code',[':catalog'=>$params['catalog'],':catalog_code'=>$params['catalog_code']])
        ->orderBy(['model_code'=>'asc','prod_start'=>'ask'])            ;

//        SELECT *
//        from kig
//where catalog = 'EU' and catalog_code = '671440' and find_in_set(sign,replace('USA DSL IV',' ',','))>0
//        ->params([':catalog'=>$params['catalog'],':catalog_code'=>$params['catalog_code']]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->pagination=false;
        $model=$dataProvider->models;
        $arr = [];
//var_dump($model);die;
        foreach($model as $item)
        {
//var_dump($item);die;

            $arr[$item['engine1'].'_'.$item['engine_en']][$item['model_code']][]= $item ;
        }
//var_dump($arr);die;
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

            ->from('emi')
            ->leftJoin('figmei','figmei.catalog=emi.catalog and figmei.part_group = emi.part_group')
            ->andWhere('emi.catalog=:catalog and emi.catalog_code=:catalog_code', [
                ':catalog'=>$params['catalog'],
                ':catalog_code'=>$params['catalog_code'],
//                ':model_code'=>$params['model_code']
            ])
        ->orderBy(['model_code'=>'asc']);
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
    function getShameiInfo($params){
        $connect = new Connection(ToyotaQuery::getConnectParam());
        $res=$connect->createCommand("SELECT * FROM shamei WHERE catalog = :catalog AND catalog_code = :catalog_code;")
        ->bindValues([
                ':catalog'=>$params['catalog'],
                ':catalog_code'=>$params['catalog_code'],])
        ->queryAll();
        return $res[0];
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


    public function searchAlbum($params){
if(!empty($params['vdate']) or $params['vdate']==''){
    $mod_info=$this->getShameiInfo($params);
    $params=array_merge($params,$mod_info);
}

//var_dump($mod_info);die;
        $query="SELECT DISTINCT(compl_code) FROM johokt WHERE catalog = :catalog AND catalog_code = :catalog_code AND model_code = :model_code";
        $connect = new Connection(ToyotaQuery::getConnectParam());
        $compl_code=$connect->createCommand($query,[':catalog'=>$params['catalog'],':catalog_code'=>$params['catalog_code'],':model_code'=>$params['model_code']])->queryAll();
        $compl_code_array=[];
        foreach($compl_code as $cc) {
            $compl_code_array[] = $cc['compl_code'];
            }
//        var_dump($params,$compl_code);die;

        $images= new ToyotaQuery($params);
        $images->setUrlParams(['rec_num'=>$params['rec_num']]);
//        $images->setUrlParams(['url_action'=>$images->getUrlAction('page')]);
        $images->select([
           'bzi.*' ,
            'kpt.compl_code',
            'inm.op1',
            'inm.op2',
            'inm.op3',
            'inm.ftype',
            'inm.desc_en',

        ])->from('bzi')
        ->leftJoin('kpt','kpt.catalog = bzi.catalog
        AND kpt.catalog_code = bzi.catalog_code
        AND kpt.ipic_code = bzi.ipic_code')
        ->join('LEFT OUTER JOIN','inm','inm.catalog = bzi.catalog
        AND inm.catalog_code = bzi.catalog_code
        AND inm.pic_desc_code = bzi.pic_desc_code
        AND inm.op1 = bzi.op1')
            ->andWhere(['kpt.compl_code' => $compl_code_array])
            ->andWhere('bzi.catalog=:catalog AND
  bzi.catalog_code=:catalog_code AND
  bzi.part_group=:part_group'
  ,[
            ':catalog'=>$params['catalog'],
            ':catalog_code'=>$params['catalog_code'],
            ':part_group'=>$params['part_group'],
        ]);
//var_dump($mod_info);die;
        if (empty($params['vdate']) and !empty($mod_info)) {
//            # При поиске по дате модели в приделах даты модели
//            $images->andWhere("bzi.start_date<=:prod_end",[':prod_end'=>$mod_info[0]['prod_end']])
//                ->andWhere("bzi.end_date>=:prod_start",[':prod_start'=>$mod_info[0]['prod_start']]);
            $images->andWhere("bzi.start_date<=:prod_end",[':prod_end'=>intval($mod_info['prod_end'])])
                ->andWhere("bzi.end_date>=:prod_start",[':prod_start'=>intval($mod_info['prod_start'])]);
        }
// 	отработать VIN --
        if (!empty($params['vdate'])) {

//        # При поиске по VIN - в приделах даты выпуска авто
        $images->andWhere(':vdate BETWEEN start_date AND end_date',
            [':vdate'=>$params['vdate']]);
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
        $dataProvider->pagination=false;
    return $dataProvider;

    }
    public function searchAlbumNext($params)
    {

        $images= new ToyotaQuery($params);

        $images ->select('*')
            ->from('images')
            ->andWhere(['catalog = :catalog','disk = :disk_num','pic_code = :pic_code']);

var_dump($images);die;

        $query = new ToyotaQuery($params);
        $query->select([ 'img_nums.*',
	"CASE `number_type`
		WHEN '1' THEN
				CONCAT('<a href=\"Figure.php?".$ulr_main."part_group=', img_nums.number, '\">** Refer Fig<a/>')
		WHEN '4' THEN '** Std Part'
		ELSE hinmei.desc_en
	END desc_en",
	"img_nums.number AS pnc"])
            ->from('img_nums')
    ->leftJoin('hinmei','hinmei.catalog = img_nums.catalog AND hinmei.pnc = img_nums.number')
    ->andWhere(['img_nums.catalog = :catalog',
   'disk = :$disk_num',
    'pic_code = :pic_code']);

    }
    public function searchPage($params){
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
        $page= new ToyotaQuery($params);
        $page::$pref='old_';
//        $ulr_main = "vin=$vin&vdate=$vdate&siyopt_code=$siyopt_code&catalog=$catalog&catalog_code=$catalog_code&model_code=$model_code&sysopt=$sysopt&compl_code=$compl_code&";
        $ulr_main='';
        $page->select("*,CASE `imgn`.`number_type`
		WHEN '1' THEN	`imgn`.`number`
		WHEN '4' THEN '** Std Part'
		ELSE `h`.`desc_en`
	END desc_en")

            ->from('img_nums imgn')
//            ->leftJoin('img_nums imgn','img.disk=imgn.disk and img.pic_code=imgn.pic_code')
            ->leftJoin('hinmei h','h.catalog = imgn.catalog AND h.pnc = imgn.number')
//
            ->andWhere('imgn.catalog=:catalog and imgn.disk=:disk and imgn.pic_code=:pic_code',
                [':catalog'=>$params['catalog'],':disk'=>$params['rec_num'],':pic_code'=>$params['pic_code']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $page,
        ]);
        $dataProvider->pagination=false;
        return $dataProvider;

    }
    public function searchCharact($params){
        $charact = new ToyotaQuery($params);
        $charact->select(['kig.*','tkm.desc_en'])
            ->from('kig')
            ->leftJoin('tkm','tkm.catalog = kig.catalog
        AND tkm.catalog_code = kig.catalog_code
        AND tkm.type = kig.type')
            ->andWhere("kig.catalog = :catalog and kig.catalog_code = :catalog_code",[':catalog'=>$params['catalog'],':catalog_code'=>$params['catalog_code']]);
        return $charact->all();
    }
}