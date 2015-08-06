<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/
  
 
include_once("index_ini.php");

//echo "<h3>JOHOCL</h3>";
//echo "Не ясно, видать для текстового EPC";

$catalog = $_GET['catalog'];
//$catalog = "US";
$catalog_code = $_GET['catalog_code'];

/* Запрос EPC Toyota */
$query = "
SELECT *
  FROM johocl
  WHERE catalog = '$catalog'
    AND catalog_code = '$catalog_code';
";

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['f_exec_name'] = "";
$params['exec_module'] = "";
$params['img4demo'] = "";  // определить имя рисунка
//ExecQuery($params);			2013-05-16 отключил, ?непонятно вообще зачем его дергает Тоета

 
 
 
//------------------------------------------------------------
echo "<h3 style='margin-top:-30px;'>Vehicle_Model_Select</h3>";

/* Запрос EPC Toyota */
$query = "
SELECT DISTINCT
    #* 
    catalog,catalog_code,model_code,prod_start,prod_end,frame,sysopt,compl_code,engine1,engine2,body,grade,atm_mtm,trans,f1,f2,f3,f4,f5
  FROM johokt
  WHERE catalog = '$catalog'
    AND catalog_code = '$catalog_code';
";
// johokt.vin8 - нужно отбросить, пользователь не занет vin8 на этом этапе
// johokt.sysopt - довольно таки важное поле, оно отбирает запчасти на совместимость по HNB.sysopt

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['f_exec_name'] = "catalog~catalog_code~model_code~sysopt";
$params['exec_module'] = "Characteristics.php";
$params['img4demo'] = ''; //basename(__FILE__, ".php");  // определить имя рисунка
ExecQuery($params);

?>