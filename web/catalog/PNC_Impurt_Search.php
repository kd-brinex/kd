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
$pnc = (isset($_GET['pnc']) ? $_GET['pnc'] : "");
$vdate = (isset($_GET['vdate']) ? $_GET['vdate'] : ""); // дата выпуска VIN (будет задано только при поиске по VIN)
?>

<h2>PNC Impurt</h2>
<img src="img/PNC_Impurt_Search.png" width="700"/>
<form method="GET" action="PNC_Impurt_Search.php">
<h4>Part Name Code Search: 
	<input type="text" name="pnc" value="86841" size="10" /> 	
	<input type="submit" value="Поиск " /> 
	
	<input type="hidden" name="catalog" value="<?php echo $catalog ?>">
	<input type="hidden" name="catalog_code" value="<?php echo $catalog_code ?>">
	<input type="hidden" name="model_code" value="<?php echo $model_code ?>">
	<input type="hidden" name="vdate" value="<?php echo $vdate ?>">
</h4> 
</form>


<hr/><h4>HCD</h4>
Первичная проверка на существования PNC<br/>
<?php
// поиск по pnc
/* Запрос EPC Toyota */
$query = "
SELECT *
  FROM hcd
  WHERE hcd.catalog = '$catalog'
	AND hcd.catalog_code = '$catalog_code'
	AND hcd.pnc = '$pnc'
";

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['url_main_params'] = "";
$params['f_exec_name'] = "";
$params['exec_module'] = "";
$params['img_row_path'] = ""; // путь к рисункам
$params['img_row_file'] = ""; // путь к рисункам
$params['img4demo'] = "";  // определить имя рисунка
ExecQuery($params);
?>


<br/><hr/><h4>HNB</h4>
<?php
// поиск по pnc
/* Запрос EPC Toyota */
$query = "
SELECT shamei.*, johokt.*
  FROM johokt
  
	JOIN shamei
	  ON shamei.catalog = johokt.catalog
	  AND shamei.catalog_code = johokt.catalog_code
  WHERE johokt.catalog = '$catalog'
    AND johokt.catalog_code = '$catalog_code'
	AND johokt.model_code = '$model_code';
";

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['url_main_params'] = "";
$params['f_exec_name'] = "";
$params['exec_module'] = "";
$params['img_row_path'] = ""; // путь к рисункам
$params['img_row_file'] = ""; // путь к рисункам
$params['img4demo'] = "";  // определить имя рисунка
ExecQuery($params);
?>


<br/><hr/><h4>HNB</h4>
<?php
// поиск по pnc
/* Запрос EPC Toyota */
$query = "
SELECT *
  FROM hnb
  WHERE hnb.catalog = '$catalog'
	AND hnb.catalog_code = '$catalog_code'
	AND hnb.pnc = '$pnc'
";

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['url_main_params'] = "";
$params['f_exec_name'] = "";
$params['exec_module'] = "";
$params['img_row_path'] = ""; // путь к рисункам
$params['img_row_file'] = ""; // путь к рисункам
$params['img4demo'] = "";  // определить имя рисунка
ExecQuery($params);
?>
