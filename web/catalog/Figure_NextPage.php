<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/
  
 
include_once("index_ini.php");

$catalog 		= $_GET['catalog'];
$catalog_code 	= $_GET['catalog_code'];
$model_code 	= $_GET['model_code'];
$sysopt 		= $_GET['sysopt'];
$compl_code 	= $_GET['compl_code'];
$part_group 	= $_GET['part_group'];
$pic_code 		= $_GET['pic_code'];

$vin 		= (isset($_GET['vin']) ? $_GET['vin'] : ""); // дата выпуска VIN (будет задано только при поиске по VIN)
$vdate		= (isset($_GET['vdate']) ? $_GET['vdate'] : ""); // дата выпуска VIN (будет задано только при поиске по VIN)
$siyopt_code= (isset($_GET['siyopt_code']) ? $_GET['siyopt_code'] : ""); // опция комплектации VIN (будет задано только при поиске по VIN)

//$disk_num = $_GET['rec_num']; // задается в самом начале Catalog_Code_Select.php (поле shame.rec_num)
$cat_info = _TOY_shamei_info(array('catalog'=>$catalog, 'catalog_code'=>$catalog_code)); // сократим GET переменные, улучшим заказчикам SEO
$disk_num = $cat_info['rec_num'];


/***********************************************************/
echo "<h3 style='margin-top:-30px;'>Images</h3>";
echo "Информация про картинку";

/* Запрос EPC Toyota */
$query = "
SELECT *
  FROM images
  WHERE catalog = '$catalog'
    AND `disk` = '$disk_num'
    AND `pic_code` = '$pic_code';
";

$params = array();
$params['query'] = $query;
ExecQuery($params);


/***********************************************************
  РИСУНОК	
 ***********************************************************/
echo "<img src=\"" . _IMG_IllSTR_INDEX_PATH . "Img/$catalog/$disk_num/$pic_code.png\" width=\"800\" border=\"1\">";

/***********************************************************/
echo "<h4>PNC-List</h4>";
echo "** Refer Fig - Это код группы из Illustrated_Index, и тогда нужно делать ссылку";

//Figure.php?catalog=EU&catalog_code=281220&rec_num=B2&model_code=SV11R-UHMEEQ&part_group=1104&
$ulr_main = "vin=$vin&vdate=$vdate&siyopt_code=$siyopt_code&catalog=$catalog&catalog_code=$catalog_code&model_code=$model_code&sysopt=$sysopt&compl_code=$compl_code&";

/* Запрос EPC Toyota */
$query = "
SELECT img_nums.*, 
	CASE `number_type` 
		WHEN '1' THEN
			# сделать переход на группу что указана в img_nums.number
			CONCAT('<a href=\"Figure.php?".$ulr_main."part_group=', img_nums.number, '\">** Refer Fig<a/>')
#			'** Refer Fig'
		WHEN '4' THEN '** Std Part'
		ELSE hinmei.desc_en
	END desc_en,
	img_nums.number AS pnc
  FROM img_nums
    LEFT JOIN hinmei
      ON hinmei.catalog = img_nums.catalog AND hinmei.pnc = img_nums.number
  WHERE img_nums.`catalog` = '$catalog'
    AND `disk` = '$disk_num'
    AND `pic_code` = '$pic_code';
";

$params = array();
$params['query'] = $query;
$params['f_sel_name'] = "";
$params['f_sel_value'] = "";
$params['url_main_params'] = "vin=$vin&vdate=$vdate&siyopt_code=$siyopt_code&catalog=$catalog&catalog_code=$catalog_code&model_code=$model_code&sysopt=$sysopt&compl_code=$compl_code&part_group=$part_group&pic_code=$pic_code&";
$params['f_exec_name'] = "pnc";
$params['exec_module'] = "Parts_Number_Translation_Results.php";
$params['img_row_path'] = ""; // путь к рисункам
$params['img_row_file'] = ""; // путь к рисункам
$params['img4demo'] = "";  // определить имя рисунка
ExecQuery($params);

?>