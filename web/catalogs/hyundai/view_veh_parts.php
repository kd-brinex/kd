<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Parts List</title>
<link rel="stylesheet" type="text/css" href="style.css">
<style type="text/css">
table.pnc_image_list td{
  border-bottom: 1px solid #E1E1E1;
}
table.pnc_image_list td.pnc_name{
  min-width: 200px; 
  max-width: 250px;
}
table.pnc_image_list tr.sector{
  background-color: #F0F0F0;
}
table.parts_list td, th{
  border-bottom: 1px solid #E1E1E1;
}
.pnc_name_def {
}
.pnc_name_loc {
  font-style: italic;
  ~font-size: small;
  color: #6E6E6E;
}
.part_replaced, .part_type{
  color: #810606;
}
.part_replaced{
  white-space: nowrap;
}
.options, .options_minus{
  font-weight: bold;
  text-decoration: underline;
}
.ucc_type{
  font-weight: bold;
}
.options_minus{
  color: #7A2121;
}
.options, .ucc_type{
  color: #535353;
}
</style>
</head>

<?php
/*---------------------------------------------
 * Список запчастей выбранной секции
 *  29.06.2015
 *
 --------------------------------------------*/

include_once("index_ini.php");	// иницируем sql-соеденение, создадим объекты $MC_API и $TMPL


// _REQUEST - ПАРАМЕТРЫ ВЫБОРА
// ~~~~~~~~~~~
$req_lang_code = (isset($_REQUEST["lang_code"]) ? $_REQUEST["lang_code"] : 'EN'); // по умолчанию - Английский
$req_vin = (isset($_REQUEST["vin"]) ? $_REQUEST["vin"] : '');
$req_catalog_code = (isset($_REQUEST["catalog_code"]) ? $_REQUEST["catalog_code"] : '');
/*
$req_cat_region = (isset($_REQUEST["cat_region"]) ? $_REQUEST["cat_region"] : '');    // передать дальше на запчасти
*/
$req_maj_sect = (isset($_REQUEST["maj_sect"]) ? $_REQUEST["maj_sect"] : '');
$req_min_sect = (isset($_REQUEST["min_sect"]) ? $_REQUEST["min_sect"] : '');
$req_pnc = (isset($_REQUEST["pnc"]) ? $_REQUEST["pnc"] : '');

// избирательно отбираем нужные параметры между VIN <-> catalog
$vin_result = array();
$set_veh_ucc = "";
$set_veh_build_date = "";
$set_veh_drive_type = "";
$set_veh_weather_type = "";
if(!empty($req_vin)){
  $vin_result = $MC_API->vin_result(array('vin'=>$req_vin, 'catalogue_code'=>$req_catalog_code));
  $vin_result = $vin_result[0];  // если попали на эту страницу то результ должен быть обязательным
  $set_veh_ucc = $vin_result['model']['ucc'];
  
  $set_veh_build_date = $vin_result['options']['build_date'];
  $set_veh_drive_type = $vin_result['options']['drive_type'];
  $set_veh_weather_type = $vin_result['options']['weather_type'];
} else {
  // опции комплектации авто будут использоватся параметрами url
  $catalog_ucctype_EN = $MC_API->cat_ucctype(array('lang_code'=>'EN', 'catalogue_code'=>$req_catalog_code));
  $catalog_ucctype_EN_key = array_flip_2($catalog_ucctype_EN, 'ucc_type'); //проиндексируем
//$set_veh_ucc = TMPL::get_request_vehicle_options($_REQUEST);  // get_request_vehicle_options2 - надежнее
  $set_veh_ucc = TMPL::get_request_vehicle_options2(array('request'=>$_REQUEST, 'catalog_ucctype_key'=>$catalog_ucctype_EN_key));
  
  $set_veh_build_date = (isset($_REQUEST["cat_year"]) ? $_REQUEST["cat_year"] : '');
  $set_veh_drive_type = (isset($_REQUEST["veh_drive_type"]) ? $_REQUEST["veh_drive_type"] : '');
  $set_veh_weather_type = (isset($_REQUEST["veh_weather_type"]) ? $_REQUEST["veh_weather_type"] : '');
}


// ТЕКСТЫ ИНТЕРФЕЙСА
// ~~~~~~~~~~~
$_SYSTEM_TEXT = $MC_API->mc_lexicon_system_text(array('lang_code'=>$req_lang_code)); // тексты интерфейса
$mc_lexicon_lex_languages_list = $MC_API->mc_lexicon_lex_languages_list(array('lang_code'=>$req_lang_code)); // язык интерфейса и данных


// ДАННЫЕ
// ~~~~~~~~~~~
$catalog = $MC_API->catalog_cat_catalog(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$req_catalog_code));
$catalog = $catalog[0];
$catalog_ucctype = $MC_API->cat_ucctype(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$req_catalog_code));//виды характеристик авто
//$catalog_ucctype_key = array_flip_2($catalog_ucctype, 'ucc_type'); //проиндексируем
$catalog_ucc = $MC_API->cat_ucc(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$req_catalog_code)); //харакетристики авто
$cat_options_des = $MC_API->cat_options(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$req_catalog_code));

// major section
$cat_map_major_sections = $MC_API->cat_map_major_section(array('lang_code'=>$req_lang_code, 'cat_folder'=>$catalog['cat_folder']));
$cat_major_section = $cat_map_major_sections[$req_maj_sect];
// minor sections
$cat_map_minor_sections = $MC_API->cat_map_minor_section(array(
      'lang_code'=>$req_lang_code, 'cat_folder'=>$catalog['cat_folder'], 'major_sect'=>$req_maj_sect,
      'ucc'=>$set_veh_ucc,  // применяемость ucc
      'vin'=>$vin_result,   // применяемость vin.options
    ));
$cat_minor_section = $cat_map_minor_sections[$req_min_sect];// minor selected section

// image ref elemetnts
$cat_image_pnc_ref = $MC_API->cat_dat_minor_image_pnc_ref(array('cat_folder'=>$catalog['cat_folder'], 'minor_sect'=>$req_min_sect));
$cat_image_minor_ref = $MC_API->cat_dat_minor_image_minor_ref(array(
      'lang_code'=>$req_lang_code, 'cat_folder'=>$catalog['cat_folder'], 'cat_image_ref'=>$cat_image_pnc_ref,
      'ucc'=>$set_veh_ucc,  // применяемость ucc
      'vin'=>$vin_result,   // применяемость vin.options
    ));
    
// part compatibility set
$part_compatibility = array(
      'build_date'=>$set_veh_build_date, 
      'drive_type'=>$set_veh_drive_type, 
      'weather_type'=>$set_veh_weather_type, 
      'ucc'=>$set_veh_ucc,
      'vin'=>$vin_result,
    );
// pnc список всех возможных pnc из списка запчастей
$cat_parts4pnc = $MC_API->cat_dat_parts(array('lang_code'=>$req_lang_code, 'cat_folder'=>$catalog['cat_folder'], 
                                    'minor_sect'=>$req_min_sect, 'compatibility'=>$part_compatibility ));
$cat_pnc = MC_API::cat_dat_parts_pnc($cat_parts4pnc);
// parts, если было уточнения по pnc перегружаем
$cat_parts = $cat_parts4pnc;
if(!empty($req_pnc)){
  $cat_parts = $MC_API->cat_dat_parts(array('lang_code'=>$req_lang_code, 'cat_folder'=>$catalog['cat_folder'], 
                                    'minor_sect'=>$req_min_sect, 'pnc'=>$req_pnc, 'compatibility'=>$part_compatibility));
}
//отобрать запчасти с наибольшым весом применяемости
if(!empty($vin_result)){
  $cat_parts = MC_API::cat_dat_parts_compare_wiegth($cat_parts);
}
//найти замены относительно региона
$bom_localpts = array();
if(!empty($vin_result)){  // Microcat подбирает только если был VIN-результат
  $bom_localpts = $MC_API->bom_localpts($cat_parts);
}
?>

<body>
<?php 
  echo TMPL::select_lang(array('request'=>$_REQUEST,'system_text'=>$_SYSTEM_TEXT, 'list_lang'=>$mc_lexicon_lex_languages_list));
  echo TMPL::breadcrumbs(array(
      'system_text'=>$_SYSTEM_TEXT,
      'lang_code'=>$req_lang_code,
      'vin'=>$req_vin,
      //'cat_family'=>$req_cat_family,
      //'cat_year'=>$req_cat_year,
      //'vehicle_type'=>$req_vehicle_type,
      //'cat_region'=>$req_cat_region,
      'catalog_code'=>$req_catalog_code,
      'maj_sect'=>$req_maj_sect,
  ));

/*
[+] ref <> pnc   Microcat использует при подборе запчастей и pnc и ref
~~~
view_veh_parts.php?lang_code=RU&catalog_code=HAC3108500&cat_region=HAC&maj_sect=CH&min_sect=5052911
view_veh_parts.php?lang_code=RU&catalog_code=AUS2309200&cat_year=&cat_region=AUS&vo_body_type=C&vo_grade=&vo_engine_capacity=P&vo_fuel_type=&vo_transmission=&vo_special_car=A&veh_drive_type=&maj_sect=EN&min_sect=2828211

[+]   ref_type = 5, на рисунках ссылки на minor_sect
~~~
*) 20-211A-B1 - не переходит
view_veh_parts.php?lang_code=RU&catalog_code=AUS2309200&cat_year=&cat_region=AUS&vo_body_type=C&vo_grade=&vo_engine_capacity=P&vo_fuel_type=&vo_transmission=&vo_special_car=A&veh_drive_type=&maj_sect=EN&min_sect=2828211
*) 56-571&57231 = PNC 57231 в 56-571
*) 97-976-1&97713A = PNC 97713A в 97-976-1
view_veh_parts.php?lang_code=RU&catalog_code=AUS2209500&cat_year=&cat_region=AUS&vo_body_type=&vo_grade=&vo_engine_capacity=P&vo_fuel_type=&vo_transmission=&vo_special_car=A&veh_drive_type=&maj_sect=EN&min_sect=25251A11
*) 81-819-A68 - не переходит
view_veh_parts.php?lang_code=RU&catalog_code=AUS2309200&cat_year=&cat_region=AUS&vo_body_type=C&vo_grade=&vo_engine_capacity=P&vo_fuel_type=&vo_transmission=&vo_special_car=A&veh_drive_type=&maj_sect=TR&min_sect=8181311
*) 88-890A,88-890B
view_veh_parts.php?lang_code=RU&catalog_code=GEN4A0PA02&cat_year=&cat_region=GEN&vo_body_type=&vo_grade=&vo_engine_capacity=&vo_fuel_type=&vo_transmission=&veh_drive_type=&maj_sect=TR&min_sect=8889811
*) 86-873A,86-873B
view_veh_parts.php?lang_code=RU&catalog_code=MES5A0GA98&cat_year=&cat_region=MES&vo_body_type=&vo_trim_level=1&vo_door___floor=&vo_suspension_type=A&vo_engine_capacity=&vo_vehicle_type=1&veh_drive_type=&maj_sect=TR&min_sect=81835C11

[+] replaced - Всегда задается вместе с type IN (UNIFIT, ASTREL, TURKEY, REMAN)
[+] type
~~~
*) 'ACCESSORY'
view_veh_parts.php?lang_code=RU&catalog_code=AHMAPCMA10&cat_year=&cat_region=&vo_body_type=W5&vo_engine_capacity=&vo_engine_type=6&vo_fuel_type=1&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=ZA&min_sect=ZA000018US
view_veh_parts.php?lang_code=RU&catalog_code=AHMAPCMA10&cat_year=&cat_region=&vo_body_type=W5&vo_engine_capacity=&vo_engine_type=6&vo_fuel_type=1&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=ZA&min_sect=ZA000018US
*) 'REMAN'
view_veh_parts.php?lang_code=RU&catalog_code=AHMAPCMA10&cat_year=&cat_region=&vo_body_type=W5&vo_engine_capacity=&vo_engine_type=6&vo_fuel_type=1&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=EN&min_sect=20201B11
view_veh_parts.php?lang_code=RU&catalog_code=AHMAPCMA10&maj_sect=MI&min_sect=4345011
*) 'UNIFIT'
view_veh_parts.php?lang_code=RU&catalog_code=AEURPSDA14&maj_sect=EN&min_sect=20213A11

[+] quantity = {A,C} - не целое
view_veh_parts.php?lang_code=RU&catalog_code=AUS5LWAA08&cat_year=&cat_region=AUS&vo_body_type=&vo_trim_level=B&vo_door___floor=&vo_suspension_type=A&vo_engine_capacity=7&vo_vehicle_type=3&veh_drive_type=&maj_sect=MI&min_sect=43430C11

[+] compatibility
*) build_date = cat_year
view_veh_parts.php?lang_code=RU&catalog_code=AUS170PA01&cat_year=2002&maj_sect=BO&min_sect=6064011&pnc=64100A
*) build_date - отсеить [09131] 091313B010
view_veh_parts.php?lang_code=RU&vin=KMHFU40D5XA000049&catalog_code=HMA390PA99&maj_sect=EN&min_sect=0909111
*) drive_type = ни R и ни L = 4,A - глюки
view_veh_parts.php?lang_code=RU&catalog_code=HAC3108500&cat_year=1987&cat_region=HAC&maj_sect=CH&min_sect=5052911&pnc=52910
*) drive_type
view_veh_parts.php?lang_code=RU&catalog_code=HEURPA612&cat_year=&cat_region=&vo_body_type=&vo_engine_capacity=&vo_engine_type=&vo_fuel_type=&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=BO&min_sect=05052B11
*) wether_type
view_veh_parts.php?lang_code=RU&catalog_code=HEURPA612&cat_year=&cat_region=&vo_body_type=&vo_engine_capacity=&vo_engine_type=&vo_fuel_type=&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=BO&min_sect=05052B11
*) ucc
view_veh_parts.php?lang_code=RU&catalog_code=HEURPA612&cat_year=&cat_region=&vo_body_type=&vo_engine_capacity=&vo_engine_type=&vo_fuel_type=&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=BO&min_sect=05052B11
*) options
view_veh_parts.php?lang_code=RU&catalog_code=HEURPA612&cat_year=&cat_region=&vo_body_type=&vo_engine_capacity=&vo_engine_type=&vo_fuel_type=&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=BO&min_sect=05052B11
view_veh_parts.php?lang_code=RU&catalog_code=AUS2209500&cat_year=&cat_region=AUS&vo_body_type=&vo_grade=&vo_engine_capacity=P&vo_fuel_type=&vo_transmission=&vo_special_car=A&veh_drive_type=&maj_sect=EN&min_sect=25251A11
view_veh_parts.php?lang_code=RU&vin=KMHJN81VP7U743519&catalog_code=KEURPJM04&maj_sect=BO&min_sect=6076011&pnc=76003
*) options_minus
view_veh_parts.php?lang_code=RU&catalog_code=AHMAPGFA10&cat_year=&cat_region=&vo_body_type=S4&vo_engine_capacity=&vo_engine_type=&vo_fuel_type=1&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=EN&min_sect=20203A11
view_veh_parts.php?lang_code=RU&vin=5NPEB4AC0BH022618&catalog_code=AHMAPGFA10&maj_sect=EN&min_sect=20203A21
*) options_minus - покажет результат если убрать вес совместмости
view_veh_parts.php?lang_code=RU&catalog_code=AHACPCMA11&cat_year=&cat_region=HAC&vo_body_type=W5&vo_engine_capacity=&vo_engine_type=6&vo_fuel_type=1&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=EL&min_sect=9191511
view_veh_parts.php?lang_code=RU&vin=5XYZG4AG0BG003368&catalog_code=AHACPCMA11&maj_sect=EL&min_sect=9191511
view_veh_parts.php?lang_code=RU&vin=5XYZG3AB0CG089717&catalog_code=AHACPCMA11&maj_sect=EL&min_sect=9191511

[+] part_weight
*) оставляет 760042E050, убирает 760042E020
view_veh_parts.php?lang_code=RU&vin=KMHJN81VP7U743519&catalog_code=KEURPJM04&maj_sect=BO&min_sect=6076011&pnc=76004
*) [91500] 915710W060
view_veh_parts.php?lang_code=RU&vin=5XYZG4AG0CG101107&catalog_code=AHACPCMA11&maj_sect=EL&min_sect=9191511
*) [91500] 915500W160
view_veh_parts.php?lang_code=RU&vin=5XYZG4AG0BG002916&catalog_code=AHACPCMA11&maj_sect=EL&min_sect=9191511

[+] Нестандартная запчасть - (синий цвет) = 1123GK, 26848 === Все что более одной записи в одном PNC
view_veh_parts.php?lang_code=RU&catalog_code=AUS2309200&cat_year=&cat_region=AUS&vo_body_type=C&vo_grade=&vo_engine_capacity=P&vo_fuel_type=&vo_transmission=&vo_special_car=A&veh_drive_type=&maj_sect=EN&min_sect=2828211

[+]
Номер детали [AL400 G1413], так и в каталоге с пробелом
view_veh_parts.php?lang_code=RU&catalog_code=AUS2209500&cat_year=&cat_region=AUS&vo_body_type=&vo_grade=&vo_engine_capacity=P&vo_fuel_type=&vo_transmission=&vo_special_car=A&veh_drive_type=&maj_sect=EL&min_sect=97976112&pnc=97713A


??? cats_pnc ??? ref <> pnc   Microcat использует при подборе запчастей и pnc и ref
view_veh_parts.php?lang_code=RU&catalog_code=AUS2309200&cat_year=&cat_region=AUS&vo_body_type=C&vo_grade=&vo_engine_capacity=P&vo_fuel_type=&vo_transmission=&vo_special_car=A&veh_drive_type=&maj_sect=EN&min_sect=2828211
view_veh_parts.php?lang_code=RU&catalog_code=HMI1A09900&cat_year=&cat_region=GEN&vo_body_type=&vo_grade=&vo_engine_capacity=&vo_fuel_type=D&vo_transmission=D&vo_special_car=I&veh_drive_type=&maj_sect=EL&min_sect=97971311

???
фотки на запчасти - аксесуары!
*) НЕСОВМЕСТИМО
view_veh_major.php?lang_code=RU&catalog_code=KHACPSD14&cat_year=2015&cat_region=HAC&vo_body_type=&vo_engine_capacity=&vo_engine_type=&vo_fuel_type=1&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=AC
*) [AC-098-EU] - минус одна запись??
view_veh_major.php?lang_code=RU&catalog_code=EUR0509900&cat_year=&cat_region=EUR&vo_body_type=A&vo_grade=&vo_engine_capacity=4&vo_fuel_type=&vo_transmission=&vo_special_car=E&veh_drive_type=&maj_sect=AC
+ *) несколько запчастей
view_veh_major.php?lang_code=RU&catalog_code=EUR0509900&cat_year=&cat_region=EUR&vo_body_type=A&vo_grade=&vo_engine_capacity=4&vo_fuel_type=&vo_transmission=&vo_special_car=E&veh_drive_type=&maj_sect=AC&min_sect=AC000399EU





???
cats_map.minor_sect_type = LOCAL
country_iso_codes
*) Если задано (cats_dat_parts.local или cats_dat_parts.type), cats_dat_parts.country_iso_codes задано всегда
*) cats_dat_parts.country_iso_codes может быть задано отдельно от cats_dat_parts.local
*) cats_dat_parts.country_iso_codes = +SE+NO+FI+DK+EE+LT+LV+BA+BG+HR+CZ+MK+RO+RS+SI+IC+ES+FR+AT+UA+HU+SK+PL+IT+MD+NL
view_veh_parts.php?lang_code=RU&catalog_code=AEURPSDA14&cat_year=&cat_region=EUR&vo_body_type=S4&vo_engine_capacity=N3&vo_engine_type=6&vo_fuel_type=1&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=EN&min_sect=20213A11

??? $compatibility.id = 5;Цвет
        
*/ 
?>

<table>
<tr valign='top'>
<td style='border-right:3px double blue; min-width:300px; max-width:350px'>
<?php
  // MINOR SECTION - List
  echo "<H3 class='group'>"."[$req_maj_sect] ".$cat_major_section['lex_desc']."</H3>";
  foreach($cat_map_minor_sections as $minor){
    $minor_url = "?".TMPL::get_request_url($_REQUEST, 'min_sect,pnc');
    echo $TMPL->minor_section_button(array(
        'minor_sect'=>$minor, 'minor_sect_url'=>$minor_url, 
        'is_show_logo'=>1, 'logo_width'=>'100px',
        'catalog_ucctype'=>$catalog_ucctype, 'catalog_ucc'=>$catalog_ucc, // показать ucc_value_info
        'cat_options_des'=>$cat_options_des, // показать option_value_info
      ));
    echo "<br/>";
  }
?>
</td>
<td style=''>
<?php
  // НАЗВАНИЕ ВЫБРАННОГО ПОДРАЗДЕЛА
  $minor_section_info = $TMPL->minor_section_info(array(
        'minor_sect'=>$cat_minor_section,
        'catalog_ucctype'=>$catalog_ucctype, 'catalog_ucc'=>$catalog_ucc, // показать ucc_value_info
        'cat_options_des'=>$cat_options_des, // показать option_value_info
      ));
  echo "<H3 class='group'>"."[".$minor_section_info['sector_format']."] - ".$minor_section_info['name']."</H3>";
  $br = ""; $hr="";
  if(!empty($minor_section_info['ucc'])){
    echo "<i>".$minor_section_info['ucc']."</i>"; // ucc
    $br = "<br/>";
    $hr = "<hr/>";
  }
  if(!empty($minor_section_info['option'])){
    echo $br."<i>".$minor_section_info['option']."</i>";// option
    $hr = "<hr/>";
  }
  echo $hr;
  
  // PNC + РИСУНОК
  $pnc_url = "?".TMPL::get_request_url($_REQUEST, 'maj_sect,min_sect,pnc'); //maj_sect,min_sect - может задаватся другой с рисунка
  echo "<table style='border-bottom:3px double blue;'>";
  echo "<tr valign='top'>";
  // СПИСОК PNC + сектора на рисунке 
  echo "<td>";
    echo TMPL::pnc_image_list(array('lang_code'=>$req_lang_code, 'cat_pnc'=>$cat_pnc, 'pnc_url'=>$pnc_url,
                                    'system_text'=>$_SYSTEM_TEXT, 'cat_image_minor_ref'=>$cat_image_minor_ref,
                                    'catalog_ucctype'=>$catalog_ucctype, 'catalog_ucc'=>$catalog_ucc, // показать ucc_value_info
                                    'cat_options_des'=>$cat_options_des, // показать option_value_info
                              ));
  echo "</td>";
  
  // РИСУНОК
  //$_IMG_DATA."Cats/".$minor_sect.".png";
  echo "<td>";
    $image_name = $cat_minor_section['minor_sect'];
    $image_path = $TMPL->get_cat_dat_image_path(array('cat_folder'=>$catalog['cat_folder'], 'image_name'=>$image_name));
    echo "<img src='$image_path' alt='' style='border:1px solid black;' usemap='#$image_name'/><br/>";
    // координаты
    echo TMPL::cat_dat_image_pnc_map(array(
        'system_text'=>$_SYSTEM_TEXT, // тексты интерфейса
        'dat_image'=>$cat_image_pnc_ref,  // список координат
        'image_name'=>$image_name,  //  имя рисунка для мапирования area
        'pnc_url'=>$pnc_url, // основа ссылки
        'cat_pnc'=>$cat_pnc, // список pnc для подписи элементов pnc
        'cat_image_minor_ref'=>$cat_image_minor_ref, // список sectors на рисунке
      ));
  echo "</td>";
  echo "</tr>";
  echo "</table>";
  
  // СПИСОК ЗАПЧАСТЕЙ
  //var_dump($part_compatibility);
  echo "<table class='parts_list'>";
  echo "<tr>";
  echo "<th>PNC</th>";
  echo "<th>".$_SYSTEM_TEXT['Part Number']['lex_lang_desc']."</th>";
  echo "<th>".$_SYSTEM_TEXT['Qty']['lex_lang_desc']."</th>";
  echo "<th>".$_SYSTEM_TEXT['Part Name']['lex_lang_desc']."</th>";
  echo "<th>".$_SYSTEM_TEXT['Model Description']['lex_lang_desc']."</th>";
  echo "<th>".$_SYSTEM_TEXT['Start Date']['lex_lang_desc']."</th>";
  echo "<th>".$_SYSTEM_TEXT['End Date']['lex_lang_desc']."</th>";
  echo "</tr>";
  
  foreach($cat_parts as $part){  
    echo "<tr>";
    echo "<td>";
      //pnc
      $pnc = $part['pnc'];
      // position
      if($part['ref'] <> $part['pnc'])
        $pnc .= "/".$part['ref']; 
      echo $pnc;
    echo "</td>";
    echo "<td>";
      //number
      echo $part['number'];
      //replaced
      if(!empty($part['replaced'])){
        echo "<br/><span class='part_replaced'>".$part['type'].": ".$part['replaced']."</span>";
      }
      //замены относительно региона
      if(!empty($bom_localpts[$part['number']])){
        $localpts = $bom_localpts[$part['number']];
        foreach($localpts as $locpt){
          echo "<br/><span class='part_replaced'>".$locpt['type'].": ".$locpt['part_number2']."</span>";          
        }
      }
    echo "</td>";
    echo "<td align='center'>".$part['quantity']."</td>";
    echo "<td>";
      //name
      echo TMPL::pnc_part_name(array(
                    'lang_code'=>$req_lang_code, // выбранный язык
                    'name_def_lex_desc'=>$part['name_def_lex_desc'], // английский
                    'name_loc_lex_desc'=>$part['name_loc_lex_desc'], // текст на выбранном языке
                    'desc_lex_desc'=>$part['desc_lex_desc'], // примичание, на любом языке какое будет
                    'name_br'=>"<br/>", // разделитель между текстами разных языков
                    ));
      
      // type 1 вариант.
      if(!empty($part['type'])){
        if(empty($part['replaced'])){  // показать тип, если он не был показа в замене номера
          echo "<br/>"."<span class='part_type'>"."[".$part['type']."]"."</span>";          
        }
        if($part['type'] == 'UNIFIT'){
          echo "<br/>"."<span class='part_type'>";
          echo $_SYSTEM_TEXT['PRODUCT LINE 2: Not to be used for warranty repair']['lex_lang_desc'];
          echo "</span>";
        }
      }
      // type 2 вариант. Показать type в описании, но нужно убрать вывод type в номере
      /*if(!empty($part['type'])){  // and ($part['type'] <> 'REMAN')
        echo "<br/>";
        echo "<span class='part_type'>";
        echo "[".$part['type']."]";
        if($part['type'] == 'UNIFIT'){
          echo " - ". $_SYSTEM_TEXT['PRODUCT LINE 2: Not to be used for warranty repair']['lex_lang_desc'];
        }
        echo "</span>";
      }*/
      
      // local
      if(!empty($part['local'])){  // показать local
        echo "<br/>"."<span class='part_type'>"."[".$part['local']."]"."</span>";          
      }
    echo "</td>";
    echo "<td>";
      // drive_type
      $br = "";
      if(isset($part['compatibility_unpack']['drive_type'])){
        $cmptbl_val = $part['compatibility_unpack']['drive_type'];
        if(isset($catalog_ucc['DT'][$cmptbl_val])){
          $ucc_typ_val = $catalog_ucc['DT'][$cmptbl_val];
          echo "<span class='ucc_type'>".$ucc_typ_val['lex_desc'].":</span> "."[$cmptbl_val]"." - ".$ucc_typ_val['ucc_lex_desc'];
          $br = "<br/>";
        }
      }
      // weather_type
      if(isset($part['compatibility_unpack']['weather_type'])){
        $cmptbl_val = $part['compatibility_unpack']['weather_type'];
        if(isset($catalog_ucc['WT'][$cmptbl_val])){
          $ucc_typ_val = $catalog_ucc['WT'][$cmptbl_val];
          echo $br."<span class='ucc_type'>".$ucc_typ_val['lex_desc'].":</span> "."[$cmptbl_val]"." - ".$ucc_typ_val['ucc_lex_desc'];
          $br = "<br/>";
        }
      }
      // ucc
      if(isset($part['compatibility_unpack']['ucc'])){
        // получить описания на опции ucc
        $ucc_values_info = TMPL::ucc_values_info(array(
                      'ucc'=>$part['compatibility_unpack']['ucc'], 
                      'catalog_ucctype'=>$catalog_ucctype, 'catalog_ucc'=>$catalog_ucc,
                )); 
        if(!empty($ucc_values_info)){
          foreach($ucc_values_info as $ucc_val_inf){
            if(!empty($ucc_val_inf['uccval_val'])){ //список настроен для показа всех возможных типов, не принимая во внимание что задано
              echo $br."<span class='ucc_type'>".$ucc_val_inf['ucctype_des'].":</span> "."[".$ucc_val_inf['uccval_val']."] ".$ucc_val_inf['uccval_des'];
              $br = "<br/>";
            }
          }
        }
      }
      // options [+]
      if(!empty($part['compatibility_unpack']['options'])){
        echo $br."<span class='options'>[+] ".$_SYSTEM_TEXT['Option Codes']['lex_lang_desc'].":</span>";
        foreach($part['compatibility_unpack']['options'] as $opt){
          echo "<br/>"."[$opt] ".$cat_options_des[$opt]['lex_desc'];
        }
        $br = "<br/>";
      }
      // options [-]
      if(!empty($part['compatibility_unpack']['options_minus'])){
        echo $br."<span class='options_minus'>[-] ".$_SYSTEM_TEXT['Option Codes']['lex_lang_desc'].":</span>";
        foreach($part['compatibility_unpack']['options_minus'] as $opt){
          echo "<br/>"."[$opt] ".$cat_options_des[$opt]['lex_desc'];
        }
        $br = "<br/>";
      }
      //var_dump($part['compatibility_unpack']);
      //echo $br.$br.$part['compatibility'];
    echo "</td>";
    echo "<td>".TMPL::get_date_dmy($part['production_from'], '.')."</td>";
    echo "<td>".TMPL::get_date_dmy($part['production_to'], '.')."</td>";
    echo "</tr>";
  }
  echo "</table>";
  //var_dump($cat_minor_section);
?>
</td>
<td style=''>
  <?php //var_dump($cat_parts); ?>
</td>
</tr>
</table>

<table>
<tr valign='top'>
<td>
  <?php //var_dump($catalog); ?>
  <?php //var_dump($vin_result); ?>
</td>
<td>
  <?php //var_dump($cat_sectors); ?>
</td>
</tr>
<tr valign='top'>
<td><?php //var_dump($catalog_ucc); ?></td>
<td><?php //var_dump($catalog_ucctype_key); ?><?php //var_dump($catalog_ucctype); ?></td>
</tr>
</table>

</body>
</html>