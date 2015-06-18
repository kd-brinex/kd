<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/
  
 
include_once("index_ini.php");

$catalog = $_GET['catalog'];
$catalog_code = $_GET['catalog_code'];
$model_code = $_GET['model_code'];
if(isset($_GET['sysopt'])) $sysopt = $_GET['sysopt']; 	// если заход с Vehicle_Input_VIN_Search_Result.php - 3 этап, то не будет задано
if(isset($_GET['vin8'])) $vin8 = $_GET['vin8']; 		// если заход с Vehicle_Model_Select.php - то не будет задано


/***********************************************************/
echo "<h2 style='margin-top:-30px;'>Characteristics</h2>";

/* Запрос EPC Toyota */
$query = "
SELECT *
  FROM shamei
  WHERE catalog = '$catalog'
    AND catalog_code = '$catalog_code';
";

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['f_exec_name'] = "";
$params['exec_module'] = "";
$params['img4demo'] = '';//basename(__FILE__, ".php");  // определить имя рисунка
ExecQuery($params);


//----------------------------------------------
echo "<br/>Это полный список всех возможных характеристик для Каталога: $catalog_code</h3>";


// Запрос EPC Toyota 
$query = "
SELECT kig.*, tkm.desc_en
  FROM kig
    LEFT JOIN tkm
      ON tkm.catalog = kig.catalog
      AND tkm.catalog_code = kig.catalog_code
      AND tkm.`type` = kig.`type`
  WHERE kig.catalog = '$catalog'
      AND kig.catalog_code = '$catalog_code'; ";


$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['f_exec_name'] = "";
$params['exec_module'] = "";
$params['img4demo'] = "";  // определить имя рисунка
ExecQuery($params);
 
 
 
echo "<br/><hr/><h3>Model Selected</h3>";
echo "Опять возвращаемся к выбранной модели авто<br/>";

// Запрос EPC Toyota 
$query = "
SELECT DISTINCT
    shamei.*,"; 
// 13.02.2013 - при заданном $vin8 нам нужно видеть весь список, возможно будут вариации по johokt.sysopt
if(isset($vin8)) $query .= "
    johokt.*";
// 13.02.2013 - иначе нужно johokt.vin8 и отобрать только подходящие для указанного $sysopt
else $query .= "
    model_code,johokt.prod_start,johokt.prod_end,frame,sysopt,compl_code,engine1,engine2,body,grade,atm_mtm,trans,johokt.f1,johokt.f2,f3,f4,f5";
$query .= "
  FROM johokt
  
	JOIN shamei
	  ON shamei.catalog = johokt.catalog
	  AND shamei.catalog_code = johokt.catalog_code
  WHERE johokt.catalog = '$catalog'
    AND johokt.catalog_code = '$catalog_code'
	AND johokt.model_code = '$model_code'";
if(isset($sysopt)) $query .= "
    AND johokt.sysopt = '$sysopt'";
if(isset($vin8)) $query .= "
    AND johokt.vin8 = '$vin8'";
// 13.02.2013 - 
// $sysopt - будет известно если выбор был методом Регион-Каталог-Модель
// $sysopt - будет НЕ задан	если Результат VIN поиска - 3 этап (не точный типо), но зато будет известный vin8
// В результате нужно обязательно compl_code - это на самомом деле период выпуска модели

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['f_exec_name'] = "catalog~catalog_code~model_code~sysopt~compl_code"; // 13.02.2013 - однозначно определяют выбранную модель johokt
$params['exec_module'] = "Illustrated_Index.php";
$params['img4demo'] = ''; //"Vehicle_Input";  // определить имя рисунка
ExecQuery($params);

?>

