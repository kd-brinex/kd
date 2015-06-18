<?php
/************************************************************
 * view module
 * bunak - tecdoc@ukr.net
 *		Применяемость номера к авто
 ************************************************************/ 
  
include_once("index_ini.php");

$part_num = (isset($_REQUEST["part_num"]) ? $_REQUEST["part_num"] : ''); 

// покажем форму для поиска
$_TEMPL=array();
$_TEMPL['part_num'] = $part_num;
include "templ_part_type.php";

// Применяемость запчасти к авто, делается долго
$part_type_res = _TOY_part_type(array('part_code' => $part_num));
if(empty($part_type_res)){
  echo "Result is empty";
  exit;
}

$part_type_res_h = array_keys(current($part_type_res));
echo array_h_2_html($part_type_res, $part_type_res_h);
	
?>