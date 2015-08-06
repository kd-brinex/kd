<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Старт</title>
<link rel="stylesheet" type="text/css" href="style.css">
<style type="text/css">
</style>
</head>

<?php
/*---------------------------------------------
 * Тупо старт, первая страница
 *  25.10.2014
 --------------------------------------------*/

include_once("index_ini.php");	// иницируем sql-соеденение, создадим объект $control

// проверим выбор
$set_lang_code = (isset($_REQUEST["lang_code"]) ? $_REQUEST["lang_code"] : 'EN'); // по умолчанию - Английский
$set_vin = 'KMHJN81VP7U743519';

$_SYSTEM_TEXT = $MC_API->mc_lexicon_system_text(array('lang_code'=>$set_lang_code)); // тексты интерфейса
$mc_lexicon_lex_languages_list = $MC_API->mc_lexicon_lex_languages_list(array('lang_code'=>$set_lang_code)); // язык интерфейса и данных

$catalog = $MC_API->catalog_cat_catalog(array('lang_code'=>$set_lang_code));
$cat_production_years = $MC_API->cat_catalog_production_years();
?>

<body>
<?php 
  echo TMPL::select_lang(array('request'=>$_REQUEST,'system_text'=>$_SYSTEM_TEXT, 'list_lang'=>$mc_lexicon_lex_languages_list));
  echo TMPL::search_vin(array('system_text'=>$_SYSTEM_TEXT, 'set_lang_code'=>$set_lang_code, 'set_vin'=>$set_vin, ));
  
  echo $_SYSTEM_TEXT['Year']['lex_lang_desc'].": <br/>";
  
  $cat_family = '';
  foreach($catalog as $cat){
    /*данный метод группирования и вывода дсписка моделей рассчитан на то что метод catalog_cat_catalog делал ORDER BY family,... (по умолчанию) */
    if(strtoupper($cat_family) <> strtoupper($cat['family'])){
      $cat_family = $cat['family'];
      
      echo "<div style='clear:both;'></div>";
      echo "<H4>$cat_family</H4>";
    }
    echo "<div class='divlink_b' align='center'>";

    // рисунок
    $catalog_image = $TMPL->get_catalog_image_path(array('cat_folder'=>$cat['cat_folder']));
    echo "<img src='$catalog_image' alt='".$cat['lex_desc']."' title='".$cat['lex_desc']."' /><br/>";
    
    echo TMPL::get_catalog_code($cat)."<br/>";
    //echo $cat['catalogue_code']." (".$cat['group_type'].")"."<br/>";
    echo $cat['family']."<br/>";
    echo $cat['cat_name']."<br/>";
    echo $cat['lex_desc']."<br/>";
    echo $cat['data_regions']."<br/>";
    echo $cat['vehicle_type']."<br/>";
    echo $cat['year_from']." - ".$cat['year_to'];
    echo "</div>";
  }
  echo "<div style='clear:both;'></div>";

  var_dump($catalog);
  //var_dump($MC_API); 
?>
</body>
</html>