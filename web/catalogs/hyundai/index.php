<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Start</title>
<link rel="stylesheet" type="text/css" href="style.css">
<style type="text/css">
</style>
</head>

<?php
/*---------------------------------------------
 * Тупо старт, первая страница
 *  01.03.2015
 --------------------------------------------*/

include_once("index_ini.php");	// иницируем sql-соеденение, создадим объект $control

// проверим выбор
$req_lang_code = (isset($_REQUEST["lang_code"]) ? $_REQUEST["lang_code"] : 'EN'); // по умолчанию - Английский
$req_vin = 'KMHJN81VP7U743519';
$req_cat_family = (isset($_REQUEST["cat_family"]) ? $_REQUEST["cat_family"] : ''); 
$req_cat_year = (isset($_REQUEST["cat_year"]) ? $_REQUEST["cat_year"] : ''); 
$req_vehicle_type = (isset($_REQUEST["vehicle_type"]) ? $_REQUEST["vehicle_type"] : ''); 
$req_cat_region = (isset($_REQUEST["cat_region"]) ? $_REQUEST["cat_region"] : ''); 
$set_filter = (!empty($req_cat_family) or !empty($req_cat_year) or !empty($req_vehicle_type) or !empty($req_cat_region)); // были ли заданый опции фильтрации

$_SYSTEM_TEXT = $MC_API->mc_lexicon_system_text(array('lang_code'=>$req_lang_code)); // тексты интерфейса
$mc_lexicon_lex_languages_list = $MC_API->mc_lexicon_lex_languages_list(array('lang_code'=>$req_lang_code)); // язык интерфейса и данных

$catalog_family = $MC_API->catalog_family();  // модельный ряд
$catalog_regions = $MC_API->catalog_regions();  // регионы
$cat_production_years = $MC_API->cat_catalog_production_years();  // года выпуска
$cat_catalog_vehicle_type = $MC_API->cat_catalog_vehicle_type();  // типы авто

// каталог получаем при заданых опциях фильтрации
$catalog = array();
if($set_filter)
  $catalog = $MC_API->catalog_cat_catalog(array('lang_code'=>$req_lang_code, 'family'=>$req_cat_family, 'cat_year'=>$req_cat_year, 'vehicle_type_code'=>$req_vehicle_type, 'cat_region'=>$req_cat_region));
?>

<body>
<?php 
  echo TMPL::select_lang(array('request'=>$_REQUEST,'system_text'=>$_SYSTEM_TEXT, 'list_lang'=>$mc_lexicon_lex_languages_list));
  echo TMPL::search_vin(array('system_text'=>$_SYSTEM_TEXT, 'set_lang_code'=>$req_lang_code, 'set_vin'=>$req_vin, ));
?>

<table border='1' bordercolor ='blue' frame='box' rules='all' style='border-color:blue; empty-cells:show; margin-top:15px;'>
<tr>

<!-- LEFT -->
<td valign='top' style='padding: 0 3px;'>
<?php
  //Car Line - будет иметь приоритет при фильтрации, т.е. должен сбивать дополнительные опции аля год выпуска и др.
  $url_prms = TMPL::get_request_url($_REQUEST, 'cat_family,cat_year,vehicle_type,cat_region');
  
  //сброс ВСЕХ опции фильтрации
/*  echo "<div style='margin-top:10px; text-align:center;'>";
  echo "<a href='?".$url_prms."'><button>"."<b>[".strtoupper($_SYSTEM_TEXT['Reset']['lex_lang_desc'])."]</b>"."</button></a>";
  echo "</div>"; */

  // список Car Line
  echo "<H4 class='group'>".$_SYSTEM_TEXT['Car Line']['lex_lang_desc']."</H4>";
  foreach($catalog_family as $row){
    echo "<a href='?".$url_prms."&cat_family=".$row['family']."' >";
    echo $row['family'];
    echo "</a><br/>";
  }
?>
</td>

<!-- CENTER -->
<td valign='top' style='padding: 0 3px;'>
<?php
  //Year
  $url_prms = TMPL::get_request_url($_REQUEST, 'cat_year');
  echo "<H4 class='group'>".$_SYSTEM_TEXT['Model year']['lex_lang_desc']."</H4>";
  echo TMPL::get_url_filter_all(array('system_text'=>$_SYSTEM_TEXT, 'url_prms'=>$url_prms)); //сброс опции фильтрации в группе
  foreach($cat_production_years as $row){
    echo "<div class='divlink_b' align='center'>";
    echo "<a href='?".$url_prms."&cat_year=".$row."' >";
    echo $row;
    echo "</a>";
    echo "</div>";
  }
  echo "<div style='clear:both;'></div>";

  //Vehicle Type
  $url_prms = TMPL::get_request_url($_REQUEST, 'vehicle_type');
  echo "<H4 class='group'>".$_SYSTEM_TEXT['Vehicle Type']['lex_lang_desc']."</H4>";
  echo TMPL::get_url_filter_all(array('system_text'=>$_SYSTEM_TEXT, 'url_prms'=>$url_prms));
  foreach($cat_catalog_vehicle_type as $row){
    $vehicle_type = $MC_API->mc_lexicon_system_text_desc(array('system_text'=>$_SYSTEM_TEXT, 'lex_sys'=>$row['vehicle_type'], 'prepare_1st'=>1));
    echo "<div class='divlink_b' align='center'>";
    echo "<a href='?".$url_prms."&vehicle_type=".$row['vehicle_type_code']."' >"; //ВНИМАНИЕ передаем код а не сам тип
    echo $vehicle_type;
    echo "</a>";
    echo "</div>";
  }
  echo "<div style='clear:both;'></div>";

  //Region
  $url_prms = TMPL::get_request_url($_REQUEST, 'cat_region');
  echo "<H4 class='group'>".$_SYSTEM_TEXT['Region']['lex_lang_desc']."</H4>";
  echo TMPL::get_url_filter_all(array('system_text'=>$_SYSTEM_TEXT, 'url_prms'=>$url_prms));
  foreach($catalog_regions as $row){
    $region_desc = $MC_API->catalog_region_desc(array('system_text'=>$_SYSTEM_TEXT, 'region'=>$row));
    echo "<div class='divlink_b' align='center'>";
    echo "<a href='?".$url_prms."&cat_region=".$row."' >"; //ВНИМАНИЕ передаем код а не сам регион
    echo $region_desc;
    echo "</a>";
    echo "</div>";
  }
  echo "<div style='clear:both;'></div>";
 
  //Info
  if(empty($catalog)){
    echo "<div style='border: 1px solid blue; margin:5px -3px 0 -3px;'></div>"; //3px возврат отступа <td style='padding: 0 3px;'>
    echo "<H2>";
    if($set_filter)
      echo $_SYSTEM_TEXT['No Results']['lex_lang_desc'];
    else
      echo $_SYSTEM_TEXT['Enter filter criteria']['lex_lang_desc'];
    echo "</H2>";
  }
  else {
    //Filters
    echo "<div style='background:#D6EBFF; margin: 2px -2px -3px -2px;'>";
    echo TMPL::breadcrumbs(array(
        'system_text'=>$_SYSTEM_TEXT,
        'lang_code'=>$req_lang_code,
        'cat_family'=>$req_cat_family,
        'cat_year'=>$req_cat_year,
        'vehicle_type'=>$req_vehicle_type,
        'cat_region'=>$req_cat_region,
    ));
    echo "</div>"; 

    //LIST OF CATALOG MODELS
    /*данный метод группирования и вывода списка моделей рассчитан на то, что метод catalog_cat_catalog делал ORDER BY family,... (по умолчанию) */
    $cat_family = '';
    $col_cnt = 4; $col_i = 0;
    if(count($catalog) < $col_cnt) $col_cnt = count($catalog);
    
    echo "<table border='1' frame='box' rules='all' style='empty-cells:show; margin-top:5px;'>";
    echo "<tr valign='top'>";
    foreach($catalog as $cat){
      // выводим группу
      if(strcasecmp($cat_family, $cat['family']) != 0){
        $cat_family = $cat['family'];
        
        //доводим предыдущую группу
        if($col_i != 0){
          while($col_i < $col_cnt){
            echo "<td>&nbsp;</td>";
            $col_i++;
          }
          echo "</tr><tr valign='top'>";
        }
        
        echo "<td colspan='$col_cnt' class='group'><h3 style='margin:0;'>$cat_family</h3></td></tr><tr valign='top'>";
        $col_i = 0;
      }
      
      // создаем новый рядок
      if($col_i >= $col_cnt){
        echo "</tr><tr valign='top'>";
        $col_i = 0;
      }
      
      echo "<td>";

      // рисунок
      $url_vehicle = "view_vehicle.php?lang_code=$req_lang_code";
      if(!empty($req_cat_year))
        $url_vehicle .= "&cat_year=$req_cat_year";
      if(!empty($req_cat_region))
        $url_vehicle .= "&cat_region=$req_cat_region";
      $url_vehicle .= "&catalog_code=".$cat['catalogue_code'];
      
      //кнопка-выбор каталога
      $catalog_image = $TMPL->get_catalog_image_path(array('cat_folder'=>$cat['cat_folder']));
      echo TMPL::catalog_button(array(
          'system_text'=>$_SYSTEM_TEXT,
          'catalog_image'=>$catalog_image, 
          'catalog_url'=>$url_vehicle,
          'catalog_title'=>$cat['lex_desc'],
        ));
      
      // инфо каталога
      echo TMPL::catalog_info(array('mc_api'=>$MC_API, 'system_text'=>$_SYSTEM_TEXT, 'catalog'=>$cat));
      
      echo "</td>";
      $col_i++;
    }
    
    while($col_i < $col_cnt){
      echo "<td>&nbsp;</td>";
      $col_i++;
    }
    
    echo "</tr>";
    echo "</table>";
  }
?>
</td>
</tr>
</table>

<?php
  //var_dump($catalog);
?>

</body>
</html>