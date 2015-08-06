<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *		Заход в модуль через выбранную Модель = _Characteristics.php_
 *		Заход в модуль через позитивный VIN-I = _Vehicle_Input_VIN_1.php_
 *		Заход в модуль через VIN-II = _Vehicle_Input_VIN_Search_Result.php_
 ************************************************************/
  
 
include_once("index_ini.php");

$catalog = $_GET['catalog'];
$catalog_code = $_GET['catalog_code'];
$model_code = $_GET['model_code'];
$sysopt = $_GET['sysopt'];			// спецификация
$compl_code = $_GET['compl_code']; 	// комплектация


// в Vehicle_Input_VIN_Search_Result.php - расписана возможность реализации скриптов для ЧПУ
$vin = (isset($_GET['vin']) ? $_GET['vin'] : ""); // VIN (будет задано только при поиске по VIN)
$vdate = (isset($_GET['vdate']) ? $_GET['vdate'] : ""); // дата выпуска VIN (будет задано только при поиске по VIN)
$siyopt_code = (isset($_GET['siyopt_code']) ? $_GET['siyopt_code'] : ""); // опция комплектации VIN (будет задано только при поиске по VIN)
?>

<div style="background:CornflowerBlue; margin-top:-40px;">
<h2>PNC Impurt</h2>
<form method="GET" action="Parts_Number_Translation_Results.php">
<h4>Part Name Code Search: 
	<input type="text" name="pnc" value="48510" size="10" /> 	
	<input type="submit" value="Поиск " /> 
	
	<input type="hidden" name="vin" value="<?php echo $vin ?>">
	<input type="hidden" name="vdate" value="<?php echo $vdate ?>">
	<input type="hidden" name="catalog" value="<?php echo $catalog ?>">
	<input type="hidden" name="catalog_code" value="<?php echo $catalog_code ?>">
	<input type="hidden" name="model_code" value="<?php echo $model_code ?>">
	<input type="hidden" name="sysopt" value="<?php echo $sysopt ?>">
	<input type="hidden" name="siyopt_code" value="<?php echo $siyopt_code ?>">
</h4> 
</form>
<hr/>
</div>


<!-- ---------------------------------- Illustrated Index ------------------------------------------- -->
<h2>Illustrated Index</h2>
Разделы групп<br/>
Emi.main_group = 1 – Tool/Engine/Fuel<br/>
Emi.main_group = 2 – Power Train/Chassis<br/>
Emi.main_group = 3 – Body<br/>
Emi.main_group = 4 – Electrical<br/><br/>


<?php
// 14.02.2012
//<h4>EMI, FIGMEI</h4>
// ОТБОР групп по дате. Можно ЕЩЕ группы тоже ограничивають по соответвию даты подгруппы-иллюстрации к датам выбранной модели или VIN

// Дату модели нужно получить, только если не известно дата VIN
if (empty($vdate)){
	$mod_info = _TOY_johokt_model_info(array(
		'catalog'		=>$catalog, 
		'catalog_code'	=>$catalog_code,
		'model_code'	=>$model_code,
		'sysopt'		=>$sysopt,
		'compl_code'	=>$compl_code,
		));
	//var_dump($mod_info);
}

/* Запрос EPC Toyota */
$query = "
SELECT emi.*, figmei.desc_en
  FROM emi
    LEFT JOIN figmei 
      ON figmei.catalog = emi.catalog
      AND figmei.part_group = emi.part_group
  WHERE emi.catalog = '$catalog'
    AND emi.catalog_code = '$catalog_code'

    #применяемость групп  если не нужна то просто вместо $compl_code -> '' или удалить этот блок
    AND (('$compl_code' = '') OR EXISTS(
      SELECT 
        bzi.ipic_code     
      FROM 
        bzi #список всех подгрупп(иллюстраций)

        # применяемость подгруппы запчастей
      	JOIN kpt 
      	  ON kpt.catalog = bzi.catalog
      	  AND kpt.catalog_code = bzi.catalog_code
      	  AND kpt.ipic_code = bzi.ipic_code # маска применяемости
          AND kpt.compl_code = '$compl_code'

      WHERE
        bzi.catalog = emi.catalog
        AND bzi.catalog_code = emi.catalog_code
        AND bzi.part_group = emi.part_group";
// 	отработать по дате модели, если не известна дата VIN --
// 		пустых дат нету	, поэтому споконо берем эти поля
//			SELECT * FROM johokt WHERE IFNULL(prod_start,'') = ''
//			SELECT * FROM johokt WHERE IFNULL(prod_end,'') = ''
// 	!empty($mod_info) - проверим вдруг модель пустая
if (empty($vdate) and !empty($mod_info)) $query .= "
        # При поиске по дате модели в приделах даты модели
        AND (bzi.start_date <= '".$mod_info['prod_end']."' AND bzi.end_date >='".$mod_info['prod_start']."')";
// 	отработать по дате VIN --
if (!empty($vdate)) $query .= "
        # При поиске по VIN - в приделах даты выпуска авто
        AND ('$vdate' BETWEEN bzi.start_date AND bzi.end_date)";
$query .= "
      ))
ORDER BY main_group, part_group
";



$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['url_main_params'] = "vin=$vin&vdate=$vdate&siyopt_code=$siyopt_code&catalog=$catalog&catalog_code=$catalog_code&model_code=$model_code&sysopt=$sysopt&compl_code=$compl_code&";
$params['f_exec_name'] = "part_group";
$params['exec_module'] = "Figure.php";
$params['img_row_path'] = _IMG_IllSTR_INDEX_PATH . "ImgIllIndex/$catalog/$catalog_code/" ; // путь к рисункам ImgIllIndex
$params['img_row_file'] = "pic_code"; // путь к рисункам
$params['img_ext'] = ".png"; // расширение рисунка
$params['img4demo'] = ''; //basename(__FILE__, ".php");  // определить имя рисунка
ExecQuery($params);

?>