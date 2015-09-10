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
 * ДЛЯ minor_section.minor_sect_type == 'LOCAL' (как праивло акссесуары) - показать картинку и описание акссесуара (так удобнее, в сравнении с выводом на отдельную страницу)
 --------------------------------------------*/

include_once("index_ini.php");	// иницируем sql-соеденение, создадим объекты $MC_API и $TMPL

// ПАРАМЕТРЫ ВЫБОРА
$set_lang_code = (isset($_REQUEST["lang_code"]) ? $_REQUEST["lang_code"] : 'EN'); // по умолчанию - Английский
$set_catalog_code = (isset($_REQUEST["catalog_code"]) ? $_REQUEST["catalog_code"] : '');
$set_cat_year = (isset($_REQUEST["cat_year"]) ? $_REQUEST["cat_year"] : '');
$set_cat_region = (isset($_REQUEST["cat_region"]) ? $_REQUEST["cat_region"] : ''); 
//$set_veh_options = TMPL::get_request_vehicle_options($_REQUEST);  // надежнее get_request_vehicle_options2
$set_veh_drive_type = (isset($_REQUEST["veh_drive_type"]) ? $_REQUEST["veh_drive_type"] : '');
$set_veh_weather_type = (isset($_REQUEST["veh_weather_type"]) ? $_REQUEST["veh_weather_type"] : '');
$set_maj_sect = (isset($_REQUEST["maj_sect"]) ? $_REQUEST["maj_sect"] : '');
$set_min_sect = (isset($_REQUEST["min_sect"]) ? $_REQUEST["min_sect"] : '');

// ТЕКСТЫ ИНТЕРФЕЙСА
$_SYSTEM_TEXT = $MC_API->mc_lexicon_system_text(array('lang_code'=>$set_lang_code)); // тексты интерфейса
$mc_lexicon_lex_languages_list = $MC_API->mc_lexicon_lex_languages_list(array('lang_code'=>$set_lang_code)); // язык интерфейса и данных

// ДАННЫЕ
// опции комплектации авто будут использоватся параметрами url
$catalog_ucctype_key = $MC_API->cat_ucctype(array('lang_code'=>'EN', 'catalogue_code'=>$set_catalog_code));
$catalog_ucctype_key = array_flip_2($catalog_ucctype_key, 'ucc_type'); //проиндексируем
$set_veh_ucc = TMPL::get_request_vehicle_options2(array('request'=>$_REQUEST, 'catalog_ucctype_key'=>$catalog_ucctype_key));

$catalog = $MC_API->catalog_cat_catalog(array('lang_code'=>$set_lang_code, 'catalogue_code'=>$set_catalog_code));
$catalog = $catalog[0];
$cat_major_section = $MC_API->cat_map_major_section(array('lang_code'=>$set_lang_code, 'cat_folder'=>$catalog['cat_folder']));

$cat_minor_section = array();
$cat_sectors = array();
$cat_major_image_list = array();
$cat_major_images = array();
$cat_major_imgs_sector_checked = array();
if(!empty($set_maj_sect)){
  // minor section
  $cat_minor_section = $MC_API->cat_map_minor_section(array(
      'lang_code'=>$set_lang_code, 
      'cat_folder'=>$catalog['cat_folder'],
      'major_sect'=>$set_maj_sect,
      'ucc'=>$set_veh_ucc,  // применяемость ucc
    ));
  
  // major-minor sectors
  $cat_sectors = array_group($cat_minor_section, 'sector');
  
  // major images list
  $cat_major_image_list = $MC_API->cat_dat_major_distinct_images(array(
      'cat_folder'=>$catalog['cat_folder'],
      'major_sect'=>$set_maj_sect,
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

[+] ucc - применяемость ;;||G||7|||||;
---
'compatibility' => string ';;|B2||||||||;' (length=14)  [должна отссевать]
view_veh_major.php?lang_code=RU&catalog_code=KEURPJM04&cat_year=&cat_region=&vo_body_type=W5&vo_engine_capacity=2B&vo_engine_type=8&vo_fuel_type=1&vo_transaxle=8&veh_drive_type=L&veh_weather_type=2&maj_sect=EN 
'compatibility' => string ';;|N3||||||||;' (length=14)  [недолжна отссевать из-за совпадения vo_engine_capacity=N3]
view_veh_major.php?lang_code=RU&catalog_code=AEURPSDA14&cat_year=2015&cat_region=EUR&vo_body_type=S4&vo_engine_capacity=N3&vo_engine_type=6&vo_fuel_type=1&vo_transaxle=F&veh_drive_type=R&veh_weather_type=1&maj_sect=EN  

???
фотки на запчасти - аксесуары!
НЕСОВМЕСТИМО
view_veh_major.php?lang_code=RU&catalog_code=KHACPSD14&cat_year=2015&cat_region=HAC&vo_body_type=&vo_engine_capacity=&vo_engine_type=&vo_fuel_type=1&vo_transaxle=&veh_drive_type=&veh_weather_type=&maj_sect=AC
[AC-098-EU] - минус одна запись??
view_veh_major.php?lang_code=RU&catalog_code=EUR0509900&cat_year=&cat_region=EUR&vo_body_type=A&vo_grade=&vo_engine_capacity=4&vo_fuel_type=&vo_transmission=&vo_special_car=E&veh_drive_type=&maj_sect=AC


??? cats_map.compatibility
;;|1F||||||||;0091B3|
SELECT DISTINCT compatibility FROM cats_map; #distinct compatibility
SELECT * FROM cats_map WHERE LENGTH(compatibility) > 16; #vin_options
SELECT * FROM catalog WHERE cat_folder IN('HMA4J0PA', 'KEURPDF1', 'HAC4J0PA');
SELECT * FROM vin_model WHERE catalogue_code = 'HMA4J0PA06'
 UNION 
SELECT * FROM vin_model WHERE catalogue_code = 'KEURPDF12' AND (ucc LIKE '%|1F|%' OR ucc LIKE '%|2G|%');
SELECT * FROM vin_vin WHERE vin_vin.vin_model_id IN (18854,19905);
SELECT * FROM vin_options WHERE vin = 'KNDMC233066000001' AND option_standart LIKE '%0440%';

??? 
ref_type = 5
d:\Project-Result\Hyundai_MicroCat\Imgs\Cats\AEURPSDA\20216A11.png - 

???
note_lex_code

???
cats_map.fl13 = +AU+CA
cats_map.fl12 = +US
cats_map.fl14 = LOCAL
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
    $maj_url = TMPL::get_request_url($_REQUEST, 'maj_sect,min_sect');
    echo TMPL::major_section_button(array('major_section'=>$major_section, 'major_sect_image'=>$maj_image, 'major_sect_url'=>$maj_url));
  }
?>
</td>
<td style='border-right:1px dashed blue;'>
<?php
  // MINOR SECTION
  echo "<H3 class='group'>"."I. ".$_SYSTEM_TEXT['Minor Section']['lex_lang_desc']."</H3>";
  foreach($cat_minor_section as $minor){
    if($minor['minor_sect_type'] == 'LOCAL'){
      $min_url = TMPL::get_request_url($_REQUEST, 'min_sect');;
    } else {
      $min_url = "";
    }
    echo $TMPL->minor_section_button(array('minor_sect'=>$minor, 'minor_sect_url'=>$min_url, 'is_show_local_logo'=>1, 'local_logo_width'=>'50px'));
    echo "<br/>";
  }
?>
</td>

<?php
  // исключительная ситуация, запчасть показывается прямо во время выбора РАЗДЕЛ-ПОДРАЗДЕЛ
  // ДЛЯ MINOR_SECTION.MINOR_SECT_TYPE == 'LOCAL'
  // (как праивло акссесуары) - показать картинку и описание акссесуара
  if(!empty($set_min_sect)){
    $minor_sect = $cat_minor_section[$set_min_sect]; // выбранный minor_section
    echo "<td>";
    echo "<H3 class='group'>"."II. ".$_SYSTEM_TEXT['Accessories']['lex_lang_desc']."</H3>";
    echo $TMPL->minor_section_local_info(array('minor_sect'=>$minor_sect));
    var_dump($minor_sect);
    echo "</td>";
  }
?>  

<?php
  // MINOR SECTION - Graphic Index
  // как я понял в новых моделях уже нету
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
            echo $TMPL->minor_section_button(array('minor_sect'=>$minor, 'minor_sect_url'=>''));
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
<?php var_dump($set_veh_ucc); ?>
<table>
<tr valign='top'>
<td>
  <?php var_dump($catalog); ?>
  <?php var_dump($cat_major_image_list); ?>
  <?php var_dump($cat_major_imgs_sector_checked); ?>
  <?php var_dump($cat_major_images); ?>
</td>
<td>
  <?php var_dump($cat_sectors); ?>
</td>
</tr>
<tr valign='top'>
<td><?php //var_dump($catalog_ucc); ?></td>
<td><?php //var_dump($catalog_ucctype_key); ?><?php //var_dump($catalog_ucctype); ?></td>
</tr>
</table>

</body>
</html>