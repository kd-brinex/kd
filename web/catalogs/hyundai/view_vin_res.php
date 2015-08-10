<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>VIN</title>
<link rel="stylesheet" type="text/css" href="style.css">
<style type="text/css">
</style>
</head>

<?php
/*---------------------------------------------
 * VIN - поиск авто по VIN
 *  15.11.2014
 --------------------------------------------*/

include_once("index_ini.php");	// иницируем sql-соеденение, создадим объект $control

// ПАРАМЕТРЫ ВЫБОРА
$req_lang_code = (isset($_REQUEST["lang_code"]) ? $_REQUEST["lang_code"] : 'EN'); // по умолчанию - Английский
$req_vin = (isset($_REQUEST["vin"]) ? $_REQUEST["vin"] : '');
$req_vin = strtoupper($req_vin);


// ТЕКСТЫ ИНТЕРФЕЙСА
$_SYSTEM_TEXT = $MC_API->mc_lexicon_system_text(array('lang_code'=>$req_lang_code)); // тексты интерфейса
$mc_lexicon_lex_languages_list = $MC_API->mc_lexicon_lex_languages_list(array('lang_code'=>$req_lang_code)); // язык интерфейса и данных

// ДАННЫЕ
$vin_result = $MC_API->vin_result(array('vin'=>$req_vin, 'is_check_ucc'=>1));  // результат может быть множественным!!! 5XYZH4AG8BG000618
    
$VIN_RES = array(); //хранить нужные данные для каждого результата
if(!empty($vin_result)){
  foreach($vin_result as $v_res){
    $vin_model = $v_res['model'];
    $vin_options = $v_res['options'];
    $v_res['vin_nation'] = $MC_API->cat_nation(array('nation_code'=>$vin_options['country'].$vin_options['region'])); //расшифровка территориальных опций
    
    // в cats0_catalog - больше на 1 модель - MAL020PA01, но винов для нее - нету
    // SELECT * FROM vin_model WHERE catalogue_code = 'MAL020PA01';
    // SELECT * FROM vin_vin WHERE vin_model_id IN (19968, 26799) LIMIT 10;
    $catalog = $MC_API->catalog(array('catalogue_code'=>$vin_model['catalogue_code']));
    //$catalog = $catalog[0];
    $v_res['catalog'] = $catalog;
    $v_res['catalog_image'] = $TMPL->get_catalog_image_path(array('cat_folder'=>$catalog['cat_folder']));
    $cat_catalog = $MC_API->cat_catalog(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$vin_model['catalogue_code']));
    $v_res['cat_catalog'] = $cat_catalog;//[0];
    
    $v_res['vin_model_year'] = MC_API::vin_model_year(array('vin'=>$req_vin, 'production_date'=>$vin_options['production_date']));
    
    //получить цвет
    $v_res['catalog_extcolor'] = array();
    if($vin_options['exterior_color'] != ''){
      $v_res['catalog_extcolor'] = $MC_API->cat_color_exterior(array('lang_code'=>$req_lang_code, 'color_main_code'=>$vin_options['exterior_color'], 'color_add_code'=>$vin_model['group_type']));
    }
    $v_res['catalog_intcolor'] = array();
    if($vin_options['interior_color'] != ''){
      $v_res['catalog_intcolor'] = $MC_API->cat_color_interior(array('lang_code'=>$req_lang_code, 'color_code'=>$vin_options['interior_color']));
    }
    
    $catalog_ucctype = $MC_API->cat_ucctype(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$vin_model['catalogue_code'])); //виды харакетристик авто
    $v_res['catalog_ucctype'] = $catalog_ucctype;
    $v_res['catalog_ucctype_key'] = array_flip_2($catalog_ucctype, 'ucc_type'); //проиндексируем
    $v_res['catalog_ucc'] = $MC_API->cat_ucc(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$vin_model['catalogue_code'])); //харакетристики авто
    
    // для оптимизации получим сразу все описания options
    $vin_options_option_code = array_merge($v_res['options_standart'], $v_res['options_optional'], $v_res['options_add']);
    $v_res['cat_options_des'] = $MC_API->cat_options(array('lang_code'=>$req_lang_code, 'catalogue_code'=>$vin_model['catalogue_code'], 'options'=>$vin_options_option_code));
    
    $VIN_RES[] = $v_res;
  }
}

/*
5XYZH4AG8BG000618 - 2 результата
KM8SB12B02U334978, KM8SB12B02U335256 - 2 записи в $vin_options - они едентичные
KMHFU40D5XA000049 => build_date <> production_date <> vin_model_year
95PZBN7HP8B001918 => build_date <> (production_date = 0) ~ Microcat - не находит!
XWEGH11HBE0000001 => (build_date == production_date) <> vin_model_year
KMHCF21F4RU000174 => нету ucc, но выводим еденичный вариант модели
KMXMSE1PPWU047971 => ucc - не подходят для модели
KMHEU41CR6A265174 KMHEN41BP4A000559 => vin_opt_add 
KMHFG4DG1CA001138 KMHFG4DG3CA001142 KMHFG4DG4CA001134 KMHFG4DG5CA001126 KMHFG4DG5CA001143 => vin_vin(-), vin_model(+), vin_options(+), а мы умеем :)
LBELMBJB09Y000007 LBELMBJB09Y000010 LBELMBJB0AY000058 LBELMBJB0AY000111 LBELMBJB0AY000125 => vin_vin(-), vin_model(-), vin_options(+)
KMHRB10APCU000122 => $vin_options['exterior_color'] <> Exterior Colour:Up <> Exterior Colour:Down
KM8SB12B02U334978 => $vin_options['exterior_color'] <> Exterior Colour:Up <> Exterior Colour:Down
KMJFD37BP1K481402 => $vin_options['exterior_color'] - единственная запись
KMXMSE1PPWU047971 => $vin_options['exterior_color'], $vin_model['group_type']==''
KMHRW20APCU014557 => $vin_options['interior_color'] == ''
*/
?>

<body>
<?php 
  echo TMPL::select_lang(array('request'=>$_REQUEST,'system_text'=>$_SYSTEM_TEXT, 'list_lang'=>$mc_lexicon_lex_languages_list));
  echo TMPL::search_vin(array('system_text'=>$_SYSTEM_TEXT, 'set_lang_code'=>$req_lang_code, 'set_vin'=>$req_vin, ));
  // Хлебные крошки
  echo TMPL::breadcrumbs(array(
      'system_text'=>$_SYSTEM_TEXT,
      'lang_code'=>$req_lang_code
    ));

  if(empty($VIN_RES)){
    echo "<br/>";
    echo $_SYSTEM_TEXT['VIN not found']['lex_lang_desc'].": ".$req_vin;
    exit();
  }  
  
  echo "<H3 style='margin:3px 0;'>";
  if(count($VIN_RES) == 1)
    echo $_SYSTEM_TEXT['Vehicle Search']['lex_lang_desc'].": ".$req_vin;
  else
    echo "<span style='color:red;'>".$_SYSTEM_TEXT['Multiple VIN Selection']['lex_lang_desc'].": ".$req_vin."</span>";
  echo "</H3>";
?>

<table border='1' frame='box' rules='all' style='empty-cells:show;'>
<tr>

<?php
$sep = "";
// из-за множественно результата 
foreach($VIN_RES as $v_res){ 
  $vin_model = $v_res['model'];
  $vin_options = $v_res['options'];
  $vin_nation = $v_res['vin_nation'];
  $vin_model_year = $v_res['vin_model_year'];
  $catalog_image = $v_res['catalog_image'];
  $catalog = $v_res['catalog'];
  $cat_catalog = $v_res['cat_catalog'];
  $catalog_ucc = $v_res['catalog_ucc'];
  $catalog_ucctype = $v_res['catalog_ucctype'];
  $catalog_ucctype_key = $v_res['catalog_ucctype_key'];
  $catalog_intcolor = $v_res['catalog_intcolor'];
  $catalog_extcolor = $v_res['catalog_extcolor'];
  $vin_options_standart = $v_res['options_standart'];
  $vin_options_optional = $v_res['options_optional'];
  $vin_options_add = $v_res['options_add'];
  $cat_options_des = $v_res['cat_options_des'];

  echo $sep; // вертикальный разделитель между множественными результатами, задается в конце
?>
<td valign='top'>
<?php
  echo "<H3 class='group'>".$_SYSTEM_TEXT['Vehicle Details']['lex_lang_desc']."</H3>";
  
  // кнопка-рисунок - выбор каталога
  $catalog_url = "view_veh_major.php?".TMPL::get_request_url($_REQUEST, '');
  $catalog_url .= "&catalog_code=".$vin_model['catalogue_code']; // catalog_code нужно уточнять для множественных результатов (5XYZH4AG8BG000618) 
  echo TMPL::catalog_button(array(
      'system_text'=>$_SYSTEM_TEXT,
      'catalog_image'=>$catalog_image, 
      'catalog_url'=>$catalog_url,
      'catalog_title'=>$cat_catalog['lex_desc'],
    ));
  
  echo TMPL::get_catalog_code($vin_model)."<br/>";
  echo $_SYSTEM_TEXT['Line']['lex_lang_desc'].": ".$catalog['family']."<br/>";
  //echo $_SYSTEM_TEXT['Region']['lex_lang_desc'].": "."<font color='red'>-----------</font>"."<br/>";
  echo $_SYSTEM_TEXT['Vehicle line']['lex_lang_desc'].": ".$catalog['cat_name']."<br/>";
  echo $_SYSTEM_TEXT['Build date']['lex_lang_desc'] . ": " . $vin_options['build_date']."<br/>";
  $vehicle_type = $MC_API->mc_lexicon_system_text_desc(array('system_text'=>$_SYSTEM_TEXT, 'lex_sys'=>$cat_catalog['vehicle_type'], 'prepare_1st'=>1));
  echo $_SYSTEM_TEXT['Vehicle Type']['lex_lang_desc'] . ": " . $vehicle_type;
  
  echo "<H3 class='group'>".$_SYSTEM_TEXT['Vehicle Information']['lex_lang_desc']."</H3>";
  
  //Major Attributes - МАТРИЦА
  // KMXMSE1PPWU047971 - не понятно что делать, кодов Характеристик для VIN - нету в списке возможных для модели
  echo "<H4 style='margin-bottom:5px;'>".$_SYSTEM_TEXT['Major Attributes']['lex_lang_desc'].":</H4>";
  $ucc_values_info = TMPL::ucc_values_info(array(
          'ucc'=>$vin_model['ucc'],
          'catalog_ucctype'=>$catalog_ucctype,
          'catalog_ucc'=>$catalog_ucc,
        ));
  foreach($ucc_values_info as $ucc_val_inf){
    echo $ucc_val_inf['ucctype_des'].": "."[".$ucc_val_inf['uccval_val']."] ".$ucc_val_inf['uccval_des']."<br/>";
  }
  //Drive Type: KMHCF21F4RU000174,KMXMSE1PPWU047971 - доказывают что drive_type - отдельное поле
  echo "<br/>";
  $vin_drive_type = $vin_options['drive_type'];
  echo $catalog_ucctype_key['DT']['lex_desc'].": [".$vin_drive_type."] ".$catalog_ucc['DT'][$vin_drive_type]['ucc_lex_desc']."<br/>";
  //Weather Type
  $vin_weather_type = $vin_options['weather_type'];
  if(isset($catalog_ucctype_key['WT'])){
    echo $catalog_ucctype_key['WT']['lex_desc'].": ";
      if($vin_weather_type <> '')
      echo "[".$vin_weather_type."] ".$catalog_ucc['WT'][$vin_weather_type]['ucc_lex_desc'];
    echo"<br/>";
  }

  //Colour Details
  echo "<H4 style='margin-bottom:5px;'>".$_SYSTEM_TEXT['Colour Details']['lex_lang_desc'].":</H4>";
  echo $_SYSTEM_TEXT['Exterior Colour']['lex_lang_desc'].": ".$vin_options['exterior_color'];
  if(!empty($catalog_extcolor)){
    echo " <br>&emsp; ".$_SYSTEM_TEXT['Up']['lex_lang_desc'].": [".$catalog_extcolor['up_color_code']."] ".$catalog_extcolor['up_lex_desc'];
    echo " <br>&emsp; ".$_SYSTEM_TEXT['Down']['lex_lang_desc'].": [".$catalog_extcolor['down_color_code']."] ".$catalog_extcolor['down_lex_desc'];
  }
  echo "<br/>";
  echo $_SYSTEM_TEXT['Interior Colour']['lex_lang_desc'].": ";
  if(!empty($catalog_intcolor)){
    echo "[".$vin_options['interior_color']."] ".$catalog_intcolor['lex_desc'];
  }
  echo "<br/>";

  //General
  echo "<H4 style='margin-bottom:5px;'>".$_SYSTEM_TEXT['General']['lex_lang_desc'].":</H4>";
  echo $_SYSTEM_TEXT['Production Date']['lex_lang_desc'].": ".$vin_options['production_date']."<br/>";
  echo $_SYSTEM_TEXT['Model year']['lex_lang_desc'].": ".$vin_model_year."<br/>";
  echo $_SYSTEM_TEXT['Model']['lex_lang_desc'].": ".$vin_model['model']."<br/>";
  if(!empty($vin_nation)){
    echo $_SYSTEM_TEXT['Country']['lex_lang_desc'].": [".$vin_options['country']."] ".$vin_nation['nation']."<br/>";
    echo $_SYSTEM_TEXT['Region']['lex_lang_desc'].": [".$vin_options['region']."] ".$vin_nation['region']."<br/>";
  } else {
    echo $_SYSTEM_TEXT['Country']['lex_lang_desc'].": <br/>";
    echo $_SYSTEM_TEXT['Region']['lex_lang_desc'].": <br/>";
  }
  echo $_SYSTEM_TEXT['Plant']['lex_lang_desc'].": ".$vin_options['plant']."<br/>";
  echo "Vehicle Factory: ".$vin_options['vahicle_factory']."<br/>"; //KMHFG4DG5CA001126
  echo "Engine Factory:".(($vin_options['engine_factory_code'] <> '') ? ("[".$vin_options['engine_factory_code']."] ") : "").$vin_options['engine_factory_desc']."<br/>"; //XWEGH11HBD0000001
  echo $_SYSTEM_TEXT['Engine Number']['lex_lang_desc'].": ".$vin_options['engine_number']."<br/>"; //XWEGH11HBD0000001
  echo $_SYSTEM_TEXT['Engine Code']['lex_lang_desc'].": ".$vin_options['engine_code']."<br/>"; //XWEGH11HBD0000001
  echo "Transmisson Factory: ".$vin_options['transmisson_factory']."<br/>"; //XWELB41AAE0000001
  echo "Transmission Number: ".$vin_options['transmission_number']."<br/>"; //XWELB41AAE0000001
  echo $_SYSTEM_TEXT['Transmission Code']['lex_lang_desc'].": ".$vin_options['transmission_code']."<br/>"; //XWELB41AAE0000001
?>
</td>
<td valign='top'>
<?php
  echo "<H3 class='group'>".$_SYSTEM_TEXT['Option Codes']['lex_lang_desc']."</H3>";
  echo TMPL::vin_option_codes(array('vin_options'=>$vin_options_standart, 'options_des'=>$cat_options_des));
  echo TMPL::vin_option_codes(array('vin_options'=>$vin_options_optional, 'options_des'=>$cat_options_des));
  
  //KMHEU41CR6A265174 KMHEN41BP4A000559
  echo "<H3  class='group'>Added options</H3>";   //style='border-bottom: 1px dashed black;'
  echo TMPL::vin_option_codes(array('vin_options'=>$vin_options_add, 'options_des'=>$cat_options_des));
?>
</td>
<?php
  $sep = "<td bgcolor='D6EBFF'>&nbsp;</td>"; // вертикальный разделитель между множественными результатами
} //foreach($VIN_RES as $v_res)
?>
</tr>
</table> 

<?php
/*
<table>
<tr>
<td><?php var_dump($catalog); ?></td>
<td><?php var_dump($cat_catalog); ?></td>
</tr>
</table>
*/
//  var_dump($vin_result); 
//  var_dump($VIN_RES); 
//  var_dump($catalog_ucctype); 
//  var_dump($catalog_ucc); 
//  var_dump($vin_options); 
?>
</body>
</html>