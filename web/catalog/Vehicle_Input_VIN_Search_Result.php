<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/
 
include_once("index_ini.php");

$vin = $_GET['vin'];
?>

<h3 style="margin-top:-30px;">Vehicle Input VIN Search Result</h3>
<form method="GET" action="Vehicle_Input_VIN_Search_Result.php">
	<input type="text" id="vin" name="vin" value="<?php echo $vin ?>" size="30" /> 	
	<input type="submit" value="Поиск " /> 
</form>
<hr />

<?php
/*----------------------------------------------
Итого определено что поиск нужно делать в 3 подхода:
Если Первый не дает результата, нужно делать 2, если 2 не дал результата тогда пробывать искать по 3 методу, если он без результатный, значит VIN-поиск - без результата.
1. johokt + frames (это и было раньше)
2. johokt + framno (только если 1 не результативный). Может быть в ввиде списка возможных моделей!!! [Vehicle Model Select]
3. johovn (только если первый а затем уже и второй без результатов)
----------------------------------------------*/


/*----------------------------------------------
I.   САМЫЙ ТОЧНЫЙ ПОИСК
SB1BZ56L10E007267, SB153SBK1OE045795
----------------------------------------------*/

$vin_list = _TOY_VIN_info(array('vin' => $vin));
if(!empty($vin_list)){
  echo "<H3>I. SHAMEI, JOHOKT, FRAMES</H3>";
  
	// добавим поле EXEC
	$vin_list_h = array_keys($vin_list[0]);
	$vin_list_h = array_merge(array('exec'), $vin_list_h);

	// 16.02.2013
	// !!! Теперь внимание
	// Я подготовил скрипты для того чтобы вы могли легко внедрять ЧПУ.
	// По идее мы должны получить одну запись VIN для региона ("EU", "GR", "JP", "US") - 
	// 		и в дальнейшем этого будет достаточно для получения всей информации про авто. 
	// 		Тогда мы можем передать параметры урла только $vin и выбранный пользователем регион ~catalog.
	//		И в нужных модулях вызывать функцию _TOY_VIN_info(array('vin' => $vin, 'catalog' => $catalog))
	// НО вдруг была не одна запись для региона - 
	//		тогда vin - бесполезен и нужно передавать весь гамуз vdate~siyopt_code~catalog~catalog_code~model_code~sysopt~compl_code,
	//		да еще и поровозом как информативное поле - $vin
	// Я на даннный момент не заморачивался с этим вопросом, но, кому интерессно, 
	// 		то нужно просто сделать проверку чтобы в одном регионе была 1 запись VIN -  и тогда спокойно передавайте только $vin,$catalog
	for($i=0; $i < count($vin_list); $i++){
		// EXEC на замену
		$url = "<a href='Illustrated_Index.php?vin=$vin&vdate=".$vin_list[$i]['vdate']."&siyopt_code=".$vin_list[$i]['siyopt_code']."&";
		$url .= "catalog=".$vin_list[$i]['catalog']."&catalog_code=".$vin_list[$i]['catalog_code']."&model_code=".$vin_list[$i]['model_code']."&";
		$url .= "sysopt=".$vin_list[$i]['sysopt']."&compl_code=".$vin_list[$i]['compl_code']."'>Exec</a>";
		
		$vin_list[$i] = array_merge(array('exec'=>$url), $vin_list[$i]);
	}

	echo array_h_2_html($vin_list,$vin_list_h); 
  return;
}


/*************************************************************************************************
II. johokt + framno (только если 1 не результативный). 
Имменно этот результат поиска по VIN показывается ввиде списка возможных моделей!!! [Vehicle Model Select]

JTDBE30K00U001066
JTMHV05J204157043
JTEBU29J605089848
JF1ZN12A605089848 - 6 разных фреймов
*/
echo "<br /><hr /><H3>II. JOHOKT, FRAMENO, SHAMEI</H3>";

$vin_frame_list = _TOY_VIN_FRAME_info(array('vin' => $vin)); // if created MySQL function get_vdate_frameno
//$vin_frame_list = _TOY_VIN_FRAME_info_nofunc(array('vin' => $vin)); // if no created MySQL function get_vdate_frameno
if(!empty($vin_frame_list)){
	// добавим поле EXEC
  $vin_list_h = array_keys($vin_frame_list[0]);
  $vin_list_h = array_merge(array('exec'), $vin_list_h);
  
  foreach($vin_frame_list as $i => $row){
    // EXEC на замену
    $url = "<a href='Illustrated_Index.php?vin=$vin&catalog=".$row['catalog']."&catalog_code=".$row['catalog_code'];
    $url .= "&model_code=".$row['model_code']."&sysopt=".$row['sysopt']."&compl_code=".$row['compl_code']."&vdate=".$row['vdate'];
    $url .= "'>Exec</a>";
    
    $vin_frame_list[$i] = array_merge(array('exec'=>$url), $row);
  }

  echo array_h_2_html($vin_frame_list,$vin_list_h); 
  return;
}


//----------------------------------------------
// III. 
echo "<hr /><H3>III. JOHOVN, SHAMEI</H3>";
echo "<font color='red'><b>Показывает список моделей, которые юзер будет выбирать если не будет точного поиска по вину.</b></font>";

$vin_model_list = _TOY_VIN_MODEL_info(array('vin' => $vin));
if(!empty($vin_model_list)){
	// добавим поле EXEC
  $vin_list_h = array_keys($vin_model_list[0]);
  $vin_list_h = array_merge(array('exec'), $vin_list_h);
  
  foreach($vin_model_list as $i => $row){
    // EXEC на замену
    $url = "<a href='Characteristics.php?vin=$vin&catalog=".$row['catalog']."&catalog_code=".$row['catalog_code'];
    $url .= "&model_code=".$row['model_code'];
    $url .= "'>Exec</a>";
    
    $vin_model_list[$i] = array_merge(array('exec'=>$url), $row);
  }

  echo array_h_2_html($vin_model_list,$vin_list_h); 
}

?>