<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 * 2012-05-03
 ************************************************************/ 
 
include_once("index_ini.php");

$vin = (isset($_GET['vin']) ? $_GET['vin'] : "JTJBT20X740046047"); // VIN
$pnc = (isset($_GET['pnc']) ? $_GET['pnc'] : "86841"); // PNC
?>

<div style="background:Pink;">
<form method="GET" action="index_VIN_PNC.php">
<h4>VIN + Part Name Code Search: </h4> 
	VIN <input type="text" name="vin" value="<?php echo $vin ?>" size="30" /> 	
	PNC <input type="text" name="pnc" value="<?php echo $pnc ?>" size="10" /> 	
	<input type="submit" value="Поиск " /> 
</form>
<br/>
</div>
<br/>

<?php
//----------------------------------------------
// I.   САМЫЙ ТОЧНЫЙ ПОИСК
// Запрос EPC Toyota Найти АВТО
echo "<hr/><h3>VIN Result</h3>";

$vin_list = _TOY_VIN_info(array('vin' => $vin));

//if (empty($res_query)) exit;
if(count($vin_list) == 0){
	echo "VIN-поиск не дал результата!";
	exit;
}

//$parts_h[] = 'exec';   // сначала поле EXEC
$vin_list_h = array_keys($vin_list[0]);
$vin_list_h = array_merge(array('exec'), $vin_list_h);

for($i=0; $i < count($i); $i++){
	// EXEC на замену
	$url = "<a href='Illustrated_Index.php?vin=$vin&vdate=".$vin_list[$i]['vdate']."&siyopt_code=".$vin_list[$i]['siyopt_code']."&";
	$url .= "catalog=".$vin_list[$i]['catalog']."&catalog_code=".$vin_list[$i]['catalog_code']."&model_code=".$vin_list[$i]['model_code']."&";
	$url .= "sysopt=".$vin_list[$i]['sysopt']."&compl_code=".$vin_list[$i]['compl_code']."'>Exec</a>";
	
	$vin_list[$i] = array_merge(array('exec'=>$url), $vin_list[$i]);
}

echo array_h_2_html($vin_list,$vin_list_h); 


// $res_query - может иметь несколько записей если ВИН подходит для разных регионов
foreach($vin_list as $res_VIN){
$siyopt_code = $res_VIN['siyopt_code'];
$model_code = $res_VIN['model_code'];
$catalog = $res_VIN['catalog'];
$catalog_code = $res_VIN['catalog_code'];
$sysopt = $res_VIN['sysopt'];
$vdate = $res_VIN['vdate'];
//----------------------------------------------
// ТУПО КУСОК  из Parts_Number_Translation_Results.php
//----------------------------------------------

/* Запрос EPC Toyota */
$query = "
SELECT
	hnb.*, 
	hinmei.desc_en 	PartName,

	# опции установки part_code
	IF(
		(hnb.field_type = 1                    # только проверять системные строки hnb.field_type == 1
            AND IFNULL(hnb.siyopt2,'') <> ''), # делать подзапрос только если задано hnb.siyopt2
		(SELECT COUNT(*) 
			FROM siyopt 
			WHERE siyopt.catalog = hnb.catalog 
				AND siyopt.siyopt = hnb.sysopt 
				AND siyopt.siyopt_code = CONCAT(hnb.sysopt, hnb.siyopt2)),
		0
	  ) hnb_siyopt_cnt,

	# состыковка опции установки VIN + part_code
	IF(
		(hnb.field_type = 1                    # только проверять системные строки hnb.field_type == 1
            AND IFNULL(hnb.siyopt2,'') <> ''), # делать подзапрос только если задано hnb.siyopt2
		(SELECT COUNT(*) 
			FROM siyopt hnb_so
				JOIN siyopt VIN_so
					ON VIN_so.catalog = hnb_so.catalog
					AND VIN_so.siyopt = hnb_so.siyopt
					AND VIN_so.siyopt_value = hnb_so.siyopt_value
			WHERE hnb_so.catalog = hnb.catalog 
				AND hnb_so.siyopt = hnb.sysopt 
				AND hnb_so.siyopt_code = CONCAT(hnb.sysopt, hnb.siyopt2)
				AND VIN_so.siyopt_code = '$siyopt_code'),
		0
      ) hnb_vin_siyopt_cnt,
				
	# применяемость запчастей относительно типу комплектации выбранной модели авто
	IF(
		(hnb.field_type = 1                         # только проверять системные строки hnb.field_type == 1
            AND SUBSTRING(hnb.add_desc,1,6) <> ''), # делать подзапрос только если задано @ipic_code
		EXISTS(SELECT kpt.compl_code 
			FROM kpt
				JOIN johokt 
				  ON johokt.catalog = kpt.catalog
				  AND johokt.catalog_code = kpt.catalog_code
				  AND johokt.compl_code = kpt.compl_code  
			WHERE kpt.catalog = hnb.catalog
			  AND kpt.catalog_code = hnb.catalog_code
			  AND kpt.ipic_code = SUBSTRING(hnb.add_desc,1,6) # маска применяемости запчасти
			AND johokt.model_code = '$model_code'),
		0
	) compl_exist
	
  FROM hnb

    # название
    LEFT OUTER JOIN hinmei 
      ON hinmei.catalog = hnb.catalog 
      AND hinmei.pnc = hnb.pnc

  WHERE hnb.catalog = '$catalog'
    AND hnb.catalog_code = '$catalog_code'
	AND hnb.pnc = '$pnc' 
";
/*
Сортировать и группировать ЗАПРЕЩЕНО !!!
# GROUP BY hnb.catalog, hnb.catalog_code, hnb.pnc, hnb.sysopt, hnb.part_code, hnb.quantity, hnb.start_date, hnb.end_date, hnb.field_type, hnb.siyopt2
# ORDER BY hnb.catalog, hnb.catalog_code, hnb.pnc, hnb.sysopt, hnb.part_code, hnb.quantity, hnb.start_date, hnb.end_date, hnb.field_type
*/
// выполним
$res_query = _mysql_query($query, false);


// ВЫВОД НАИМЕНОВНАИЕ КОЛОНОК
$parts_h 	= array(); // наименование полей
$parts_h[] = 'exec'; 		// сначала поле EXEC
$numfields = mysql_num_fields($res_query);
for ($i=0; $i < $numfields; $i++){
	$parts_h[] = mysql_field_name($res_query, $i);
};
$parts_h[] = 'mode_des'; 	// еще сделаем поле куда сложим описания Model (Description)
$parts_h[] = 'conditions'; 	// Conditions


// ------------------------------
// ОБРАБОТКА РЕЗУЛЬАТА
$_data = array(); 		// временная строка
$_data_res = array(); 	// результат обработки запроса
$parts_data = array(); 	// Окончательный результат
while ($p_r = mysql_fetch_array($res_query, MYSQL_ASSOC)) {
	
	// field_type == 1 - системная запись, именно она может определает Condishion запчасти
	if($p_r['field_type'] == 1){
	
		if(!empty($_data)) $_data_res[] = $_data; // если мы сюда попали значит это уже слежующая запчасть
		$_data['_exec'] = "";				// подготовим первое поле для Exec
		$_data = array_merge($_data, $p_r);	// заберем всю системную запись
		$_data['mode_des'] = ''; 			// подготовимся еще дописать Model (Description)

		// EXEC на замену
		$_data['_exec'] = "<a href='Part_Subs.php?catalog=".$p_r['catalog']."&part_code=".$p_r['part_code']."'>Subs/Procr</a>";

		$_br = ''; // сделаем красоту		
		
		continue;
	}

		/* 	НУЖНО ПРОВЕРИТЬ - АВТО SUBS
		JTHBH96S605055732 pnc - 53801
		2 запчасти которые вывалило наша БД - это заменяемые, проверяешь по DAIHIN, если типо в списке - объеденяешь как одну
		*/	
		
	$_data['mode_des'] .= $_br . $p_r['add_desc']; // заберем только описания 
	$_br = '<br/>'; // сделаем красоту
}
if(!empty($_data)) $_data_res[] = $_data; // самая ПОСЛЕДНЯЯ запчасть вставить


/* !!! IMPORTANT

WEIGT OF COMPARABILITY PART_NUMBERS TO VIN
index_VIN_PNC.php?vin=JTEBZ29J400108770&pnc=52119A

Show more parts then EPC Toyota.

I have some theory about this. I try explain you. Look carefully to result on
http://212.90.37.5/_toyota/index_VIN_PNC.php?vin=JTEBZ29J400108770&pnc=52119A

result have some system fields - hnb_siyopt_cnt and hnb_vin_siyopt_cnt
There are weight of comparability part_number to VIN. When greater then more comparabilit.

So for result vin=JTEBZ29J400108770&pnc=52119A
part number 5211960945 - have greatest weight (2) with compare to other parts (1). So 5211960945 - more comparability part.
*/
foreach($_data_res as $_data){
	// ! ВНИМАНИЕ эксперемент
	// 		было замечено что EPC вычищает из списка запчасти, предположительно из-за того что в add_desc в позиции 80 стоит символ=1(маска)
	//		например
	//			vin: JTHGL46F205032314 pnc: 48510 hnb.add_desc: [00003F                                                                         01]
	//		я попробывал выбрать запчасти через каталог (без поиска по VIN) - результат тот же, запчасти hnb с 1 в конце не показываются
	//		а потом сделал поиск 4851080340 по применяемости к моделям, показала только применяемость к каталогу 431240 модели авто
	// 		Russion Spec не было в списке, и других тоже с 1 в конце, так что теория наша очень даже верна
	if(substr($_data['add_desc'],80,1) == '1') continue;

	
	
	/* ------------------------------
	ПРОВЕРКА CONDITIONS
	(3)Display conditions
	Match on: Displays the Part Number reduced by Maker Option (-Single part no. identification function)
		-When the target catalogs for the single part no. identification function are searched by Frame or VIN Numbers, the search will be carried out with this condition. 
	Spec off: Displays the Part Number reduced by the input Model
	Match off: Displays all Part Number for the currently displayed Fig-No.
	In case of Spec off/Match off, please confirm the actual vehiclein the same way as before. 
	---- */
						// # применяема ли запчасть вообще к выбранной модели авто
	if($_data['compl_exist'] == 0){	
		$_data['conditions'] = 'MatchOff';
						// уточням запчасти по индексу комплектации johokt.sysopt == hnb.sysopt 
						// vin: JTHBH96S605055732 pnc: 53801 => получаем 2 MatchOn = 5380130A10, 5380130A00 
						// но 5380130A00 - точно не матчОн поскольку у него (HNB.sysopt = 413W) <> johokt.sysopt 
						// johokt.sysopt = 1-4 символа из frames.siyopt_code substr($siyopt_code,0,4)
						// 5380130A10 - MatchOn, у него (HNB.sysopt = 317W) == johokt.sysopt
	} elseif($_data['sysopt'] != $sysopt){
		$_data['conditions'] = 'MatchOff';
						// делался ли поиск по VIN (сделал на базе проверки дати производства VIN авто, можно и другое :),
						// если нет то 'MatchOn' - никогда не будет
	} elseif(empty($vdate)) {		
		$_data['conditions'] = 'SpecOff';
						// # применяемость запчастей по дате производства VIN авто, елси не попало в дату - то нах
	} elseif(($vdate < $_data['start_date']) or ($_data['end_date'] < $vdate)){
		$_data['conditions'] = 'MatchOff';
						// # количество состыковки опций установки запчасти к VIN обязательно должны совпадать
	} elseif($_data['hnb_siyopt_cnt'] == $_data['hnb_vin_siyopt_cnt']){
		$_data['conditions'] = 'MatchOn';
						// больше не куда фильтровать
	} else{
		$_data['conditions'] = 'SpecOff';
	}
	
	$parts_data[] = $_data;
}
//var_dump($parts_data);
//die();


// ------------------------------
// ВЫВОД РЕЗУЛЬАТА
$params = array();
// подсветить conditions
$params['f_sel_name'] = "conditions";
$params['f_sel_val_col'] = array(
		'MatchOn' => "LimeGreen",
		'SpecOff' => "CornflowerBlue",
		'MatchOff' => ""
		);

echo "<br/><hr/> Результат Поиска VIN:$vin Catalog:$catalog PNC:$pnc";
$parts_matchon = array(); 	// результат обработки запроса
foreach($parts_data as $_p_d){
	if($_p_d['conditions'] == 'MatchOff') continue;
	$parts_matchon[] = $_p_d;
}

echo array_h_2_html($parts_matchon,$parts_h,$params);
}

/*
// тестировать 	
$params = array();
$params['is_show_sql'] = 0;
$params['query'] = $query;
ExecQuery($params);*/

?>