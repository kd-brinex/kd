<h2>SUBS</h2>

<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/ 
 
include_once("index_ini.php");

echo "<hr/><h3>CHOHIN - замена </h3>";

$catalog = $_GET['catalog'];
$part_code = $_GET['part_code'];

/* Запрос EPC Toyota */
$query = "
SELECT *	
	FROM chohin
	WHERE chohin.catalog = '$catalog'
	  AND chohin.part_code1 = '$part_code'
";

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['f_exec_name'] = "";
$params['exec_module'] = "";
$params['img4demo'] = "";  // определить имя рисунка
ExecQuery($params);

 
echo "<hr/><h3>DAIHIN - замена</h3>";

$catalog = $_GET['catalog'];
$part_code = $_GET['part_code'];

/* Запрос EPC Toyota */
$query = "
SELECT *	
	FROM daihin
	WHERE daihin.catalog = '$catalog'
	  AND daihin.part_code1 = '$part_code'
";

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['f_exec_name'] = "";
$params['exec_module'] = "";
$params['img4demo'] = "";  // определить имя рисунка
ExecQuery($params);

?>