<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/
 
include_once("index_ini.php");
include_once("index_db_functions.php"); // новый модуль, типо API для БД Тайоты

$catalog 		= $_GET['catalog'];
$catalog_code 	= $_GET['catalog_code'];
$model_code 	= $_GET['model_code'];
$sysopt 		= $_GET['sysopt'];
$compl_code 	= $_GET['compl_code'];
$part_group 	= $_GET['part_group'];  // дальше не понадобится

$vin 		 = (isset($_GET['vin']) ? $_GET['vin'] : ""); // дата выпуска VIN (будет задано только при поиске по VIN)
$vdate 		 = (isset($_GET['vdate']) ? $_GET['vdate'] : ""); // дата выпуска VIN (будет задано только при поиске по VIN)
$siyopt_code = (isset($_GET['siyopt_code']) ? $_GET['siyopt_code'] : ""); // опция комплектации VIN (будет задано только при поиске по VIN)


//$disk_num = $_GET['rec_num']; // задается в самом начале Catalog_Code_Select.php (поле shame.rec_num)
$cat_info = _TOY_shamei_info(array('catalog'=>$catalog, 'catalog_code'=>$catalog_code)); // сократим GET переменные, улучшим заказчикам SEO
$disk_num = $cat_info['rec_num'];

//----------------------------------------------
echo "<h3 style='margin-top:-30px;'>Figure</h3>";
echo "<br/>Рисунков может быть несколько, В EPC начинают работать PrevPage и NextPage";
echo "<br/>";

// Дату модели нужно получить, только если не известно дата VIN
if (empty($vdate)){
	$mod_info = _TOY_johokt_model_info(array(
		'catalog'		=>$catalog, 
		'catalog_code'	=>$catalog_code,
		'model_code'	=>$model_code,
		'sysopt'		=>$sysopt,
		'compl_code'	=>$compl_code,
		));
	//var_dump($mod_info);
}

// Запрос EPC Toyota 
// Если inm.ftype > 1 то можна брать только описания inm.desc_en и клеить к ftype == 1";
$query = "
SELECT 
  bzi.*, 
  kpt.compl_code, 
  inm.op1, inm.op2, inm.op3, inm.ftype, inm.desc_en

FROM 
  bzi #список всех подгрупп(иллюстраций)

    # применяемость подгруппы запчастей
	LEFT JOIN kpt 
	  ON kpt.catalog = bzi.catalog
	  AND kpt.catalog_code = bzi.catalog_code
	  AND kpt.ipic_code = bzi.ipic_code # маска применяемости
	
	# описания к подгрупп(иллюстраций)
	LEFT OUTER JOIN inm
	  ON inm.catalog = bzi.catalog
	  AND inm.catalog_code = bzi.catalog_code
	  AND inm.pic_desc_code = bzi.pic_desc_code
	  AND inm.op1 = bzi.op1
	
WHERE 
  bzi.catalog = '$catalog'
  AND bzi.catalog_code = '$catalog_code'
  AND bzi.part_group = '$part_group'

  # применяемость подгруппы запчастей относительно типу комплектации выбранной модели авто
  AND kpt.compl_code IN (
    SELECT DISTINCT(compl_code) FROM johokt WHERE catalog = '$catalog' AND catalog_code = '$catalog_code' AND model_code = '$model_code')
";
// 	отработать по дате модели, если не известна дата VIN --
// 		пустых дат нету	, поэтому споконо берем эти поля
//			SELECT * FROM johokt WHERE IFNULL(prod_start,'') = ''
//			SELECT * FROM johokt WHERE IFNULL(prod_end,'') = ''
// 	!empty($mod_info) - проверим вдруг модель пустая
if (empty($vdate) and !empty($mod_info)) $query .= "
  # При поиске по дате модели в приделах даты модели
  AND (bzi.start_date <= '".$mod_info['prod_end']."' AND bzi.end_date >='".$mod_info['prod_start']."')";
// 	отработать VIN --
if (!empty($vdate)) $query .= "
  # При поиске по VIN - в приделах даты выпуска авто
  AND ('$vdate' BETWEEN start_date AND end_date)";

  
$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['url_main_params'] = "vin=$vin&vdate=$vdate&siyopt_code=$siyopt_code&catalog=$catalog&catalog_code=$catalog_code&model_code=$model_code&sysopt=$sysopt&compl_code=$compl_code&part_group=$part_group&";
$params['f_exec_name'] = "pic_code";
$params['exec_module'] = "Figure_NextPage.php";
$params['img_row_path'] = _IMG_IllSTR_INDEX_PATH . "Img/$catalog/$disk_num/" ; // путь к рисункам ImgIllIndex
$params['img_row_file'] = "pic_code"; // путь к рисункам
$params['img_ext'] = ".png"; // расширение рисунка
$params['img_show_size'] = "250"; // высота и ширина рисунка для показа
$params['img4demo'] = '';//basename(__FILE__, ".php");  // определить имя рисунка
ExecQuery($params);


/* //----------------------------------------------
 echo "<h3>KPT</h3>";

// Запрос EPC Toyota 
$query = "
SELECT *
  FROM hinmei
  WHERE hinmei.catalog = '$catalog'
    AND hinmei.pnc = '8601';
";

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['f_exec_name'] = "";
$params['exec_module'] = "";
$params['img_row_path'] = ""; // путь к рисункам
$params['img_row_file'] = ""; // путь к рисункам
$params['img4demo'] = "";  // определить имя рисунка
ExecQuery($params);
 */
?>