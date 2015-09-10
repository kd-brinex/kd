<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Vehicle Identification</title>
<link rel="stylesheet" type="text/css" href="style.css">
<style type="text/css">
tr.line{
  border-top:1px solid black; 
  border-bottom:1px solid black;
}
td.line{
  border-right:1px solid black;
}
</style>
</head>

<?php
/*---------------------------------------------
 * Идентификация автомобиля по основным опциям комплектации
 *  31.03.2015
 
  Некоторые сайты (laximo.ru, hyundai.epcdata.ru, catalog.marand.msk.ru/hyundai/) - выводят модели авто с основными комплектацией из vin_model
  Например: SELECT * FROM vin_model WHERE catalogue_code = 'EUR2809100'
  Но Microcat так не делает!
  
  Оснавная задача, обеспечит формирование и выбор опций комплектации модели. Делаем по Microcat!
 --------------------------------------------*/

include_once("index_ini.php");	// иницируем sql-соеденение, создадим объект $control

// ПАРАМЕТРЫ ВЫБОРА
$req_lang_code = (isset($_REQUEST["lang_code"]) ? $_REQUEST["lang_code"] : 'EN'); // по умолчанию - Английский
$req_catalog_code = (isset($_REQUEST["catalog_code"]) ? $_REQUEST["catalog_code"] : '');
$req_cat_year = (isset($_REQUEST["cat_year"]) ? $_REQUEST["cat_year"] : '');
$req_cat_region = (isset($_REQUEST["cat_region"]) ? $_REQUEST["cat_region"] : ''); 

// ТЕКСТЫ ИНТЕРФЕЙСА
$_SYSTEM_TEXT = $MC_API->mc_lexicon_system_text(array('lang_code'=>$req_lang_code)); // тексты интерфейса
$mc_lexicon_lex_languages_list = $MC_API->mc_lexicon_lex_languages_list(array('lang_code'=>$req_lang_code)); // язык интерфейса и данных

// ДАННЫЕ
//$catalog = $MC_API->catalog(array('catalogue_code'=>$req_catalog_code));
$catalog = $MC_API->catalog_cat_catalog(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$req_catalog_code));
$catalog = $catalog[0];
$cat_production_years = $MC_API->cat_catalog_production_years_array($catalog['year_from'], $catalog['year_to']);  // года выпуска
$catalog_regions = $MC_API->catalog_regions_array(array(array('data_regions'=>$catalog['data_regions'])));  // регионы

//виды характеристик авто
$catalog_ucctype = $MC_API->cat_ucctype(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$req_catalog_code));
$catalog_ucctype = array_flip_2($catalog_ucctype, 'ucc_type'); //проиндексируем
//виды характеристик авто будут использоватся параметрами url
$catalog_ucctype_key = $MC_API->cat_ucctype(array('lang_code'=>'EN', 'catalogue_code'=>$req_catalog_code));
$catalog_ucctype_key = array_flip_2($catalog_ucctype_key, 'ucc_type'); //проиндексируем

$catalog_ucc = $MC_API->cat_ucc(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$req_catalog_code)); //харакетристики авто
?>

<body>
<?php 
  echo TMPL::select_lang(array('request'=>$_REQUEST,'system_text'=>$_SYSTEM_TEXT, 'list_lang'=>$mc_lexicon_lex_languages_list));
  echo TMPL::breadcrumbs(array(
      'system_text'=>$_SYSTEM_TEXT,
      'lang_code'=>$req_lang_code,
      //'cat_family'=>$req_cat_family,
      'cat_year'=>$req_cat_year,
      //'vehicle_type'=>$req_vehicle_type,
      'cat_region'=>$req_cat_region,
  ));
  
/*
[+] В имени харакетристики - '&' => Door & Floor
view_vehicle.php?lang_code=RU&catalog_code=MES5A0GA98
*/
?>

<form action='view_veh_major.php'>
<?php //глобальные параметры ?>
<input type='hidden' name='lang_code' value='<?php echo $req_lang_code;?>'>
<input type='hidden' name='catalog_code' value='<?php echo $req_catalog_code;?>'>

<table>
<tr>
<td width='275px'>
<?php    
  // CATALOG INFO
  //image - можем показать красивые
  $catalog_image = $TMPL->get_catalog_image_ttl_path(array('cat_folder'=>$catalog['cat_folder']));
  echo "<img src='$catalog_image' alt='".$catalog['lex_desc']."' border=1 width='99%' />"."<br/>";
  //info
  echo TMPL::catalog_info(array('mc_api'=>$MC_API, 'system_text'=>$_SYSTEM_TEXT, 'catalog'=>$catalog));
?>
</td>
<td>
<?php
  // УТОЧНЕНИЕ
  echo "<H3 class='group'>".$_SYSTEM_TEXT['Vehicle Details']['lex_lang_desc']."</H3>";
  echo "<table style='empty-cells:show; border-collapse:collapse' width='100%'>";

  //год
  echo "<tr class='line'>";
  echo "<td align='right' class='line'>";
    echo "<b>".$_SYSTEM_TEXT['Year']['lex_lang_desc'].": "."</b>";
  echo "</td>";
  echo "<td>";
    //var_dump($cat_production_years);
    
    // Unknown - если множественный выбор
    if(count($cat_production_years) != 1)
      echo "<div class='divlink'><input type='radio' name='cat_year' value='' ".($req_cat_year == '' ? "checked" : "").">".$_SYSTEM_TEXT['Unknown']['lex_lang_desc']."</div>";

    foreach($cat_production_years as $row){
      // если одна опция - сразу выбор
      $checked = "";
      if((count($cat_production_years) == 1) or ($req_cat_year == $row))
        $checked = "checked";
        
      echo "<div class='divlink'>";
      echo "<input type='radio' name='cat_year' value='$row' $checked>".$row;
      echo "</div>";
    }
    echo "<div style='clear:both;'></div>";
  echo "</td>";
  echo "</tr>";

  //регион
  echo "<tr class='line'>";
  echo "<td align='right' class='line'>";
    echo "<b>".$_SYSTEM_TEXT['Region']['lex_lang_desc'].": "."</b>";
  echo "</td>";
  echo "<td>";
    //var_dump($catalog_regions);
    
    // Unknown - если множественный выбор
    if(count($catalog_regions) != 1)
      echo "<div class='divlink'><input type='radio' name='cat_region' value='' ".($req_cat_region == '' ? "checked" : "").">".$_SYSTEM_TEXT['Unknown']['lex_lang_desc']."</div>";

    foreach($catalog_regions as $row){
      $region_desc = $MC_API->catalog_region_desc(array('system_text'=>$_SYSTEM_TEXT, 'region'=>$row));
      // если одна опция - сразу выбор
      $checked = "";
      if((count($catalog_regions) == 1) or ($req_cat_region == $row))
        $checked = "checked";
        
      echo "<div class='divlink'>";
      echo "<input type='radio' name='cat_region' value='$row' $checked>"."[".$row."] - ".$region_desc;
      echo "</div>";
    }
    echo "<div style='clear:both;'></div>";
  echo "</td>";
  echo "</tr>";

  echo "</table>";
?>

<?php
// ВЫБОР ОПЦИЙ
echo "<H3 class='group'>".$_SYSTEM_TEXT['Major Attributes']['lex_lang_desc']."</H3>";

echo "<table style='empty-cells:show; border-collapse:collapse'>";
foreach($catalog_ucc as $ucc_type => $ucc_list){
  echo "<tr class='line'>";
  echo "<td align='right' class='line'>";
  echo "<b>".$catalog_ucctype[$ucc_type]['lex_desc'].": "."</b>";
  echo "</td>";
  echo "<td>";
    //код опции возьмем с английского
    $ucctype_key = TMPL::get_catalog_ucctype_name($catalog_ucctype_key, $ucc_type);
    
    // если одна опция - сразу выбор
    $ucc_checked = "";
    if(count($ucc_list) == 1)
      $ucc_checked = "checked";   
    else
      echo "<div class='divlink'><input type='radio' name='$ucctype_key' value='' checked>".$_SYSTEM_TEXT['Unknown']['lex_lang_desc']."</div>";

    foreach($ucc_list as $ucc => $ucc_desc){
      echo "<div class='divlink'>";
      echo "<input type='radio' name='$ucctype_key' value='$ucc' $ucc_checked>"."[$ucc] - ".$ucc_desc['ucc_lex_desc'];
      echo "</div>";
    }
    echo "<div style='clear:both;'></div>";
  echo "</td>";
  echo "</tr>";
}

// сброс выбора опций
if(!empty($catalog_ucc)){
  echo "<tr>";
  echo "<td class='line'>";
    echo "&nbsp;";
  echo "</td>";
  echo "<td>";
    $url_prms = TMPL::get_request_url($_REQUEST);
    echo "<a href='?$url_prms' title=''>";
    echo $_SYSTEM_TEXT['Reset']['lex_lang_desc'];
    echo "</a>";
  echo "</td>";
  echo "</tr>";
}
echo "</table>";
?>

<br/>
<input type='submit' value='<?php echo $_SYSTEM_TEXT['Load']['lex_lang_desc']; ?>' style="height:50px; width:200px"/>

</td>
</tr>
</table>
</form>

<table>
<tr valign='top'>
<td><?php //var_dump($catalog); ?></td>
<td><?php //var_dump($cat_catalog); ?></td>
</tr>
<tr valign='top'>
<td><?php //var_dump($catalog_ucc); ?></td>
<td><?php //var_dump($catalog_ucctype_key); ?><?php //var_dump($catalog_ucctype); ?></td>
</tr>
</table>

</body>
</html>