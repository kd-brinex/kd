<h2>Catalog Code Select</h2>
<ul>	
	<li>Date -  Это поле может обозначать дату внесения информации в базу данных.</li> 
	<li>Opt = 0 – подсветка желтая</li>
</ul>

<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/
 
 
include_once("index_ini.php");

$catalog = $_GET['catalog'];
//$catalog = "'US'";

/* Запрос EPC Toyota */
$query = "
SELECT *
  FROM shamei
  WHERE catalog = '$catalog'
  ORDER BY catalog_code;
  #ORDER BY model_name, prod_start;
";

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "opt";
$params['f_sel_value'] = "0";
$params['f_sel_color'] = "yellow";
$params['f_exec_name'] = "catalog~catalog_code"; // какие поля из запроса будут формировать урл
$params['exec_module'] = "Vehicle_Model_Select.php";
$params['img4demo'] = '';//basename(__FILE__, ".php");  // определить имя рисунка

ExecQuery($params);
?>