<?php
namespace app\modules\autocatalog\models;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 05.08.15
 * Time: 16:49
 */
use yii\db\Query;

class Hyundai extends CCar
{
    public function searchVIN($params)
    {
//        var_dump($params);die;
        $req_lang_code ='RU';
        $vin = $params['vin'];
        $query_options = new Query();
        $query_options->select('*')
            ->from('vin_options')
            ->where('vin = :vin', [':vin' => $vin]);

        $vin_options = $query_options->one($this->getDb()); // результат - 1 запись (даже при множесвенном MC_API->vin_vin_model)

        $query_models = new Query();
        $query_models->select('vv.vin, vv.vin_date, vv.*')
            ->from('vin_vin vv')
            ->leftJoin('vin_model vm', 'vv.vin_model_id = vm.vin_model_id')
            ->where('vin = :vin', [':vin' => $vin]);
        if (!empty($params['catalog_code'])) $query_models->andWhere("catalog_code = :catalog_code", [':catalog_code' => $params['catalog_code']]);
        $vin_models = $query_models->all($this->getDb()); // результат может быть множественным!!! 5XYZH4AG8BG000618


        if (empty($vin_models) and !empty($vin_options)) {

            $query_models->select('*')
                ->from('vin_model')
                ->where('vin_model_id = :vin_model_id', [':vin_model_id' => $vin_options['vin_model_id']]);
            if (!empty($params['catalog_code'])) $query_models->andWhere("catalog_code = :catalog_code", [':catalog_code' => $params['catalog_code']]);

            $vin_models = $query_models->all($this->getDb());
        }
        if (empty($vin_models)) {
            return false;
        }
//        var_dump($vin_models);die;
        $vin_res = [];
        foreach ($vin_models as $k => $vin_mod) {
            $vin_res[] = array(
                'model' => $vin_mod,
                'options' => $vin_options,
                'options_standart' => $this->vin_options_split_option($vin_options, 'option_standart'), // опции вина
                'options_optional' => $this->vin_options_split_option($vin_options, 'option_optional'),
                'options_add' => $this->vin_options_split_option($vin_options, 'option_add'),
            );
        }



        foreach ($vin_res as $v_res) {
            $vin_model = $v_res['model'];
            $vin_options = $v_res['options'];
            $v_res['vin_nation'] = $this->cat_nation(array('nation_code' => $vin_options['country'] . $vin_options['region'])); //расшифровка территориальных опций

            // в cats0_catalog - больше на 1 модель - MAL020PA01, но винов для нее - нету
            // SELECT * FROM vin_model WHERE catalogue_code = 'MAL020PA01';
            // SELECT * FROM vin_vin WHERE vin_model_id IN (19968, 26799) LIMIT 10;
            var_dump($vin_model);die;
            $catalog = $this->catalog(array('catalogue_code' => $vin_model['catalogue_code']));
            //$catalog = $catalog[0];
            $v_res['catalog'] = $catalog;
            $v_res['catalog_image'] = $this->get_catalog_image_path(array('cat_folder' => $catalog['cat_folder']));
            $cat_catalog = $this->cat_catalog(array('lang_code' => $req_lang_code, 'catalogue_code' => $vin_model['catalogue_code']));
            $v_res['cat_catalog'] = $cat_catalog;//[0];

            $v_res['vin_model_year'] = self::vin_model_year(array('vin' => $vin, 'production_date' => $vin_options['production_date']));

            //получить цвет
            $v_res['catalog_extcolor'] = array();
            if ($vin_options['exterior_color'] != '') {
                $v_res['catalog_extcolor'] = $this->cat_color_exterior(array('lang_code' => $req_lang_code, 'color_main_code' => $vin_options['exterior_color'], 'color_add_code' => $vin_model['group_type']));
            }
            $v_res['catalog_intcolor'] = array();
            if ($vin_options['interior_color'] != '') {
                $v_res['catalog_intcolor'] = $this->cat_color_interior(array('lang_code' => $req_lang_code, 'color_code' => $vin_options['interior_color']));
            }

            $catalog_ucctype = $this->cat_ucctype(array('lang_code' => $req_lang_code, 'catalogue_code' => $vin_model['catalogue_code'])); //виды харакетристик авто
            $v_res['catalog_ucctype'] = $catalog_ucctype;
            $v_res['catalog_ucctype_key'] = array_flip_2($catalog_ucctype, 'ucc_type'); //проиндексируем
            $v_res['catalog_ucc'] = $this->cat_ucc(array('lang_code' => $req_lang_code, 'catalogue_code' => $vin_model['catalogue_code'])); //харакетристики авто

            // для оптимизации получим сразу все описания options
            $vin_options_option_code = array_merge($v_res['options_standart'], $v_res['options_optional'], $v_res['options_add']);
            $v_res['cat_options_des'] = $this->cat_options(array('lang_code' => $req_lang_code, 'catalogue_code' => $vin_model['catalogue_code'], 'options' => $vin_options_option_code));

            $VIN_RES[] = $v_res;
        }
    }

    public function vin_options_split_option(array $vin_options, $fld_option = "")
    {
        if (empty($vin_options[$fld_option]))
            return [];

        // option_type =3, =4, =8 - нету
        $split_length = 4;  // option_type =2, =5(X7MEN41BP4A000004, X7MCF41GP4M010007), =6(KMCGB17EPXC000608)
        if (($vin_options['option_type'] == '1') or ($vin_options['option_type'] == '7')) //option_type =1, =7 (Y6LJM81BPAL207324)
            $split_length = 6;

        $res = str_split($vin_options[$fld_option], $split_length);
        //$res = array_map('trim', $res);  //нагуглил что для built-in function быстрее чем foreach!?
        $res_key = [];
        foreach ($res as $row) {
            $row = trim($row);
            $res_key[$row] = $row;
        }
        return $res_key;

    }

    public function cat_nation(array $prms)
    {
        if (empty($prms['nation_code'])) return '';
        $query = new Query();
        $query->select('*')
            ->from('cats0_nation')
            ->where('nation_code = :nation_code', [':nation_code' => $prms['nation_code']]);
//        $sql = "
//#EXPLAIN
//SELECT *
//  FROM cats0_nation
//  WHERE nation_code = '".$prms['nation_code']."';
//";

        $res = $query->one($this->getDb());

        return $res;
    }

    public function catalog(array $prms)
    {
        if (empty($prms['catalogue_code']))
            die(__METHOD__ . ": Не задано обязательный параметр - catalogue_code!");
$query = new Query();
        $query->select('*')
            ->from('catalog')
            ->where('catalogue_code = :catalogue_code',[':catalogue_code'=>$prms['catalogue_code']]);
//        $sql = "
//#EXPLAIN
//SELECT *
//FROM catalog
//WHERE catalogue_code = '" . $prms['catalogue_code'] . "'
//";

        // данные для указаного
        /*if(isset($prms['catalogue_code'])){
          $sql .= "WHERE catalogue_code = '".$prms['catalogue_code']."'";
        }*/

        $res = $query->one($this->getDb());
        return $res[0];
    }
    public function get_catalog_image_path(array $prms){
        if(empty($prms['cat_folder']))
            return "";

        return $this->image."Cutups/".$prms['cat_folder'].".rle";
    }
    public function cat_catalog(array $prms){
        if(empty($prms['lang_code']) or empty($prms['catalogue_code']))
            die(__METHOD__.": Не заданы обязательные параметры!");

        $lang_code = $prms['lang_code'];
        $q_lang_code = 'Q'.substr($lang_code,0,1);
        $query = new Query();

        $query->select('cc.*');
        $COALESCE1 = new Expression('COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code)');
        $COALESCE2 = new Expression('COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc)');
        $query->addSelect(['lang_code'=>$COALESCE1,'lex_desc'=>$COALESCE2])
            ->from('cats0_catalog cc')
            ->leftJoin('lex_lex lex_def',"lex_def.lang_code = 'EN' AND cat_name_lex_code = lex_def.lex_code")
            ->leftJoin('lex_lex lex_loc',"lex_loc.lang_code = 'RU' AND cat_name_lex_code = lex_loc.lex_code")
            ->leftJoin('lex_lex lex_qual_def',"lex_qual_def.lang_code = 'QE' AND cat_name_lex_code = lex_qual_def.lex_code")
            ->leftJoin('lex_lex lex_qual_loc',"lex_qual_loc.lang_code = 'QR' AND cat_name_lex_code = lex_qual_loc.lex_code")
            ->where('catalogue_code = :catalogue_code',[':catalogue_code'=>$prms['catalogue_code']]);
//        $sql = "
//#EXPLAIN
//SELECT
//  cats0_catalog.*,
//  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) lang_code,
//  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) lex_desc
//FROM
//  cats0_catalog
//  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND cat_name_lex_code = lex_def.lex_code
//  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND cat_name_lex_code = lex_loc.lex_code
//  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND cat_name_lex_code = lex_qual_def.lex_code
//  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND cat_name_lex_code = lex_qual_loc.lex_code
//WHERE catalogue_code = '".$prms['catalogue_code']."'
//";

        // данные для указаного
        /*if(isset($prms['catalogue_code'])){
          $sql .= "WHERE catalogue_code = '".$prms['catalogue_code']."'";
        }*/

        $res = $query->one($this->getDb());
        return $res;
    }
    static function vin_model_year(array $prms){
        $vmy_key = substr($prms['vin'], 10-1, 1); //vin model year = 10-й символ
        $production_date = trim($prms['production_date']);

        if(strlen($production_date) < 8) return 0;

        $model_year = 0;
        switch ($vmy_key){
            case "A":
                if ($production_date < '20000101'){
                    $model_year = 1980;
                } else {
                    $model_year = 2010;
                }
                break;
            case "B":
                if ($production_date < '20000101'){
                    $model_year = 1981;
                } else {
                    $model_year = 2011;
                }
                break;
            case "C":
                if ($production_date < '20000101'){
                    $model_year = 1982;
                } else {
                    $model_year = 2012;
                }
                break;
            case "D":
                if ($production_date < '20000101'){
                    $model_year = 1983;
                } else {
                    $model_year = 2013;
                }
                break;
            case "E":
                if ($production_date < '20000101'){
                    $model_year = 1984;
                } else {
                    $model_year = 2014;
                }
                break;
            case "F":
                if ($production_date < '20000101'){
                    $model_year = 1985;
                } else {
                    $model_year = 2015;
                }
                break;
            case "G":
                if ($production_date < '20000101'){
                    $model_year = 1986;
                } else {
                    $model_year = 2016;
                }
                break;
            case "H":
                if ($production_date < '20000101'){
                    $model_year = 1987;
                } else {
                    $model_year = 2017;
                }
                break;
            case "J":
                if ($production_date < '20000101'){
                    $model_year = 1988;
                } else {
                    $model_year = 2018;
                }
                break;
            case "K":
                if ($production_date < '20000101'){
                    $model_year = 1989;
                } else {
                    $model_year = 2019;
                }
                break;
            case "L":
                if ($production_date < '20000101'){
                    $model_year = 1990;
                } else {
                    $model_year = 2020;
                }
                break;
            case "M":
                $model_year = 1991;
                break;
            case "N":
                $model_year = 1992;
                break;
            case "P":
                $model_year = 1993;
                break;
            case "R":
                $model_year = 1994;
                break;
            case "S":
                $model_year = 1995;
                break;
            case "T":
                $model_year = 1996;
                break;
            case "V":
                $model_year = 1997;
                break;
            case "W":
                $model_year = 1998;
                break;
            case "X":
                $model_year = 1999;
                break;
            case "Y":
                $model_year = 2000;
                break;
            case "1":
                $model_year = 2001;
                break;
            case "2":
                $model_year = 2002;
                break;
            case "3":
                $model_year = 2003;
                break;
            case "4":
                $model_year = 2004;
                break;
            case "5":
                $model_year = 2005;
                break;
            case "6":
                $model_year = 2006;
                break;
            case "7":
                $model_year = 2007;
                break;
            case "8":
                $model_year = 2008;
                break;
            case "9":
                $model_year = 2009;
                break;
            default:
                $model_year = 0;
        }

        return $model_year;
    }
    public function cat_color_exterior(array $prms){
        if(empty($prms['lang_code'])){
            $prms['lang_code']='RU';}

        if(empty($prms['color_main_code'])) return '';

        $color_main_code = $prms['color_main_code'];
        $color_add_code = !empty($prms['color_add_code']) ? $prms['color_add_code'] : "";

        $lang_code = $prms['lang_code'];
        $q_lang_code = 'Q'.substr($lang_code,0,1);
        $query_from= new Query();
        $query_from->select('*')
        ->from('cats0_extcolor')->where('color_main_code = :color_main_code AND color_add_code = :color_add_code ',[':color_main_code'=>$color_main_code,':color_add_code' =>$color_add_code]);
        $query_from->union((new Query())->select('*')->from('cats0_extcolor')->where('color_main_code = :color_main_code AND (up_color_code = :up_color_code OR down_color_code = :down_color_code)',[':color_main_code'=>$color_main_code,':up_color_code'=>$color_main_code,':down_color_code'=>$color_main_code]));
        $query_from->union((new Query())->select('*')->from('cats0_extcolor')->where('color_main_code = :color_main_code',[':color_main_code'=>$color_main_code]));

        $query = new Query();
        $query->select('color_type,
  color_main_code, color_add_code,
  up_color_code,
  down_color_code');
        $COALESCE1 = new Expression('COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code)');
        $COALESCE2 = new Expression('COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc)');
        $COALESCE3 = new Expression('COALESCE(down_lex_qual_loc.lang_code, down_lex_loc.lang_code, down_lex_qual_def.lang_code, down_lex_def.lang_code)');
        $COALESCE4 = new Expression('COALESCE(down_lex_qual_loc.lex_desc, down_lex_loc.lex_desc, down_lex_qual_def.lex_desc, down_lex_def.lex_desc)');
        $query->addSelect([
            'up_lang_code'=>$COALESCE1,
            'up_lex_desc'=>$COALESCE2,
            'up_lang_code'=>$COALESCE3,
            'up_lex_desc'=>$COALESCE4,
        ]);
        $query->from(['color' => $query_from]);
        $query->join('LEFT OUTER','lex_lex lex_def',"lex_def.lang_code = 'EN' AND color.up_lex_code = lex_def.lex_code" );
        $query->join('LEFT OUTER','lex_lex lex_loc',"lex_loc.lang_code = 'RU' AND color.up_lex_code = lex_loc.lex_code" );
        $query->join('LEFT OUTER','lex_lex lex_qual_def',"lex_qual_def.lang_code = 'QE' AND color.up_lex_code = lex_qual_def.lex_code" );
        $query->join('LEFT OUTER','lex_lex lex_qual_loc',"lex_qual_loc.lang_code = 'QR' AND color.up_lex_code = lex_qual_loc.lex_code" );


        $query->join('LEFT OUTER','lex_lex down_lex_def',"down_lex_def.lang_code = 'EN' AND color.down_lex_code = down_lex_def.lex_code" );
        $query->join('LEFT OUTER','lex_lex down_lex_loc',"down_lex_loc.lang_code = 'RU' AND color.down_lex_code = down_lex_loc.lex_code" );
        $query->join('LEFT OUTER','lex_lex down_lex_qual_def',"down_lex_qual_def.lang_code = 'QE' AND color.down_lex_code = down_lex_qual_def.lex_code" );
        $query->join('LEFT OUTER','lex_lex down_lex_qual_loc',"down_lex_qual_loc.lang_code = 'QR' AND color.down_lex_code = down_lex_qual_loc.lex_code" );

//
//        $sql = "
//#EXPLAIN
//SELECT
//  color_type,
//  color_main_code, color_add_code,
//  #color_up
//  up_color_code,
//  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) up_lang_code,
//  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) up_lex_desc,
//  #color_down
//  down_color_code,
//  COALESCE(down_lex_qual_loc.lang_code, down_lex_loc.lang_code, down_lex_qual_def.lang_code, down_lex_def.lang_code) down_lang_code,
//  COALESCE(down_lex_qual_loc.lex_desc, down_lex_loc.lex_desc, down_lex_qual_def.lex_desc, down_lex_def.lex_desc) down_lex_desc
//FROM
//  #cats0_extcolor
//  (
//    #1 - подбираем по основному коду и коду модели
//    SELECT * FROM cats0_extcolor
//    WHERE color_main_code = '$color_main_code' AND color_add_code = '$color_add_code' AND '$color_add_code' <> ''
//    UNION ALL
//    #2 - подбираем по основному коду и коду верха-низа (KMHRB10APCU000122, KMHCF21F4RU000174)
//    SELECT * FROM cats0_extcolor
//    WHERE color_main_code = '$color_main_code' AND (up_color_code = '$color_main_code' OR down_color_code = '$color_main_code')
//    UNION ALL
//    #3 - ну хоть что нибудь (KMJFD37BP1K481402)
//    SELECT * FROM cats0_extcolor
//    WHERE color_main_code = '$color_main_code'
//    LIMIT 1
//  ) color
//  #color_up
//  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND color.up_lex_code = lex_def.lex_code
//  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND color.up_lex_code = lex_loc.lex_code
//  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND color.up_lex_code = lex_qual_def.lex_code
//  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND color.up_lex_code = lex_qual_loc.lex_code
//  #color_down
//  LEFT OUTER JOIN lex_lex down_lex_def ON down_lex_def.lang_code = 'EN' AND color.down_lex_code = down_lex_def.lex_code
//  LEFT OUTER JOIN lex_lex down_lex_loc ON down_lex_loc.lang_code = '$lang_code' AND color.down_lex_code = down_lex_loc.lex_code
//  LEFT OUTER JOIN lex_lex down_lex_qual_def ON down_lex_qual_def.lang_code = 'QE' AND color.down_lex_code = down_lex_qual_def.lex_code
//  LEFT OUTER JOIN lex_lex down_lex_qual_loc ON down_lex_qual_loc.lang_code = '$q_lang_code' AND color.down_lex_code = down_lex_qual_loc.lex_code
//";

        $res = $query->one($this->getDb());

        return $res;
    }
    public function cat_ucctype(array $prms){
        if(empty($prms['lang_code']) or empty($prms['catalogue_code']))
            die(__METHOD__.": Не заданы обязательные параметры!");

        $lang_code = $prms['lang_code'];
        $q_lang_code = 'Q'.substr($lang_code,0,1);
        $query = new Query();
        $COALESCE1 = new Expression('COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code)');
        $COALESCE2 = new Expression('COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc)');
        $query -> select('ucc_type');
        $query->addSelect([
            'lang_code'=>$COALESCE1,
            'lex_desc'=>$COALESCE2,
        ]);
        $query -> from('cats0_ucctype ucctype')
            ->join('LEFT OUTER','lex_lex lex_def',"lex_def.lang_code = 'EN' AND ucctype.lex_code = lex_def.lex_code")
            ->join('LEFT OUTER','lex_lex lex_loc',"lex_loc.lang_code = 'RU AND ucctype.lex_code = lex_loc.lex_code")
            ->join('LEFT OUTER','lex_lex lex_qual_def',"lex_qual_def.lang_code = 'QE' AND ucctype.lex_code = lex_qual_def.lex_code")
            ->join('LEFT OUTER','lex_lex lex_qual_loc',"lex_qual_loc.lang_code = 'QR' AND ucctype.lex_code = lex_qual_loc.lex_code");
        $query->where('catalogue_code=:catalogue_code',[':catalogue_code'=>$prms['catalogue_code']]);

//        $sql = "
//#EXPLAIN
//SELECT
//  ucc_type,
//  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) lang_code,
//  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) lex_desc
//FROM cats0_ucctype ucctype
//  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND ucctype.lex_code = lex_def.lex_code
//  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND ucctype.lex_code = lex_loc.lex_code
//  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND ucctype.lex_code = lex_qual_def.lex_code
//  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND ucctype.lex_code = lex_qual_loc.lex_code
//WHERE
//  catalogue_code = '".$prms['catalogue_code']."';
//";

        $res = $query->all($this->getDb());

        return $res;
    }
    public function cat_ucc(array $prms){
        if(empty($prms['lang_code']) or empty($prms['catalogue_code']))
            die(__METHOD__.": Не заданы обязательные параметры!");

        $lang_code = $prms['lang_code'];
        $q_lang_code = 'Q'.substr($lang_code,0,1);
        $COALESCE1 = new Expression('COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code)');
        $COALESCE2 = new Expression('COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc)');
        $COALESCE3 = new Expression('COALESCE(ucc_lex_qual_loc.lang_code, ucc_lex_loc.lang_code, ucc_lex_qual_def.lang_code, ucc_lex_def.lang_code)');
        $COALESCE4 = new Expression('COALESCE(ucc_lex_qual_loc.lex_desc, ucc_lex_loc.lex_desc, ucc_lex_qual_def.lex_desc, ucc_lex_def.lex_desc)');
        $query=new Query();
        $query->select('ucc_type,ucc');
        $query->addSelect([
            'lang_code'=>$COALESCE1,
            'lex_desc'=>$COALESCE2,
            'ucc_lang_code'=>$COALESCE3,
            'ucc_lex_desc'=>$COALESCE4,
        ]);
        $query->from('ats0_ucc ucc');
        $query->join('LEFT OUTER','lex_lex lex_def',"lex_def.lang_code = 'EN' AND ucc.type_lex_code = lex_def.lex_code");
        $query->join('LEFT OUTER','lex_lex lex_loc',"lex_loc.lang_code = 'RU' AND ucc.type_lex_code = lex_loc.lex_code");
        $query->join('LEFT OUTER','lex_lex lex_qual_def',"lex_qual_def.lang_code = 'QE' AND ucc.type_lex_code = lex_qual_def.lex_code");
        $query->join('LEFT OUTER','lex_lex lex_qual_loc',"lex_qual_loc.lang_code = 'QR' AND ucc.type_lex_code = lex_qual_loc.lex_code");
        $query->join('LEFT OUTER','lex_lex ucc_lex_def',"ucc_lex_def.lang_code = 'EN' AND ucc.lex_code1 = ucc_lex_def.lex_code");
        $query->join('LEFT OUTER','lex_lex ucc_lex_loc',"ucc_lex_loc.lang_code = 'RU' AND ucc.lex_code1 = ucc_lex_loc.lex_code");
        $query->join('LEFT OUTER','lex_lex ucc_lex_qual_def',"ucc_lex_qual_def.lang_code = 'QE' AND ucc.lex_code1 = ucc_lex_qual_def.lex_code");
        $query->join('LEFT OUTER','lex_lex ucc_lex_qual_loc',"ucc_lex_qual_loc.lang_code = 'QR' AND ucc.lex_code1 = ucc_lex_qual_loc.lex_code");
        $query->where('catalogue_code=:catalogue_code',['catalogue_code'=>$prms['catalogue_code']]);
//        $sql = "
//SELECT
//  ucc_type,
//  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) lang_code,
//  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) lex_desc,
//  ucc,
//  COALESCE(ucc_lex_qual_loc.lang_code, ucc_lex_loc.lang_code, ucc_lex_qual_def.lang_code, ucc_lex_def.lang_code) ucc_lang_code,
//  COALESCE(ucc_lex_qual_loc.lex_desc, ucc_lex_loc.lex_desc, ucc_lex_qual_def.lex_desc, ucc_lex_def.lex_desc) ucc_lex_desc
//FROM
//  cats0_ucc ucc
//  #ucc_type
//  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND ucc.type_lex_code = lex_def.lex_code
//  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND ucc.type_lex_code = lex_loc.lex_code
//  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND ucc.type_lex_code = lex_qual_def.lex_code
//  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND ucc.type_lex_code = lex_qual_loc.lex_code
//  #ucc
//  LEFT OUTER JOIN lex_lex ucc_lex_def ON ucc_lex_def.lang_code = 'EN' AND ucc.lex_code1 = ucc_lex_def.lex_code
//  LEFT OUTER JOIN lex_lex ucc_lex_loc ON ucc_lex_loc.lang_code = '$lang_code' AND ucc.lex_code1 = ucc_lex_loc.lex_code
//  LEFT OUTER JOIN lex_lex ucc_lex_qual_def ON ucc_lex_qual_def.lang_code = 'QE' AND ucc.lex_code1 = ucc_lex_qual_def.lex_code
//  LEFT OUTER JOIN lex_lex ucc_lex_qual_loc ON ucc_lex_qual_loc.lang_code = '$q_lang_code' AND ucc.lex_code1 = ucc_lex_qual_loc.lex_code
//  #lex_code2 - соркащенный ucc, не указывается с 2014
//WHERE
//  catalogue_code = '".$prms['catalogue_code']."';
//";

        $res = $query->all($this->getDb());
        if(empty($res))
            return [];

        // перегруппируем в массив для быстрого доступа
        $res_key = [];
        foreach($res as $row){
            $res_key[$row['ucc_type']][$row['ucc']] = $row;
        }

        return $res_key;
    }
    public function cat_options(array $prms){
        if(empty($prms['lang_code']) or empty($prms['catalogue_code']))
            die(__METHOD__.": Не заданы обязательные параметры!");

        $lang_code = $prms['lang_code'];
        $q_lang_code = 'Q'.substr($lang_code,0,1);

        $options = "";
        if(!empty($prms['options']))
            $options = "'".implode("','",$prms['options'])."'";
        $query=new Query();
        $query->select('option');
        $COALESCE1 = new Expression('COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code)');
        $COALESCE2 = new Expression('COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc)');
        $query->addSelect([
            'lang_code'=>$COALESCE1,
            'lex_desc'=>$COALESCE2
        ]);
        $query->from('cats0_options');
        $query->join('LEFT OUTER','lex_lex lex_def',"lex_def.lang_code = 'EN' AND cats0_options.lex_code1 = lex_def.lex_code");
        $query->join('LEFT OUTER','lex_lex lex_loc',"lex_loc.lang_code = 'RU' AND cats0_options.lex_code1 = lex_loc.lex_code");
        $query->join('LEFT OUTER','lex_lex lex_qual_def',"lex_qual_def.lang_code = 'QE' AND cats0_options.lex_code1 = lex_qual_def.lex_code");
        $query->join('LEFT OUTER','lex_lex lex_qual_loc',"lex_qual_loc.lang_code = 'QR' AND cats0_options.lex_code1 = lex_qual_loc.lex_code");
        $query->where('catalogue_code=:catalogue_code',[':catalogue_code'=>$prms['catalogue_code']]);
        if(!empty($options)) $query->andWhere(['option'=>$options]);
//        $sql = "
//SELECT
//  `option`,
//  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) lang_code,
//  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) lex_desc
//FROM cats0_options
//  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND cats0_options.lex_code1 = lex_def.lex_code
//  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND cats0_options.lex_code1 = lex_loc.lex_code
//  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND cats0_options.lex_code1 = lex_qual_def.lex_code
//  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND cats0_options.lex_code1 = lex_qual_loc.lex_code
//";


        // фильтрация
//        $where = array();
//        $where[] = "catalogue_code = '".$prms['catalogue_code']."'";
//        if(!empty($options)) $where[] = "`option` IN ($options)";

        $res = $query->all($this->getDb());
        $res = array_flip_2($res, 'option'); // проиндексируем

        return $res;
    }
}