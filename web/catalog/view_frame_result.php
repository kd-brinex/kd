<?php
/************************************************************
 * view module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/
 
include_once("index_ini.php");

$frame = (isset($_REQUEST["frame"]) ? $_REQUEST["frame"] : ''); 
$fr_number = (isset($_REQUEST["fr_number"]) ? $_REQUEST["fr_number"] : ''); 

// покажем форму для поиска
$_TEMPL=array();
$_TEMPL['frame'] = $frame;
$_TEMPL['fr_number'] = $fr_number;
include "templ_freame_search.php";

// Frame
$frame_res = _TOY_frame_info(array('frame_code' => $frame, 'serial_number' => $fr_number));
$frame_res_h = array_keys(current($frame_res));

// может быть несколько записей, например разные регионы
// view_frame_result.php?frame=ACA20&fr_number=0015070
foreach($frame_res as $_k => $_frame){
	// EXEC на замену
	$url = "<a href='Illustrated_Index.php?";
	$url .= "catalog=".$_frame['catalog'];
	$url .= "&model_code=".$_frame['model_code'];
	$url .= "&vdate=".$_frame['vdate'];
	$url .= "&siyopt_code=".$_frame['siyopt_code'];
	$url .= "&catalog_code=".$_frame['catalog_code'];
	$url .= "&sysopt=".$_frame['sysopt'];
	$url .= "&compl_code=".$_frame['compl_code'];
	$url .= "'>".$_frame['model_code']."</a>";

	$frame_res[$_k]['model_code'] = $url;
}


echo array_h_2_html($frame_res, $frame_res_h);

?>
