<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Major Section</title>
<link rel="stylesheet" type="text/css" href="style.css">
<style type="text/css">
</style>
</head>

<?php
/*---------------------------------------------
 * Основные разделы каталога автомобиля 
 *  18.04.2015
 *
 * Показать список Основных разделов - major_section, список Подразделов - minor_section
 --------------------------------------------*/

include_once("index_ini.php");	// иницируем sql-соеденение, создадим объекты $MC_API и $TMPL


// _REQUEST - ПАРАМЕТРЫ ВЫБОРА
$req_lang_code = (isset($_REQUEST["lang_code"]) ? $_REQUEST["lang_code"] : 'EN'); // по умолчанию - Английский
$req_vin = (isset($_REQUEST["vin"]) ? $_REQUEST["vin"] : '');
$req_catalog_code = (isset($_REQUEST["catalog_code"]) ? $_REQUEST["catalog_code"] : '');
$req_cat_year = (isset($_REQUEST["cat_year"]) ? $_REQUEST["cat_year"] : '');          // передать дальше на запчасти
$req_cat_region = (isset($_REQUEST["cat_region"]) ? $_REQUEST["cat_region"] : '');    // передать дальше на запчасти
$req_veh_drive_type = (isset($_REQUEST["veh_drive_type"]) ? $_REQUEST["veh_drive_type"] : '');
$req_veh_weather_type = (isset($_REQUEST["veh_weather_type"]) ? $_REQUEST["veh_weather_type"] : '');
$req_maj_sect = (isset($_REQUEST["maj_sect"]) ? $_REQUEST["maj_sect"] : '');
// избирательно отбираем нужные параметры между VIN <-> catalog
$vin_result = array();
$set_veh_ucc = "";
if(!empty($req_vin)){
  $vin_result = $MC_API->vin_result(array('vin'=>$req_vin, 'catalogue_code'=>$req_catalog_code));
  $vin_result = $vin_result[0];  // если попали на эту страницу то результ должен быть обязательным
  $set_veh_ucc = $vin_result['model']['ucc'];
} else {
  // опции комплектации авто будут использоватся параметрами url
  $catalog_ucctype_EN = $MC_API->cat_ucctype(array('lang_code'=>'EN', 'catalogue_code'=>$req_catalog_code));
  $catalog_ucctype_key = array_flip_2($catalog_ucctype_EN, 'ucc_type'); //проиндексируем
//$set_veh_ucc = TMPL::get_request_vehicle_options($_REQUEST);  // get_request_vehicle_options2 - надежнее
  $set_veh_ucc = TMPL::get_request_vehicle_options2(array('request'=>$_REQUEST, 'catalog_ucctype_key'=>$catalog_ucctype_key));
}

// ТЕКСТЫ ИНТЕРФЕЙСА
$_SYSTEM_TEXT = $MC_API->mc_lexicon_system_text(array('lang_code'=>$req_lang_code)); // тексты интерфейса
$mc_lexicon_lex_languages_list = $MC_API->mc_lexicon_lex_languages_list(array('lang_code'=>$req_lang_code)); // язык интерфейса и данных


// ДАННЫЕ
$catalog = $MC_API->catalog_cat_catalog(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$req_catalog_code));
$catalog = $catalog[0];
$catalog_ucctype = $MC_API->cat_ucctype(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$req_catalog_code));//виды характеристик авто
$catalog_ucc = $MC_API->cat_ucc(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$req_catalog_code)); //харакетристики авто
$cat_major_section = $MC_API->cat_map_major_section(array('lang_code'=>$req_lang_code, 'cat_folder'=>$catalog['cat_folder']));//Oсновной раздел

$cat_options_des = array();
$cat_minor_section = array();
$cat_sectors = array();
$cat_major_image_list = array();
$cat_major_images = array();
$cat_major_imgs_sector_checked = array();
if(!empty($req_maj_sect)){
  // minor section
  $cat_minor_section = $MC_API->cat_map_minor_section(array(
      'lang_code'=>$req_lang_code, 
      'cat_folder'=>$catalog['cat_folder'],
      'major_sect'=>$req_maj_sect,
      'ucc'=>$set_veh_ucc,  // применяемость ucc
      'vin'=>$vin_result,  // применяемость vin.options
    ));
    
  // оптимизируем выгрузку описаний option относимые к выбранному разделу, !!!оптиммизировали около 1 сек
  $options = array();
  foreach($cat_minor_section as $min_sec){
    if(!empty($min_sec['compatibility_unpack']['option'])){
      foreach($min_sec['compatibility_unpack']['option'] as $opt){
        $options[$opt] = $opt;
      }
    }
  }
  if(!empty($options)){ //!!!оптиммизировали около 1 сек
    $cat_options_des = $MC_API->cat_options(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$req_catalog_code, 'options'=>$options));
  }
  // major-minor sectors
  $cat_sectors = array_group($cat_minor_section, 'sector');
  
  // major images list
  $cat_major_image_list = $MC_API->cat_dat_major_distinct_images(array(
      'cat_folder'=>$catalog['cat_folder'],
      'major_sect'=>$req_maj_sect,
    ));
  
  //Основные секции: рисунки основного раздела с секторами
  if(!empty($cat_major_image_list)){
    // major images
    $cat_major_images = $MC_API->cat_dat_major_images(array(
        'cat_folder'=>$catalog['cat_folder'],
        'major_images'=>$cat_major_image_list,
      ));
    
    // !!! (не обязательно, по желанию), практически все сектора из cats_map присутвуют в cats_dat_ref.G[...], однако лучше бы проверить
    $cat_major_imgs_sector_checked = array(); // если Microcat не тупит, должна оставатся пустой!!!
    $cat_major_imgs_sector_checked = $MC_API->cat_dat_major_images_sectors_check($cat_major_images, $cat_sectors);
  }
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
      'cat_year'=>$req_cat_year,
      //'vehicle_type'=>$req_vehicle_type,
      'cat_region'=>$req_cat_region,
      'catalog_code'=>$req_catalog_code,
      'maj_sect'=>$req_maj_sect,
  ));

/*
[+] 'sector_part' => string '01/02'
~~~
view_veh_major.php?lang_code=RU&catalog_code=AEURPSDA14&cat_year=&cat_region=EUR&vo_body_type=S4&vo_engine_capacity=N3&vo_engine_type=6&vo_fuel_type=1&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=EN

[+] на рисунках основного раздела несколько одинаковых секторов => просто глюк :)
SELECT cat_folder, ref, img_name, COUNT(`id`) FROM cats_dat_ref WHERE ref_type = '3'
GROUP BY cat_folder, ref, img_name
HAVING COUNT(`id`) > 1;
GEX33091	41328A	GI6	2
GEX33091	41328B	GI6	2
~~~
view_veh_major.php?lang_code=RU&catalog_code=GEX3309100&cat_year=&cat_region=&vo_body_type=F&vo_grade=&vo_engine_capacity=&vo_fuel_type=A&vo_transmission=B&vo_special_car=&veh_drive_type=&maj_sect=EN

[+] note_lex_code
---
view_veh_major.php?lang_code=RU&vin=KMXMSE1PPWU047971&catalog_code=EURM209800&maj_sect=EN

[+] ucc - применяемость ;;||G||7|||||;
[+] ucc - комментарий в наименовании
---
'compatibility' => string ';;|B2||||||||;' (length=14)  [должна отссевать vo_engine_capacity=2B]
view_veh_major.php?lang_code=RU&catalog_code=KEURPJM04&cat_year=&cat_region=&vo_body_type=W5&vo_engine_capacity=2B&vo_engine_type=8&vo_fuel_type=1&vo_transaxle=8&veh_drive_type=L&veh_weather_type=2&maj_sect=EN 
'compatibility' => string ';;|N3||||||||;' (length=14)  [недолжна отссевать из-за совпадения vo_engine_capacity=N3]
view_veh_major.php?lang_code=RU&catalog_code=AEURPSDA14&cat_year=2015&cat_region=EUR&vo_body_type=S4&vo_engine_capacity=N3&vo_engine_type=6&vo_fuel_type=1&vo_transaxle=F&veh_drive_type=R&veh_weather_type=1&maj_sect=EN
view_veh_major.php?lang_code=RU&vin=KMHJN81VP7U743519&catalog_code=KEURPJM04&maj_sect=EN

[+] cats_map.compatibility 
;;|||||||||;0440|
view_veh_major.php?lang_code=RU&catalog_code=HMA4J0PA06&cat_year=2006&cat_region=&vo_body_type=A&vo_grade=&vo_engine_capacity=6&vo_fuel_type=6&vo_transmission=D&veh_drive_type=&maj_sect=EN
;;|||||||||;0440|   [должна фильтровать]
view_veh_major.php?lang_code=RU&vin=KNDMC233066000001&catalog_code=HMA4J0PA06&maj_sect=EN
;;|1F||||||||;0091B3|
view_veh_major.php?lang_code=RU&catalog_code=KEURPDF12&cat_year=&cat_region=EUR&vo_body_type=&vo_engine_capacity=&vo_engine_type=&vo_fuel_type=&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=MI
;;|1F||||||||;0091B3|
view_veh_major.php?lang_code=RU&vin=KMHLB41UACU000133&catalog_code=KEURPDF12&maj_sect=MI


???
cats_map.fl13 = +AU+CA
cats_map.fl12 = +US
*/  
?>

<table>
<tr valign='top'>
<td style='border-right:3px double blue;'>
<?php
  // MAJOR SECTION
  echo "<H3 class='group'>".$_SYSTEM_TEXT['Major Section']['lex_lang_desc']."</H3>";
  foreach($cat_major_section as $maj_code => $major_section){
    //$_IMG_DATA."Maj/".$maj_code.".png";
    $maj_image = $TMPL->get_cat_major_sect_image_path(array('major_sect_code'=>$maj_code));
    $maj_url = TMPL::get_request_url($_REQUEST, 'maj_sect');
    echo TMPL::major_section_button(array('major_section'=>$major_section, 'major_sect_image'=>$maj_image, 'major_sect_url'=>$maj_url));
  }
?>
</td>
<td style='border-right:1px dashed blue;'>
<?php
  // MINOR SECTION - List
  echo "<H3 class='group'>"."I. ".$_SYSTEM_TEXT['Minor Section']['lex_lang_desc']."</H3>";
  $min_url = "view_veh_parts.php?".TMPL::get_request_url($_REQUEST);
  foreach($cat_minor_section as $minor){
    echo $TMPL->minor_section_button(array(
        'minor_sect'=>$minor, 'minor_sect_url'=>$min_url, 
        'is_show_logo'=>1, 'logo_width'=>'100px',
        'catalog_ucctype'=>$catalog_ucctype, 'catalog_ucc'=>$catalog_ucc, // показать ucc_value_info
        'cat_options_des'=>$cat_options_des, // показать option_value_info
      ));
    echo "<br/>";
  }
?>
</td>

<?php
  // MINOR SECTION - Graphic Index
  // похлже что, в новых моделях уже нету
  if(!empty($cat_major_image_list)){
    echo "<td>";
    echo "<H3 class='group'>"."II. ".$_SYSTEM_TEXT['Graphic Index']['lex_lang_desc']."</H3>";
    
    // практически все сектора из cats_map присутвуют в cats_dat_ref.G[...], однако лучше бы проверить 
    if(!empty($cat_major_imgs_sector_checked))
      var_dump($cat_major_imgs_sector_checked);  // выводите уже самостоятельно :)
    
    // перебираем GI1..GIn, в порядке возрастания
    foreach($cat_major_image_list as $graphic_index){ //GI1..GIn
      $GI = $cat_major_images[$graphic_index];
      
      // рисунок GI
      $dat_image = $TMPL->get_cat_dat_image_path(array('cat_folder'=>$catalog['cat_folder'], 'image_name'=>$graphic_index));
      echo "<img src='$dat_image' alt='$graphic_index' usemap='#$graphic_index' />";

      // координаты
      echo TMPL::cat_dat_image_map(array(
          'dat_image'=>$GI,
          'image_name'=>$graphic_index,
          'sectors'=>$cat_sectors,
        ));
      echo "<br/>";
      
      // перебор всех записей GI, список позиций
      foreach($GI as $gi_row){
        $sector_code = $gi_row['ref'];
        
        if(isset($cat_sectors[$sector_code])){
          $gi_row_sector = $cat_sectors[$sector_code];
          
          foreach($gi_row_sector as $minor){
            echo $TMPL->minor_section_button(array('minor_sect'=>$minor, 'minor_sect_url'=>$min_url));
            echo "<br/>";        
          }
        } else {
          // покажем 'Not Applicable'
          echo "[".$sector_code."] - <span class='not_applicable'>".$_SYSTEM_TEXT['Not Applicable']['lex_lang_desc']."</span><br/>";
        }        
      }
      echo "<hr/>";
    }
    echo "</td>";
  }
?>
</tr>
</table>

<?php //var_dump($_REQUEST); ?>
<?php //var_dump($set_veh_ucc); ?>
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