<?php
/*-----------------------
 *	Класс - api TecDoc
 *	Всю нагрузку на БД должен взять он
 *	25.10.2014
 ----------------------*/

include_once("fun_array.php");	// дополниельные array-функции 

// Робота с БД
// оф. источник гласит http://www.php.net/manual/ru/function.mysql-connect.php
// mysql - расширение устарело, начиная с версии PHP 5.5.0, и будет удалено в будущем
// поэтому потихоньку пеерползаем на mysqli

class MC_API {
 	private $mysqli;	// mysqli

  // lex_lex.lang_code = mc_lexicon_h.lang_code => совместимость языков данных с системнымы языками 
  private $lex_lexicon = array('CH'=>'ZH-CHS', 'GE'=>'DE', 'JP'=>'JA', 'KR'=>'KO', 'SP'=>'ES',);
  private $lexicon_lex = array(); // lex_lexicon - наоброт

  
  public function __construct(array $prms) {
    $this->mysqli = new MySQLi_conn($prms);	//Коннект к БД MySQL
    
    $this->lexicon_lex = array_flip($this->lex_lexicon);
  }
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Каталог автомобилей
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function catalog(array $prms){   
    if(empty($prms['catalogue_code']))
      die(__METHOD__.": Не задано обязательный параметр - catalogue_code!");

    $sql = "
#EXPLAIN
SELECT * 
FROM catalog
WHERE catalogue_code = '".$prms['catalogue_code']."'
";
  
    // данные для указаного
    /*if(isset($prms['catalogue_code'])){
      $sql .= "WHERE catalogue_code = '".$prms['catalogue_code']."'";
    }*/
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    return $res[0];
  }  
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Каталог автомобилей: вся возможная инфа
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function catalog_cat_catalog(array $prms){   
    if(empty($prms['lang_code']))
      die(__METHOD__.": Не задано обязательный параметр - lang_code!");
      
    $lang_code = $prms['lang_code'];
    $q_lang_code = 'Q'.substr($lang_code,0,1);
    
    // порядок сортировки, можем изменять в вызове
    $sql_order = (!empty($prms['sql_order']) ? $prms['sql_order'] : "family, year_from DESC, cat_name");

    $sql = "
SELECT
  catalog.*, 
  cats0_catalog.production_from c0_production_from,
  cats0_catalog.production_to c0_production_to,
  cats0_catalog.vehicle_type_code,
  cats0_catalog.vehicle_type,
  cats0_catalog.year_from,
  cats0_catalog.year_to,
  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) lang_code,
  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) lex_desc  
FROM 
  # 09-14 => catalog меньше cats0_catalog на 1 запись
  catalog JOIN cats0_catalog ON catalog.catalogue_code = cats0_catalog.catalogue_code
  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND cat_name_lex_code = lex_def.lex_code
  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = 'RU' AND cat_name_lex_code = lex_loc.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND cat_name_lex_code = lex_qual_def.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = 'QR' AND cat_name_lex_code = lex_qual_loc.lex_code
";
  
    // фильтрация
    $where = array();
    if(isset($prms['catalogue_code'])) $where[] = "catalog.catalogue_code = '".$prms['catalogue_code']."'";    
    if(!empty($prms['family'])) $where[] = "family = '".$prms['family']."'";    
    if(!empty($prms['cat_year'])) $where[] = $prms['cat_year']." >= year_from AND (".$prms['cat_year']." <= year_to OR year_to = 0)";    
    if(!empty($prms['vehicle_type_code'])) $where[] = "vehicle_type_code = '".$prms['vehicle_type_code']."'";    
    if(!empty($prms['cat_region'])) $where[] = "data_regions LIKE '%".$prms['cat_region']."|%'";  // в конце '|'
    
    $res = $this->mysqli->query_prepare_exec($sql, $where, $sql_order);
    return $res;
  }  
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Каталог автомобилей: Модельный ряд
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function catalog_family(){   
    $sql = "
SELECT DISTINCT(family)
FROM catalog
ORDER BY family
";
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    return $res;
  }  
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Каталог автомобилей: регионы
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function catalog_regions(){   
    $sql = "
SELECT DISTINCT data_regions
FROM catalog
";
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    if (empty($res))
      return array();
    
    $regions = self::catalog_regions_array($res);
    return $regions;
  }  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Каталог автомобилей: регионы = DISTINCT + SORT
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function catalog_regions_array(array $regions){     
    $res = array();
    foreach($regions as $row){
      $data_regions = trim($row['data_regions'], '|'); //EUR|GEN|MES|CIS|
      $data_regions = explode('|', $data_regions);
      
      foreach($data_regions as $_r){
        $res[$_r] = $_r;  // DISTINCT
      }
    }
    
    sort($res); // SORT
    return $res;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Получить описание для региона по коду
  //  'system_text' - нужен обязательно
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function catalog_region_desc(array $prms){
    if(empty($prms['system_text']))
      die(__METHOD__.": Не задано обязательный параметр - system_text!");
    
    $translate_str = $prms['region']; //если вдруг нету перевода, то хотябы код вернуть
    switch ($prms['region']){
      case "AUS":
        $prms['lex_sys'] = "Australia"; //2353
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
      case "EUR":
        $prms['lex_sys'] = "Europe"; //2354
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
      case "GEN":
      case "GEX":
        $prms['lex_sys'] = "General"; //2203
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
      case "MES":
        $prms['lex_sys'] = "Middle East"; //3563
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
      case "HAC":
      case "CAN":
        $prms['lex_sys'] = "Canada"; //2136
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
      case "HMA":
      case "USA":
        $prms['lex_sys'] = "USA"; //2135
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
      case "HMJ":
        $prms['lex_sys'] = "Japan"; //2355
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
      case "HMT":
        $prms['lex_sys'] = "Turkey"; //2356
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
      case "HMI":
        $prms['lex_sys'] = "India"; //2620
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
      case "GEC":
        $prms['lex_sys'] = "Europe"; //2354
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        $prms['lex_sys'] = "General"; //2203
        $translate_str .= " & ".self::mc_lexicon_system_text_desc($prms);
        break;
      case "NAS":
        $prms['lex_sys'] = "North America"; //3565
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
      case "CHN":
        $prms['lex_sys'] = "China"; //3737
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
      case "CIS":
        $prms['lex_sys'] = "CIS"; //-1, а оно есть :)
        $translate_str = self::mc_lexicon_system_text_desc($prms);
        break;
    }
    
    return $translate_str;
  }
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Каталог автомобилей: Дополнительно
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_catalog(array $prms){   
    if(empty($prms['lang_code']) or empty($prms['catalogue_code']))
      die(__METHOD__.": Не заданы обязательные параметры!");

    $lang_code = $prms['lang_code'];
    $q_lang_code = 'Q'.substr($lang_code,0,1);

    $sql = "
#EXPLAIN
SELECT 
  cats0_catalog.*,
  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) lang_code,
  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) lex_desc  
FROM 
  cats0_catalog
  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND cat_name_lex_code = lex_def.lex_code
  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND cat_name_lex_code = lex_loc.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND cat_name_lex_code = lex_qual_def.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND cat_name_lex_code = lex_qual_loc.lex_code
WHERE catalogue_code = '".$prms['catalogue_code']."'
";
  
    // данные для указаного
    /*if(isset($prms['catalogue_code'])){
      $sql .= "WHERE catalogue_code = '".$prms['catalogue_code']."'";
    }*/
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    return $res[0];
  }  
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Exterior Colour
  //  Вытянем сразу все цвета
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_color_exterior(array $prms){
    if(empty($prms['lang_code']))
      die(__METHOD__.": Не задано обязательный параметр - lang_code!");
    
    if(empty($prms['color_main_code'])) return '';
    
    $color_main_code = $prms['color_main_code'];
    $color_add_code = !empty($prms['color_add_code']) ? $prms['color_add_code'] : "";
    
    $lang_code = $prms['lang_code'];
    $q_lang_code = 'Q'.substr($lang_code,0,1);
    
    $sql = "
#EXPLAIN
SELECT
  color_type,
  color_main_code, color_add_code,
  #color_up
  up_color_code,
  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) up_lang_code,
  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) up_lex_desc,   
  #color_down
  down_color_code,
  COALESCE(down_lex_qual_loc.lang_code, down_lex_loc.lang_code, down_lex_qual_def.lang_code, down_lex_def.lang_code) down_lang_code,
  COALESCE(down_lex_qual_loc.lex_desc, down_lex_loc.lex_desc, down_lex_qual_def.lex_desc, down_lex_def.lex_desc) down_lex_desc   
FROM 
  #cats0_extcolor
  (
    #1 - подбираем по основному коду и коду модели
    SELECT * FROM cats0_extcolor
    WHERE color_main_code = '$color_main_code' AND color_add_code = '$color_add_code' AND '$color_add_code' <> ''
    UNION ALL
    #2 - подбираем по основному коду и коду верха-низа (KMHRB10APCU000122, KMHCF21F4RU000174)
    SELECT * FROM cats0_extcolor
    WHERE color_main_code = '$color_main_code' AND (up_color_code = '$color_main_code' OR down_color_code = '$color_main_code')
    UNION ALL
    #3 - ну хоть что нибудь (KMJFD37BP1K481402)
    SELECT * FROM cats0_extcolor
    WHERE color_main_code = '$color_main_code'
    LIMIT 1
  ) color
  #color_up
  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND color.up_lex_code = lex_def.lex_code
  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND color.up_lex_code = lex_loc.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND color.up_lex_code = lex_qual_def.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND color.up_lex_code = lex_qual_loc.lex_code
  #color_down
  LEFT OUTER JOIN lex_lex down_lex_def ON down_lex_def.lang_code = 'EN' AND color.down_lex_code = down_lex_def.lex_code
  LEFT OUTER JOIN lex_lex down_lex_loc ON down_lex_loc.lang_code = '$lang_code' AND color.down_lex_code = down_lex_loc.lex_code
  LEFT OUTER JOIN lex_lex down_lex_qual_def ON down_lex_qual_def.lang_code = 'QE' AND color.down_lex_code = down_lex_qual_def.lex_code
  LEFT OUTER JOIN lex_lex down_lex_qual_loc ON down_lex_qual_loc.lang_code = '$q_lang_code' AND color.down_lex_code = down_lex_qual_loc.lex_code
";
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    if(!empty($res)) 
      $res = $res[0];
      
    return $res;
  }  
/*
KMHEU41FP7A301511 - Мain:NW + Mod:NF => O	NW	[	 ]	NW	NW, O+NW+__(-) остальные
XWEGH11HBD0000001 - Мain:AF + Mod:BHL , color_type = O
KMHRW20APCU014557 - Мain:LT + Mod:AP => O+LT+AP -> LIGHT MEDIUM GRAY
KMHRB10APCU000193 - Мain:LT + Mod:AP , color_type
KMHFB41B09A343714 - Мain:AJ + Mod:TG => O+AJ+[	 ] -> ARIZONA CREAM, O+AJ+NF(-)
XWEGH41DBE0000401 - Мain:AU + Mod:BHL => O+AU+BHL -> STERLING SILVER, O+AU+[	 ](-)
KMHRB10APCU000122 - Мain:SR + Mod:AP , color_type = T(+) ~ color_type = O(-)
KMHEM41B04A000223 - Мain:VL + Mod:*DA => O+VL+TQ -> ICY BLUE(?), T+VL+[	 ](-?)
KMHFC41B98A326924 - Мain:DY + Mod:TG => O+DY+NF -> SILKY BEIGE(?), T+DY+[	 ](-?)
KMFGA17CP2C157523 - Мain:PT + Mod:*UB => O+PT+[	 ] -> PAPYRUS WHITE, O+PT+EE(-)
KMHCF45G01U144372 - Мain:GG + Mod:*BA => O+GG+[	 ] -> *****, O+GG+__(-) остальные
*/  

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Interior Colour
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_color_interior(array $prms){
    if(empty($prms['lang_code']))
      die(__METHOD__.": Не задано обязательный параметр - lang_code!");
    
    if(empty($prms['color_code'])) return '';

    $lang_code = $prms['lang_code'];
    $q_lang_code = 'Q'.substr($lang_code,0,1);
    
    $sql = "
#EXPLAIN
SELECT 
  color_code,
  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) lang_code,
  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) lex_desc
FROM cats0_intcolor color
  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND color.lex_code = lex_def.lex_code
  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND color.lex_code = lex_loc.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND color.lex_code = lex_qual_def.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND color.lex_code = lex_qual_loc.lex_code
WHERE 
  color_code = '".$prms['color_code']."'
";
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    if(!empty($res)) 
      $res = $res[0];
      
    return $res;
  }  
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Расшифровка опций (копмлектаций)
  //  $prms['options'] - массив опций
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_options(array $prms){
    if(empty($prms['lang_code']) or empty($prms['catalogue_code']))
      die(__METHOD__.": Не заданы обязательные параметры!");
         
    $lang_code = $prms['lang_code'];
    $q_lang_code = 'Q'.substr($lang_code,0,1);
    
    $options = "";
    if(!empty($prms['options']))   
      $options = "'".implode("','",$prms['options'])."'";

    $sql = "
SELECT 
  `option`,
  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) lang_code,
  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) lex_desc
FROM cats0_options 
  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND cats0_options.lex_code1 = lex_def.lex_code
  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND cats0_options.lex_code1 = lex_loc.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND cats0_options.lex_code1 = lex_qual_def.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND cats0_options.lex_code1 = lex_qual_loc.lex_code
";

    
    // фильтрация
    $where = array();
    $where[] = "catalogue_code = '".$prms['catalogue_code']."'";    
    if(!empty($options)) $where[] = "`option` IN ($options)";    
    
    $res = $this->mysqli->query_prepare_exec($sql, $where); // результат может быть множественным  
    $res = array_flip_2($res, 'option'); // проиндексируем    

    return $res;
  }
/*
lex_code1 - достаточно
SELECT * FROM cats0_options WHERE lex_code1 <> lex_code2
*/
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Расшифровка Страны и Региона
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_nation(array $prms){
    if(empty($prms['nation_code'])) return '';
    
    $sql = "
#EXPLAIN
SELECT * 
  FROM cats0_nation
  WHERE nation_code = '".$prms['nation_code']."';
";
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    if(!empty($res)) 
      $res = $res[0];
      
    return $res;
  }  
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Каталог автомобилей: последовательность выпуска в годах
  //  05.04.2015
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_catalog_production_years(){
    $sql = "SELECT MIN(year_from) year_from, MAX(year_to) year_to FROM cats0_catalog ";
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    $res = $res[0];
    
    $period = self::cat_catalog_production_years_array($res['year_from'], $res['year_to']);
    
    return $period;
  }
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Каталог автомобилей: создать список годов выпуска от старшого к младшему
  //  05.04.2015
	//~~~~~~~~~~~~~~~~~~~~~~~~   
  public function cat_catalog_production_years_array($year_from=0, $year_to=0){
    $year_to = (int)$year_to;
    $year_from = (int)$year_from;
    
    if($year_to < $year_from)  //коррекция даты как у GEN7S40501, KEURCWR13
      $year_to = date('Y');
    
    //от старшого к младшему
    $period = array();
    while($year_to >= $year_from){
      $period[$year_to] = $year_to;
      $year_to--;
    }
    
    return $period;
  }


	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Каталог автомобилей: Тип автомобиля
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_catalog_vehicle_type(){
    $sql = "
SELECT DISTINCT vehicle_type_code, vehicle_type
FROM cats0_catalog
ORDER BY vehicle_type_code
";
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    $res = array_flip_2($res, 'vehicle_type_code'); // проиндексируем    
    return $res;
  }
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Виды характеристик модели авто
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_ucctype(array $prms){
    if(empty($prms['lang_code']) or empty($prms['catalogue_code']))
      die(__METHOD__.": Не заданы обязательные параметры!");

    $lang_code = $prms['lang_code'];
    $q_lang_code = 'Q'.substr($lang_code,0,1);
    
    $sql = "
#EXPLAIN
SELECT 
  ucc_type,
  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) lang_code,
  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) lex_desc
FROM cats0_ucctype ucctype
  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND ucctype.lex_code = lex_def.lex_code
  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND ucctype.lex_code = lex_loc.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND ucctype.lex_code = lex_qual_def.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND ucctype.lex_code = lex_qual_loc.lex_code
WHERE 
  catalogue_code = '".$prms['catalogue_code']."';
";
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    return $res;
  }
/* Они стандартные - 01, 02, 03, 04, 05, 06, DT, WT, 07
SELECT DISTINCT(ucc_type) FROM cats0_ucctype
*/
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Виды характеристик модели авто
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_ucc(array $prms){
    if(empty($prms['lang_code']) or empty($prms['catalogue_code']))
      die(__METHOD__.": Не заданы обязательные параметры!");

    $lang_code = $prms['lang_code'];
    $q_lang_code = 'Q'.substr($lang_code,0,1);
    
    $sql = "
SELECT
  ucc_type,
  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) lang_code,
  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) lex_desc,
  ucc,
  COALESCE(ucc_lex_qual_loc.lang_code, ucc_lex_loc.lang_code, ucc_lex_qual_def.lang_code, ucc_lex_def.lang_code) ucc_lang_code,
  COALESCE(ucc_lex_qual_loc.lex_desc, ucc_lex_loc.lex_desc, ucc_lex_qual_def.lex_desc, ucc_lex_def.lex_desc) ucc_lex_desc
FROM 
  cats0_ucc ucc
  #ucc_type
  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND ucc.type_lex_code = lex_def.lex_code
  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND ucc.type_lex_code = lex_loc.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND ucc.type_lex_code = lex_qual_def.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND ucc.type_lex_code = lex_qual_loc.lex_code
  #ucc
  LEFT OUTER JOIN lex_lex ucc_lex_def ON ucc_lex_def.lang_code = 'EN' AND ucc.lex_code1 = ucc_lex_def.lex_code
  LEFT OUTER JOIN lex_lex ucc_lex_loc ON ucc_lex_loc.lang_code = '$lang_code' AND ucc.lex_code1 = ucc_lex_loc.lex_code
  LEFT OUTER JOIN lex_lex ucc_lex_qual_def ON ucc_lex_qual_def.lang_code = 'QE' AND ucc.lex_code1 = ucc_lex_qual_def.lex_code
  LEFT OUTER JOIN lex_lex ucc_lex_qual_loc ON ucc_lex_qual_loc.lang_code = '$q_lang_code' AND ucc.lex_code1 = ucc_lex_qual_loc.lex_code
  #lex_code2 - соркащенный ucc, не указывается с 2014
WHERE 
  catalogue_code = '".$prms['catalogue_code']."';
";
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    if(empty($res))
      return array();
      
    // перегруппируем в массив для быстрого доступа
    $res_key = array();
    foreach($res as $row){
      $res_key[$row['ucc_type']][$row['ucc']] = $row;
    }
      
    return $res_key;
  }
/*
Раньше в lex_code2 был текстовый код опции - сокращенное название, в 2014 уже незадается
SELECT * FROM cats0_ucc WHERE lex_code1 <> lex_code2;
*/  
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Разбор автомобиля: Основные секции: список наименований рисунков основного раздела с секторами
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_dat_major_distinct_images(array $prms){
    if(empty($prms['cat_folder']) or empty($prms['major_sect']))
      return array();

    $sql = "
#EXPLAIN
SELECT DISTINCT(img_name)
FROM cats_dat_ref
  JOIN cats_map 
    ON cats_dat_ref.cat_folder = cats_map.cat_folder
    AND sector = ref
    AND ref_type = '3' #ссылка на sector(участок)
WHERE 
  cats_dat_ref.cat_folder = '".$prms['cat_folder']."'
  AND major_sect = '".$prms['major_sect']."'
ORDER BY
  CAST(SUBSTR(img_name,3) AS SIGNED) #организуем правильный порядок сортировки, убрав GI    
";
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    
    // наведем красоту, если будет тупеж с сортировкой в sql-запросе, то тут можно будет отсортировать
    $result = array(); 
    foreach($res as $row){
      $result[$row['img_name']] = $row['img_name'];
    }
    
    return $result;
  }
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Разбор автомобиля: Основные секции: рисунки основного раздела с секторами
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_dat_major_images(array $prms){
    if(empty($prms['cat_folder']) or empty($prms['major_images']))
      return array();
    
    $major_images = "'".implode("','",$prms['major_images'])."'";
      
    $sql = "
#EXPLAIN
SELECT *
FROM cats_dat_ref
  WHERE cat_folder = '".$prms['cat_folder']."'
  AND ref_type = '3'
  AND img_name IN ($major_images)
;
";
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    $res = array_group($res, 'img_name');
    return $res;
  }
/*
#проверить, все ли сектора попадают в рисунки
SELECT * 
FROM cats_map 
  LEFT OUTER JOIN cats_dat_ref
    ON cats_map.cat_folder = cats_dat_ref.cat_folder
    AND ref = sector
WHERE 
  ref_type = '3'
  AND cats_dat_ref.cat_folder IS NULL
LIMIT 10;
*/  


	//~~~~~~~~~~~~~~~~~~~~~~~~
  // !!! по БД, все сектора из cats_map присутвуют в cats_dat_ref.G[...], 
  //  однако для тех кто хочет выводить исключительно графикой, лучше бы проверить то что не попало
  //  
  //  $major_images = array_group($MC_API->cat_map_minor_section, 'sector');
  //  $sectors = $MC_API->cat_dat_major_images;
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_dat_major_images_sectors_check(array $major_images, array $sectors){
    $sector_keys = array_flip(array_keys($sectors));
    
    foreach($major_images as $image){ // перебираем GI1..GIn
      foreach($image as $row){  //  перебираем каждый ref рсиунка
        if(isset($sectors[$row['ref']])){
          unset($sector_keys[$row['ref']]); // повторная очистка уже очщиеннго не вызывает ошибку :)
        }
      }
    }
    
    return $sector_keys;
  }
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Разбор автомобиля: Основные секции разборки автомобиля
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_map_major_section(array $prms){   
    if(empty($prms['lang_code']) or empty($prms['cat_folder']))
      die(__METHOD__.": Не заданы обязательные параметры!");

    $lang_code = $prms['lang_code'];
    $q_lang_code = 'Q'.substr($lang_code,0,1);

    $sql = "
#EXPLAIN
SELECT 
  major_section.*,
  COALESCE(lex_qual_loc.lang_code, lex_loc.lang_code, lex_qual_def.lang_code, lex_def.lang_code) lang_code,
  COALESCE(lex_qual_loc.lex_desc, lex_loc.lex_desc, lex_qual_def.lex_desc, lex_def.lex_desc) lex_desc  
FROM 
  (
    #EXPLAIN
    SELECT DISTINCT major_sect, major_sect_lex_code
    FROM cats_map 
    WHERE cat_folder = '".$prms['cat_folder']."'
  ) major_section
  LEFT OUTER JOIN lex_lex lex_def ON lex_def.lang_code = 'EN' AND major_sect_lex_code = lex_def.lex_code
  LEFT OUTER JOIN lex_lex lex_loc ON lex_loc.lang_code = '$lang_code' AND major_sect_lex_code = lex_loc.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_def ON lex_qual_def.lang_code = 'QE' AND major_sect_lex_code = lex_qual_def.lex_code
  LEFT OUTER JOIN lex_lex lex_qual_loc ON lex_qual_loc.lang_code = '$q_lang_code' AND major_sect_lex_code = lex_qual_loc.lex_code
";
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    $res = array_flip_2($res, 'major_sect');  //проиндексируем
    return $res;
  }
/*
# список всех возможных основных разделов
SELECT DISTINCT major_sect, major_sect_lex_code, lex_def.lex_desc
FROM cats_map
JOIN lex_lex lex_def ON lex_def.lang_code = 'RU' AND major_sect_lex_code = lex_def.lex_code;

# подсчитать количество основных разделов в каталогах
SELECT cat_folder, COUNT(DISTINCT major_sect) FROM cats_map
GROUP BY cat_folder;

# узнать что это за зверь 'AZ'?
SELECT DISTINCT cat_folder FROM cats_map WHERE major_sect = 'AZ';
*/

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Разбор автомобиля: Второстепенные секции (подгруппы) автомобиля
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_map_minor_section(array $prms){   
    if(empty($prms['lang_code']) or empty($prms['cat_folder']))
      die(__METHOD__.": Не заданы обязательные параметры!");

    $lang_code = $prms['lang_code'];
    $q_lang_code = 'Q'.substr($lang_code,0,1);

    $sql = "
SELECT 
  cats_map.*,
  COALESCE(lex_major_qual_loc.lang_code, lex_major_loc.lang_code, lex_major_qual_def.lang_code, lex_major_def.lang_code) major_lang_code,
  COALESCE(lex_major_qual_loc.lex_desc, lex_major_loc.lex_desc, lex_major_qual_def.lex_desc, lex_major_def.lex_desc) major_lex_desc,  
  COALESCE(lex_minor_qual_loc.lang_code, lex_minor_loc.lang_code, lex_minor_qual_def.lang_code, lex_minor_def.lang_code) minor_lang_code,
  COALESCE(lex_minor_qual_loc.lex_desc, lex_minor_loc.lex_desc, lex_minor_qual_def.lex_desc, lex_minor_def.lex_desc) minor_lex_desc, 
  COALESCE(lex_note_qual_loc.lang_code, lex_note_loc.lang_code, lex_note_qual_def.lang_code, lex_note_def.lang_code) note_lang_code,
  COALESCE(lex_note_qual_loc.lex_desc, lex_note_loc.lex_desc, lex_note_qual_def.lex_desc, lex_note_def.lex_desc) note_lex_desc  
FROM 
  cats_map
  #major
  LEFT OUTER JOIN lex_lex lex_major_def ON lex_major_def.lang_code = 'EN' AND major_sect_lex_code = lex_major_def.lex_code
  LEFT OUTER JOIN lex_lex lex_major_loc ON lex_major_loc.lang_code = '$lang_code' AND major_sect_lex_code = lex_major_loc.lex_code
  LEFT OUTER JOIN lex_lex lex_major_qual_def ON lex_major_qual_def.lang_code = 'QE' AND major_sect_lex_code = lex_major_qual_def.lex_code
  LEFT OUTER JOIN lex_lex lex_major_qual_loc ON lex_major_qual_loc.lang_code = '$q_lang_code' AND major_sect_lex_code = lex_major_qual_loc.lex_code
  #minor
  LEFT OUTER JOIN lex_lex lex_minor_def ON lex_minor_def.lang_code = 'EN' AND minor_sect_lex_code = lex_minor_def.lex_code
  LEFT OUTER JOIN lex_lex lex_minor_loc ON lex_minor_loc.lang_code = '$lang_code' AND minor_sect_lex_code = lex_minor_loc.lex_code
  LEFT OUTER JOIN lex_lex lex_minor_qual_def ON lex_minor_qual_def.lang_code = 'QE' AND minor_sect_lex_code = lex_minor_qual_def.lex_code
  LEFT OUTER JOIN lex_lex lex_minor_qual_loc ON lex_minor_qual_loc.lang_code = '$q_lang_code' AND minor_sect_lex_code = lex_minor_qual_loc.lex_code
  #note
  LEFT OUTER JOIN lex_lex lex_note_def ON lex_note_def.lang_code = 'EN' AND note_lex_code = lex_note_def.lex_code
  LEFT OUTER JOIN lex_lex lex_note_loc ON lex_note_loc.lang_code = '$lang_code' AND note_lex_code = lex_note_loc.lex_code
  LEFT OUTER JOIN lex_lex lex_note_qual_def ON lex_note_qual_def.lang_code = 'QE' AND note_lex_code = lex_note_qual_def.lex_code
  LEFT OUTER JOIN lex_lex lex_note_qual_loc ON lex_note_qual_loc.lang_code = '$q_lang_code' AND note_lex_code = lex_note_qual_loc.lex_code
";
    
  
    // фильтрация
    $where = array();
    $where[] = "cat_folder = '".$prms['cat_folder']."'";    
    if(!empty($prms['major_sect'])) $where[] = "major_sect = '".$prms['major_sect']."'";    
    if(!empty($prms['sector_format_arr'])){ //array
      $where[] = "sector_format IN ('".implode("','", $prms['sector_format_arr'])."')";
    }

    $res = $this->mysqli->query_prepare_exec($sql, $where);

    $res = $this::cat_map_minor_section_unpack_compatibility($res);
//    var_dump($res);die;
    // применяемость
    if(!empty($prms['ucc']) or !empty($prms['vin'])){
      foreach($res as $k=>$row){
        // ucc
        if(!empty($row['compatibility_unpack']['ucc']) and !self::compare_ucc($prms['ucc'], $row['compatibility_unpack']['ucc'])){
          unset($res[$k]);
          continue;
        }
        // vin.options
        if(!empty($row['compatibility_unpack']['option']) and !self::compare_vin_options($prms['vin'], $row['compatibility_unpack']['option'])){
          unset($res[$k]);
        }
      }
    }
    
    $res = array_flip_2($res, 'minor_sect');  //проиндексируем
    return $res;
  }
/*
Все возможные опции совместимости
SELECT DISTINCT compatibility FROM cats_map;
*/  
 
  //~~~~~~~~~~~~~~~~~~~~~~~~
  // Распарсим опции совместимости cats_map.compatibility всех minor_section сразу
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public static function cat_map_minor_section_unpack_compatibility(array $minor_sects){
    $res = array();
    foreach($minor_sects as $row){
      $compatibility_unpack = array();
      
      if(!self::empty_compatibility($row['compatibility'])){  // может быть пустым
        $compatibility_array = explode(';',$row['compatibility']);
        $compatibility_unpack = $compatibility_array;
        // пока не понимаю необходимоть 0,1 - полей
        $compatibility_unpack['ucc'] = $compatibility_array[2];
        
        $option = rtrim($compatibility_array[3],'|');
        if(!empty($option))
          $compatibility_unpack['option'] = explode('|', $option);
      }
      $row['compatibility_unpack'] = $compatibility_unpack;
      $res[] = $row;
    }
    
    return $res;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Разбор автомобиля: Позиция элементов (PNC или minor_setcion) на рисунке
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_dat_minor_image_pnc_ref(array $prms){
    if(empty($prms['cat_folder']) or empty($prms['minor_sect']))
      die(__METHOD__.": Не заданы обязательные параметры!");
      
    $sql = "
SELECT 
  *
FROM 
  cats_dat_ref
";

    // фильтрация
    $where = array();
    $where[] = "cat_folder = '".$prms['cat_folder']."'";    
    $where[] = "img_name = '".$prms['minor_sect']."'";    

    $res = $this->mysqli->query_prepare_exec($sql, $where);
    return $res;
  }
/* ошибка EUR28091 ~ "1338AB'" исправлена при экспорте данных*/
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Разбор автомобиля: Позиция других minor_setcion на рисунке выбранного minor_setcion
  //  cat_image_ref[]['ref'] - содержит код сектора, а не самого minor_setcion
  //  с учетом применяемости ucc + vin.options
  //  усилина подбор информации для найденых sector->minor_sections
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_dat_minor_image_minor_ref(array $prms){
    $cat_image_ref = $prms['cat_image_ref'];   //$MC_API->cat_dat_minor_image_pnc_ref
    $cat_folder = $prms['cat_folder'];
    
    // получить сектора с рисунка
    $minor_ref = array();
    foreach($cat_image_ref as $img_ref){
      if($img_ref['ref_type'] != '5') continue;

      // ссылка может содержать PNC
      $ref_codes = explode(',', $img_ref['ref']); // может быть список секторов = 86-873A,86-873B
      foreach($ref_codes as $sector_pnc){
        $sector_pnc = explode('&', $sector_pnc); // 56-571&57231 = PNC 57231 в 56-571
        $sector = $sector_pnc[0];  //sector_format
        $pnc = isset($sector_pnc[1]) ? $sector_pnc[1] : "";
                
        $minor_ref[$sector]['pnc'] = $pnc;  
      }
    }
    
    if(empty($minor_ref)) // нету ссылок на minor_section
      return array();
      
    // получить из БД описания секторов 
    $sector_format = array_keys($minor_ref);
    $prms['sector_format_arr'] = $sector_format;
    $minor_sections = $this->cat_map_minor_section($prms);
    if(empty($minor_sections))
      return $minor_ref;
    
    // паралельно с PNC запоминаем описание секторов
    $minor_sections = array_group($minor_sections, 'sector_format');
    foreach($minor_ref as $sector => $data){
      $sector_info = array();
      if(isset($minor_sections[$sector])){
        $sector_info = $minor_sections[$sector];
      }
      
      $minor_ref[$sector]['sector'] = $sector_info;
    }

    return $minor_ref;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Разбор автомобиля: Список запчастей
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function cat_dat_parts(array $prms){
    if(empty($prms['lang_code']) or empty($prms['cat_folder']) or empty($prms['minor_sect']))
      die(__METHOD__.": Не заданы обязательные параметры!");

    $lang_code = $prms['lang_code'];
    $q_lang_code = 'Q'.substr($lang_code,0,1);

    $sql = "
SELECT 
  cats_dat_parts.*,
  COALESCE(name_desc_qual_def.lex_desc, name_desc_def.lex_desc) name_def_lex_desc,
  COALESCE(name_desc_qual_loc.lex_desc, name_desc_loc.lex_desc) name_loc_lex_desc,
  COALESCE(desc_qual_loc.lex_desc, desc_loc.lex_desc,desc_qual_def.lex_desc, desc_def.lex_desc) desc_lex_desc
FROM 
  cats_dat_parts
  # name_lex_code
  LEFT OUTER JOIN lex_lex name_desc_def ON name_desc_def.lang_code = 'EN' AND name_lex_code = name_desc_def.lex_code
  LEFT OUTER JOIN lex_lex name_desc_qual_def ON name_desc_qual_def.lang_code = 'QE' AND name_lex_code = name_desc_qual_def.lex_code
  LEFT OUTER JOIN lex_lex name_desc_loc ON name_desc_loc.lang_code = '$lang_code' AND name_lex_code = name_desc_loc.lex_code
  LEFT OUTER JOIN lex_lex name_desc_qual_loc ON name_desc_qual_loc.lang_code = '$q_lang_code' AND name_lex_code = name_desc_qual_loc.lex_code
  # desc_lex_code
  LEFT OUTER JOIN lex_lex desc_def ON desc_def.lang_code = 'EN' AND desc_lex_code = desc_def.lex_code
  LEFT OUTER JOIN lex_lex desc_qual_def ON desc_qual_def.lang_code = 'QE' AND desc_lex_code = desc_qual_def.lex_code
  LEFT OUTER JOIN lex_lex desc_loc ON desc_loc.lang_code = '$lang_code' AND desc_lex_code = desc_loc.lex_code
  LEFT OUTER JOIN lex_lex desc_qual_loc ON desc_qual_loc.lang_code = '$q_lang_code' AND desc_lex_code = desc_qual_loc.lex_code
";

    // фильтрация sql
    $where = array();
    $where[] = "cat_folder = '".$prms['cat_folder']."'";    
    $where[] = "minor_sect = '".$prms['minor_sect']."'";    
    if(!empty($prms['pnc'])) $where[] = "pnc = '".$prms['pnc']."'";

    $res = $this->mysqli->query_prepare_exec($sql, $where);
    if(empty($res)) 
      return array();
    
    //Распарсим опции совместимости
    $res = self::cat_dat_part_unpack_compatibility($res);
    
    //применяемость
    $res = self::cat_dat_part_compare_compatibility($res, $prms);
       
    return $res;
  }
  
  //~~~~~~~~~~~~~~~~~~~~~~~~
  // Разбор автомобиля: Список запчастей: Распарсим опции совместимости cats_dat_parts.compatibility всех parts сразу
  //  если empty_compatibility => compatibility_unpack тоже будет пустым
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function cat_dat_part_unpack_compatibility(array $parts){
    $res = array();
    foreach($parts as $row){
      $comptb_unpack = array();
      
      if(!self::empty_compatibility($row['compatibility'])){  // может быть пустым
        $comptb_unpack = explode(';',$row['compatibility']);
        // допиливаем
        $comptb_unpack['drive_type'] = $comptb_unpack[0];
        $comptb_unpack['weather_type'] = $comptb_unpack[1];
        $comptb_unpack['ucc'] = $comptb_unpack[2];
        
        $options = rtrim($comptb_unpack[3], '|');
        $comptb_unpack['options'] = array();
        if(!empty($options)){
          $comptb_unpack['options'] = explode('|', $options);
        }
        
        $options_minus = rtrim($comptb_unpack[4], '|');
        $comptb_unpack['options_minus'] = array();
        if(!empty($options_minus)){
          $comptb_unpack['options_minus'] = explode('|', $options_minus);
        }
        
        $comptb_unpack['wiegth'] = (isset($comptb_unpack[8]) ? explode('|', $comptb_unpack[8]) : array('',''));    
      }
      $row['compatibility_unpack'] = $comptb_unpack;
      $res[] = $row;
    }
    
    return $res;
  }
  
  //~~~~~~~~~~~~~~~~~~~~~~~~
  // Разбор автомобиля: Список запчастей: Проверить запчасти ($parts) на совместимость
  //  согласно заданной маске применяемости ($prms['compatibility'])
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function cat_dat_part_compare_compatibility(array $parts, array $prms){
    // если маска применяемости пустая - все соместимо
    if(empty($prms['compatibility']) or self::empty_compatibility_set($prms['compatibility']))
      return $parts;
    $comptb_set = $prms['compatibility'];
    
    $res = array();
    foreach($parts as $row){
      // дата производства авто
      if(isset($comptb_set['build_date'])){
        $build_date = trim($comptb_set['build_date']);
        $date_l = strlen($build_date);  // 4 = год выпуска авто, выбранный при разборе каталога. VIN = 8 символов
        $row_production_from = substr($row['production_from'], 0, $date_l);
        $row_production_to = (!empty($row['production_to']) ? $row['production_to'] : '99991231');
        $row_production_to = substr($row_production_to, 0, $date_l);
        
        if(($build_date > $row_production_to) or ($build_date < $row_production_from))
          continue; // неподходит
      }
      
      // если сторка применяемости - пустая => значит запчасть совместима
      if(self::empty_compatibility($row['compatibility'])){
        $res[] = $row;
        continue;
      }
      $comptb_part = $row['compatibility_unpack'];
      
      
      // isset only, no! empty
      // drive_type
      if(isset($comptb_part['drive_type']) and isset($comptb_set['drive_type']) 
         and !self::compare_ucc_vals($comptb_part['drive_type'],$comptb_set['drive_type'])){
           continue;  // неподходит
      }
      // weather_type
      if(isset($comptb_part['weather_type']) and isset($comptb_set['weather_type']) 
         and !self::compare_ucc_vals($comptb_part['weather_type'],$comptb_set['weather_type'])){
           continue;  // неподходит
      }
      // ucc
      if(isset($comptb_part['ucc']) and isset($comptb_set['ucc'])
         and !self::compare_ucc($comptb_part['ucc'], $comptb_set['ucc'])){
           continue;  // неподходит
      }
      // vin-options [+]
      if(isset($comptb_part['options']) and isset($comptb_set['vin'])
         and !self::compare_vin_options($comptb_set['vin'], $comptb_part['options'])){
           continue;  // неподходит
      }
      // vin-options [-]
      if(isset($comptb_part['options_minus']) and isset($comptb_set['vin'])
         and !self::compare_vin_options_minus($comptb_set['vin'], $comptb_part['options_minus'])){
           continue;  // неподходит
      }
      
      $res[] = $row;
    }
    
    return $res;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Разбор автомобиля: Список запчастей: в приделах PNC отбрать запчасти с наибольшым весом применяемости
  //  вызывать только при результате поиска по VIN
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function cat_dat_parts_compare_wiegth(array $cat_parts){
    // наилучший вес в PNC 
    $pnc_weight = array();
    foreach($cat_parts as $part){
      $part_wiegth = $part['compatibility_unpack']['wiegth'][0];
      if(!isset($pnc_weight[$part['pnc']]) or ($part_wiegth > $pnc_weight[$part['pnc']])){
        $pnc_weight[$part['pnc']] = $part['compatibility_unpack']['wiegth'][0];
      }
    }
    
    // отобрать запчасти с наилучшим весом
    $res = array();
    foreach($cat_parts as $part){
      $part_wiegth = $part['compatibility_unpack']['wiegth'][0];
      if($part_wiegth >= $pnc_weight[$part['pnc']]){
        $res[] = $part;
      }
    }
    
    return $res;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Разбор автомобиля: тупо список номеров
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function cat_dat_parts_numbers(array $cat_parts){
    if(empty($cat_parts))
      return array();
  
    $res = array();
    foreach($cat_parts as $part){
      $res[$part['number']] = $part['number'];  // уникальность
    }
    return $res;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Разбор автомобиля: вытянуть список pnc из списка запчастей
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function cat_dat_parts_pnc(array $cat_parts){
    if(empty($cat_parts)) 
      return array();
     
    $res = array();
    foreach($cat_parts as $part){
      $res[$part['ref']] = array(
                'pnc' => $part['pnc'],
                'minor_sect'=>$part['minor_sect'],  //для построения урла
                'major_sect'=>$part['major_sect'],  //для построения урла
                'name_def_lex_desc'=>$part['name_def_lex_desc'],
                'name_loc_lex_desc'=>$part['name_loc_lex_desc'],
                'desc_lex_desc'=>$part['desc_lex_desc'],
             );
    }
    
    return $res;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Разбор автомобиля: Список замен внутри региона
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function bom_localpts(array $cat_parts){
    if(empty($cat_parts))
      return array();
      
    $numbers = self::cat_dat_parts_numbers($cat_parts);
    if(empty($numbers))
      return array();
    
    $part_number1 = implode("','",$numbers);
    $sql = "
SELECT * FROM bom_localpts WHERE part_number1 IN ('$part_number1');
";

    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    if(empty($res))
      return array();
      
    $res = array_group($res, 'part_number1'); //нужно группировать, может быть несколько на один номер
    return $res;
  }

  
  

/*********************************************************************************************************
  ПРОВЕРКА СОВМЕСТИМОСТИ
 *********************************************************************************************************/
  
  //~~~~~~~~~~~~~~~~~~~~~~~~
	// Проверить что строка compatibility задано, т.е. не ";;|||||||||;;;;;;|"
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function empty_compatibility($compatibility){
    if(empty($compatibility))
      return true;
      
    $compatibility = str_replace(array(' ',';','|'), '', $compatibility);
    if(empty($compatibility))
      return true;
    
    return false;
  }
  
  //~~~~~~~~~~~~~~~~~~~~~~~~
	// Проверить что маска compatibility для отбора данных - задано
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function empty_compatibility_set($compatibility_set){
    if(empty($compatibility_set))
      return true;
      
    $compatibility_set = array_filter($compatibility_set);
    if(empty($compatibility_set))
      return true;
    
    return false;
  }
  
  

  //~~~~~~~~~~~~~~~~~~~~~~~~
	// Совместимость ucc
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public static function compare_ucc($ucc1="", $ucc2=""){
    if($ucc1 == "" or $ucc2 == "")
      return true;
    
    $ucc1_arr = explode('|', $ucc1);
    $ucc2_arr = explode('|', $ucc2);
    
    //проверять нужно значения в одинаковых позициях
    //если значение в позиции пустое - значит совместима
    //количество элементов в $ucc1_arr и $ucc2_arr - могут отличатся, тогда проверяем по наименьшому 
    foreach($ucc1_arr as $k=>$ucc_v1){
      if(!isset($ucc2_arr[$k])) // проверяем количество элементов по наименьшому
        break; 
      $ucc_v2 = $ucc2_arr[$k];
      
      // ищем НЕсовместимость
      if(!self::compare_ucc_vals($ucc_v1, $ucc_v2)) 
        return false;
    }
    
    return true;
  }
  
  //~~~~~~~~~~~~~~~~~~~~~~~~
	// Совместимость ucc, сравнить явно заданные (!isset) значение на сометсимость
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public static function compare_ucc_vals($ucc_v1="", $ucc_v2=""){
    if(($ucc_v1 == "") or ($ucc_v2 == ""))
      return true;
      
    return ($ucc_v1 == $ucc_v2);
  }

  
  //~~~~~~~~~~~~~~~~~~~~~~~~
	// Совместимость (Подгруппа|Запчасть).options <-> vin.options
  //  $vin - вин содержит 3 группы options, поэтому передаем все огруппы опций(весь вин)
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public static function compare_vin_options(array $vin, array $options){
    // прверять что опции !empty - обязательно, это признак положительной применимости
    if(empty($options))
      return true;
    if(empty($vin['options_standart']) and empty($vin['options_optional']) and empty($vin['options_add']))
      return true;
    
    foreach($options as $opt){
      if(empty($opt)) 
        continue;
      
      //найти в vin.options
      if(!isset($vin['options_standart'][$opt]) and !isset($vin['options_optional'][$opt]) and !isset($vin['options_add'][$opt])) //???
        return false;
    }

    return true;
  }

  
  //~~~~~~~~~~~~~~~~~~~~~~~~
	// НЕ Совместимость (Запчасть).options_minus <-> vin.options
  //  $vin - вин содержит 3 группы options, поэтому передаем все огруппы опций(весь вин)
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public static function compare_vin_options_minus(array $vin, array $options){
    // прверять что опции !empty - обязательно, это признак положительной применимости
    if(empty($options))
      return true;
    if(empty($vin['options_standart']) and empty($vin['options_optional']) and empty($vin['options_add']))
      return true;
    
    foreach($options as $opt){
      if(empty($opt)) 
        continue;
      
      //НЕ найти в vin.options
      if(isset($vin['options_standart'][$opt]) or isset($vin['options_optional'][$opt]) or isset($vin['options_add'][$opt])) //???
        return false;
    }

    return true;
  }
  
  
  
/*********************************************************************************************************
  ЯЗЫКИ 
 *********************************************************************************************************
Qx - думаю от слова Quality - более качественный, а для некоторых языков и более полный
Qx - определяется от кода застаревшего образца первым сиволом

SELECT lang_code, COUNT(lex_desc)  FROM lex_lex  GROUP BY lang_code;
Q1, Q2, QC, QJ, QK, QP, QR - неполные

сравнение текстов
SELECT * 
FROM lex_lex lex_qual
  JOIN lex_lex lex_en ON lex_en.lang_code = 'EN' AND lex_en.lex_code = lex_qual.lex_code
WHERE lex_qual.lang_code = 'QE';

Q% - не полные
SELECT * 
FROM lex_lex lex_en
  LEFT OUTER JOIN lex_lex lex_qual ON lex_qual.lang_code = 'QE' AND lex_en.lex_code = lex_qual.lex_code
WHERE lex_en.lang_code = 'EN';
*/

	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Список языков для текстов данных
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function lex_languages_list(){
    $sql = "
SELECT DISTINCT (lang_code) 
  FROM lex_lex 
  WHERE lang_code NOT LIKE 'Q%'
";
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    return $res;
  }
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Список системных языков
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function mc_lexicon_languages_list(array $prms){
    if(empty($prms['lang_code']))
      die(__METHOD__.": Не задано обязательный параметр - lang_code!");
  
    $sql = "
#EXPLAIN
SELECT #*,
  lex_h.lang_code, lex_h.lang_desc, lex.lang_code lex_lang_code, lex.lex_desc
  FROM mc_lexicon_h lex_h
    JOIN mc_lexicon lex 
      ON lex.lex_code = lex_h.lex_code_desc
      AND lex.lang_code = '".$prms['lang_code']."'
";
    
    // нужно ли уточнять список
    if(isset($prms['lexicon_lang_codes'])){
      $sql_where = implode("','", $prms['lexicon_lang_codes']);
      $sql .= "  WHERE lex_h.lang_code IN ('$sql_where')";
    }
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    return $res;
  }
  
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Список системных языков для которых есть тексты данных
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function mc_lexicon_lex_languages_list(array $prms){
    if(empty($prms['lang_code']))
      die(__METHOD__.": Не задано обязательный параметр - lang_code!");
 
    // список кодов языков данных
    $lex_languages_list = $this->lex_languages_list();
    
    // получить список кодов для системного языка 
    $lexicon_lang_codes = array();
    foreach ($lex_languages_list as $row){
      $lexicon_lang_codes[] = $this->lex2lexicon($row['lang_code']);
    }
    
    // список ситемных языков
    $lexicon_lang_code = $this->lex2lexicon($prms['lang_code']);
    $res = $this->mc_lexicon_languages_list(array('lang_code'=>$lexicon_lang_code, 'lexicon_lang_codes'=>$lexicon_lang_codes));
    
    //адаптируем для языков данных
    foreach($res as $k => $row){
      $res[$k]['lang_code'] = $this->lexicon2lex($row['lang_code']);
    }
    
    return $res;
  }
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// совместимость кода языка данных с кодом системного языка
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  protected function lex2lexicon($lang_code=''){
    $lex_lang_code = isset($this->lex_lexicon[$lang_code]) ? $this->lex_lexicon[$lang_code] : $lang_code;
    return $lex_lang_code;
  }
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// совместимость кода системного языка с кодом языка данных
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  protected function lexicon2lex($lang_code=''){
    $lexicon_lang_code = isset($this->lexicon_lex[$lang_code]) ? $this->lexicon_lex[$lang_code] : $lang_code;
    return $lexicon_lang_code;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Список всек текстов для интерфейса
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function mc_lexicon_system_text(array $prms){
    if(empty($prms['lang_code']))
      die(__METHOD__.": Не задано обязательный параметр - lang_code!");

    $lexicon_lang_code = $this->lex2lexicon($prms['lang_code']);
      
    $sql = "
#EXPLAIN
SELECT 
    # lex_sys.lex_desc lex_sys_desc, lex_lang.lex_desc lex_lang_desc - не все могут быть
    lex_sys.lex_desc lex_sys_desc, 
    IF(lex_lang.lex_desc <> '', lex_lang.lex_desc, lex_sys.lex_desc) lex_lang_desc
  FROM mc_lexicon lex_sys
    JOIN mc_lexicon lex_lang 
      ON lex_lang.lex_code = lex_sys.lex_code
      AND lex_lang.lang_code = '$lexicon_lang_code'
  WHERE lex_sys.lang_code = 'EN'
    AND (
      lex_sys.lex_desc IN (
        'Accessories', 'ALL',
        'Build date',
        'Canada', 'Car Line', 'Catalogue', 'CIS', 'Colour Details', 'Commercial', 'Country',
        'Displays Section:', 'Down',
        'End Date', 'Engine Code', 'Engine Number', 'Enter filter criteria', 'Exterior Colour',
        'General', 'Graphic Index',
        'India', 'Interior Colour',
        'Japan',
        'Language', 'Line', 'Load',
        'Major Attributes', 'Major Section', 'Middle East', 'Minor Section', 'Model', 'Model Description', 'Model year', 'Multiple VIN Selection',
        'No Results',
        'Option Codes',
        'Part Name', 'Part Number', 'Passenger', 'Production Date', 'Plant',
        'Qty',
        'Search', 'Select', 'Special', 'Start Date',
        'Transmission Code', 'Turkey',
        'Up', 'USA',
        'Vehicle Details', 'Vehicle Information', 'Vehicle line', 'VIN not found', 'Vehicle Search',
        'Year'
      )
      OR lex_sys.lex_code IN (
        734 /*Not Applicable*/, 828 /*Unknown*/, 1325 /*Reset*/, 2353 /*Australia*/, 2354 /*Europe*/, 3565 /*North America*/, 3688 /*Region*/, 3689 /*Vehicle Type*/, 6166 /*PRODUCT LINE 2...*/)
    );
";
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    $res = array_flip_2($res, 'lex_sys_desc'); // проиндексируем    
    return $res;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Вытянуть текстовое описание по английскому слову - lex_sys_desc, 
  //  если не получается - результат lex_sys_desc
  //  prepare_1st => (если <> 0) первый символ заглавный - остальные прописью
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function mc_lexicon_system_text_desc(array $prms){
    $SYSTEM_TEXT = $prms['system_text'];
    $lex_sys = $prms['lex_sys'];
    $lex_lang_desc = $lex_sys;
    
    if(!empty($prms['prepare_1st']))
      $lex_sys = ucfirst(strtolower($lex_sys));

    if(isset($SYSTEM_TEXT[$lex_sys]))
      $lex_lang_desc = $SYSTEM_TEXT[$lex_sys]['lex_lang_desc'];

    return $lex_lang_desc;
  }
  
  
  
  
  
/*********************************************************************************************************
  VIN 
 *********************************************************************************************************/

	//~~~~~~~~~~~~~~~~~~~~~~~~
	// VIN: поиск по VIN
  //  $prms
  //    'vin'
  //    'catalogue_code' - для точного нахождения авто (нужно при работе с запчастями)
  //    'is_check_ucc' - проверить и повозможности взять ucc-значения из каталога, 
  //                     если реализовать визуальный функционал доуточнения ucc-значений, то параметр нужно пропускать
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function vin_result(array $prms){
    $vin_options = $this->vin_options($prms);  // результат - 1 запись (даже при множесвенном MC_API->vin_vin_model)
    $vin_models = $this->vin_vin_model($prms); // результат может быть множественным!!! 5XYZH4AG8BG000618

    // есть случаи когда VIN в базе(vin_options-vin_model) есть, а в индексе(vin_vin) нету - KMHFG4DG0CA001132
    if(empty($vin_models) and !empty($vin_options)){
      $vin_models = $this->vin_model(array('vin_model_id'=>$vin_options['vin_model_id'])); 
    }
    
    if(empty($vin_models))
      return array();
    
    //формируем результат
    $vin_res = array();
    foreach($vin_models as $k=>$vin_mod){
      // Если ucc-строка имет пустые значения и то можно взять из catalog_ucc, 
      // но при условии что при данном ucc-типе возможно только одно значение -  KMHCF21F4RU000174
      if(!empty($prms['is_check_ucc'])){
        $vin_mod = $this->vin_ucc_check($vin_mod); // перезаписуем
      }
      
      //формируем результат
      $vin_res[] = array(
            'model'=>$vin_mod,
            'options'=>$vin_options,
            'options_standart'=>$this->vin_options_split_option($vin_options, 'option_standart'), // опции вина
            'options_optional'=>$this->vin_options_split_option($vin_options, 'option_optional'),
            'options_add'=>$this->vin_options_split_option($vin_options, 'option_add'),
          );
    }

    return $vin_res;
  }
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// VIN: id & model
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function vin_vin_model(array $prms){
    if(empty($prms['vin']))
      die(__METHOD__.": Не задано обязательный параметр - vin!");
    
    $sql = "
SELECT vin_vin.vin, vin_date, vin_model.* 
  FROM vin_vin
  LEFT JOIN vin_model
    ON vin_vin.vin_model_id = vin_model.vin_model_id
";
    
    // фильтрация
    $where = array();
    $where[] = "vin = '".$prms['vin']."'";    
    if(!empty($prms['catalogue_code'])) $where[] = "catalogue_code = '".$prms['catalogue_code']."'";    
    
    $res = $this->mysqli->query_prepare_exec($sql, $where); // результат может быть множественным  
    return $res;
  }  

  //~~~~~~~~~~~~~~~~~~~~~~~~
	// VIN: id & model
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function vin_model(array $prms){
    if(empty($prms['vin_model_id']))
      die(__METHOD__.": Не задано обязательный параметр - vin_model_id!");
    
    $sql = "
SELECT * FROM vin_model
";
    
    // фильтрация
    $where = array();
    $where[] = "vin_model_id = '".$prms['vin_model_id']."'";    
    if(!empty($prms['catalogue_code'])) $where[] = "catalogue_code = '".$prms['catalogue_code']."'";    
    
    $res = $this->mysqli->query_prepare_exec($sql, $where);  // результат оставляем множественным, для совметсимости с vin_vin_model
    return $res;
  }
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// VIN: options
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function vin_options(array $prms){
    if(empty($prms['vin']))
      die(__METHOD__.": Не задано обязательный параметр - vin!");
    
    $sql = "
#EXPLAIN
SELECT * FROM vin_options WHERE vin = '".$prms['vin']."';
";
    
    $res = $this->mysqli->query_fetch_all(array('query' => $sql));
    if(!empty($res)) 
      $res = $res[0];
      
    return $res;
  }  
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// VIN: проверить и повозможности взять ucc-значения из каталога
  //  Если ucc-строка имет пустое значения, то значение можно взять из catalog_ucc, 
  //    но при условии что при данном ucc-типе задано только одно значение
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function vin_ucc_check(array $vin_model){
    $catalog_ucctype = $this->cat_ucctype(array('lang_code'=>'EN', 'catalogue_code'=>$vin_model['catalogue_code'])); //виды харакетристик авто
    $catalog_ucc = $this->cat_ucc(array('lang_code'=>'EN', 'catalogue_code'=>$vin_model['catalogue_code'])); //харакетристики авто

    $ucc = explode('|', $vin_model['ucc']); // позиция очень важная штука
    foreach($ucc as $f_typ => $f_val){  // тип и значение ucc-элемента в ucc-строке
      if(!($f_val == '' or $f_val == '.')) continue; // работаем только с пустыми
      
      // проверка существует ли текущий тип Характеристики в модели (тупо по позиции)
      if(!isset($catalog_ucctype[$f_typ])) continue; // KMXMSE1PPWU047971
      $ucctype = $catalog_ucctype[$f_typ];           // тип ucc-харакетристики 'ucc_type', 'lex_desc'
      $ucctype_typ = $ucctype['ucc_type'];
      
      // тип ucc-харакетристик со своим списком возможных значений 
      $catalog_ucc_values = isset($catalog_ucc[$ucctype_typ]) ? $catalog_ucc[$ucctype_typ] : array();
      
      //условии что при данном ucc-типе только одно значение
      if(count($catalog_ucc_values) == 1){
        $ucc[$f_typ] = key($catalog_ucc_values);
      }
    }
    $vin_model['ucc'] = implode('|', $ucc);
    
    return $vin_model;
  }
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// VIN: get vin date
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function vin_date(array $vin_options){
    $res = !empty($vin_options['production_date']) ? $vin_options['production_date'] : $vin_options['build_date'];
    return $res;
  }
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// VIN: get model year
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
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
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// VIN: options - разделить опции(копмлектации) в моссив
  //  vin_options - опции VIN
  //  fld_option - какое поле парсить
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function vin_options_split_option(array $vin_options, $fld_option=""){
    if(empty($vin_options[$fld_option]))
      return array();
    
    // option_type =3, =4, =8 - нету
    $split_length = 4;  // option_type =2, =5(X7MEN41BP4A000004, X7MCF41GP4M010007), =6(KMCGB17EPXC000608)
    if(($vin_options['option_type'] == '1') or ($vin_options['option_type'] == '7')) //option_type =1, =7 (Y6LJM81BPAL207324)
      $split_length = 6;
    
    $res = str_split($vin_options[$fld_option], $split_length);
    //$res = array_map('trim', $res);  //нагуглил что для built-in function быстрее чем foreach!? 
    $res_key = array();
    foreach($res as $row){
      $row = trim($row);
      $res_key[$row] = $row;
    }
    
    return $res_key;
  }
//KMXMSE1PPWU047971   option_type=='C', option_xxx - пусто
/*
SELECT * 
FROM vin_options 
WHERE option_type = 0 AND option_standart <> '' #AND id > 28000000
LIMIT 3
*/
  
  
  
  
  
/*********************************************************************************************************
  ДОПОЛНИТЕЛЬНО 
 *********************************************************************************************************/
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// get year-month-day from date
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function date_to_ymd($date=""){
    $ymd['year'] = substr($date,0,4);
    $ymd['month'] = substr($date,4,2);
    $ymd['day'] = substr($date,6,0);
    return $ymd;
  }
}
?>