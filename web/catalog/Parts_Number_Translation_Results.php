<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<a href="index.php"><b><<<--- GO TO START</b></a>

<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *	06-02-2013
 ************************************************************/
  
include_once("index_ini.php");

$catalog 		= $_GET['catalog'];
$catalog_code 	= $_GET['catalog_code'];
$model_code 	= $_GET['model_code'];
$sysopt 		= (isset($_GET['sysopt']) ? $_GET['sysopt'] : "");
//$part_group = $_GET['part_group'];
$pnc 			= $_GET['pnc'];

$vin 		= (isset($_GET['vin']) ? $_GET['vin'] : ""); // дата выпуска VIN (будет задано только при поиске по VIN)
$vdate 		= (isset($_GET['vdate']) ? $_GET['vdate'] : ""); // дата выпуска VIN (будет задано только при поиске по VIN)
$siyopt_code = (isset($_GET['siyopt_code']) ? $_GET['siyopt_code'] : ""); // опция комплектации VIN (будет задано только при поиске по VIN)

if(!empty($vin)) echo "<h3>Поиск по VIN: $vin</h3>";

//----------------------------------------------
echo "<h3>Информация о выбранном автомобиле</h3>";

/* Запрос EPC Toyota */
$query = "
SELECT 
	shamei.*, # информация про модель
	johokt.*  # информация про серюю модели
  FROM johokt
  
	JOIN shamei
	  ON shamei.catalog = johokt.catalog
	  AND shamei.catalog_code = johokt.catalog_code
  WHERE johokt.catalog = '$catalog'
    AND johokt.catalog_code = '$catalog_code'
    AND johokt.model_code = '$model_code'
    # условие выбранной sysopt модели
    AND johokt.sysopt = '$sysopt'
";
// johokt.sysopt = '$sysopt' нужно сверять именно sysopt, даже если оно и пустое
//		$siyopt_code[1-4] - не подходит посокльку johokt может быть с пустым sysopt, тогда как freim.sysopt - будет задано

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['f_sel_color'] = "";
$params['f_exec_name'] = "";
$params['exec_module'] = "";
$params['img4demo'] = ''; //basename(__FILE__, ".php");  // определить имя рисунка
ExecQuery($params);

?>

<br />
<div style="background:Pink;">
<hr /><h2>PNC Impurt</h2>
<form method="GET" action="Parts_Number_Translation_Results.php">
<h4>Part Name Code Search: 
	<input type="text" name="pnc" value="<?php echo $pnc ?>" size="10" /> 	
	<input type="submit" value="Поиск " /> 
	
	<input type="hidden" name="vin" value="<?php echo $vin ?>">
	<input type="hidden" name="vdate" value="<?php echo $vdate ?>">
	<input type="hidden" name="catalog" value="<?php echo $catalog ?>">
	<input type="hidden" name="catalog_code" value="<?php echo $catalog_code ?>">
	<input type="hidden" name="model_code" value="<?php echo $model_code ?>">
	<input type="hidden" name="siyopt_code" value="<?php echo $siyopt_code ?>">
</h4> 
</form>
<hr/>
</div>

<?php
//----------------------------------------------
echo "<h3>HNB, KPT - Parts Number Translation Results</h3>";
echo "<b>MatchOff</b> - включает в себя SpecOn (выводит все запчасти для каталога разборки, вывод ВСЕГО!!! )
<br/><b>SpecOn</b> - включает себя Match on (выводит запчасти для каталога + выбранной модели авто)
<br/><b>Match on</b> - самое жесткое соответвии запчати к Вину (включаеться при поиске машин по ВИНУ)";

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
			  AND johokt.model_code = '$model_code'
			  AND johokt.sysopt = '$sysopt'), # уточним johokt
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
	запрос возвращает записи в той очердности в которой было заложено в БД из EPC. 
	ORDER в запрос вставлять нельзя, иначе сбивается системный порядок
GROUP BY hnb.catalog, hnb.catalog_code, hnb.pnc, hnb.sysopt, hnb.part_code, hnb.quantity, hnb.start_date, hnb.end_date, hnb.field_type, hnb.siyopt2
ORDER BY hnb.catalog, hnb.catalog_code, hnb.pnc, hnb.sysopt, hnb.part_code, hnb.quantity, hnb.start_date, hnb.end_date, hnb.field_type

	compl_exist - уточним еще соместимость (AND johokt.sysopt = '$sysopt')
*/
// выполним
$res_query = _mysql_query($query);


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
//var_dump($p_r);
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

/* !!!!!!
	НУЖНО сделать - АВТО SUBS
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


/*

- по хорошему надо ещё фильтрануть по trim-коду и коду цвета
*/


						// # применяема ли запчасть вообще к выбранной модели авто
						// в KPT находим индексы применяемости по индексу модели
	if($_data['compl_exist'] == 0){	
		$_data['conditions'] = 'MatchOff';
						// уточняем запчасти по спецификации frames.siyopt_code[1-4] == hnb.sysopt если поиск по VIN или johokt.sysopt == hnb.sysopt 
						// vin: JTHBH96S605055732 pnc: 53801 => получаем 2 MatchOn = 5380130A10, 5380130A00 
						// 		5380130A00 - точно не матчОн поскольку у него (HNB.sysopt = 413W) <> johokt.sysopt 
						// 		5380130A10 - MatchOn, у него (HNB.sysopt = 317W) == johokt.sysopt
						// Приорететнее проверить спицификацию по VIN, а затем уже по модели
						//
						// 16.02.2013
						// пример ниже показываает что для 'SpecOff' нужно проверять !empty($sysopt)
						// http://localhost/www-php/www-php-Toyota_EPC/interface/Parts_Number_Translation_Results.php? vin=&vdate=&siyopt_code=&catalog=EU&catalog_code=284570&model_code=ACV30R-AEPNKW&sysopt=&compl_code=004&part_group=1103&pic_code=113134&pnc=04111&
						// !empty($siyopt_code) - походу тоже сделал проверку
	} elseif((!empty($siyopt_code) and $_data['sysopt'] != substr($siyopt_code,0,4)) 
			and (!empty($sysopt) and $_data['sysopt'] != $sysopt)){
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
	//echo $_data['sysopt'] . substr($siyopt_code,0,4) . $_data['sysopt'] . $sysopt . "<br>";
	
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

echo array_h_2_html($parts_data,$parts_h,$params);

/*	
$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "conditions";

// 	отработать VIN
$params['f_sel_val_col'] = array(
		'MatchOn' => "LimeGreen",
		'SpecOff' => "CornflowerBlue",
		'MatchOff' => "yellow"
		);

$params['f_exec_name'] = "part_code";
$params['exec_module'] = "Part_Subs.php";
$params['img4demo'] = "";  // определить имя рисунка
ExecQuery($params);


<h4>How concut rows</h4>
For better understending loock for index_VIN_PNC.php. It show how you may correctly concut info in add_desc. So, data about parts in hnb-tables separated by systems rows field_type=1. All next rows (they have field_type=2) to new systems row are info rows for user. All text in add_desc are need concut.
<br/>So for example part 7544260150 have full description HDJ100, UZ100..VX(row=2) LAND (row=3)

<br/>
<hr /><h3>RYAKUG - Abbreviation Search</h3>
<br/>hnb.add_desc - может содержать аббревиатуры. Строчку нужно парсить на наличие кодов, начинаются с символа * заканчиваются любым разделителем ([=], [,], [ ], [-] и т.п.).<br/>
Например «*115=TOYOTA/00-/Y»<br/><br/>

<b>SELECT * FROM ryakug WHERE catalog = 'US' AND abb IN ('*115', '115');</b><br/><br/>

catalog	abb	desc_en<br/>
US	*115	CATALYST MAKE/PRODUCTION YEAR/ONBOARD DIAGNOSIS:YES OR NO(THIS INDICATION COMPLIES WITH EC DIRECTIVE.)<br/>
US	115	CATALYST MAKE/PRODUCTION YEAR/ONBOARD DIAGNOSIS:YES OR NO(THIS INDICATION COMPLIES WITH EC DIRECTIVE.)<br/>
*/
?>


<br/><br/><a href="index.php"><b><<<--- GO TO START</b></a>
<html>