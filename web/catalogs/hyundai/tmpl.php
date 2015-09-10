<?php
/************************************************************
 * template module
 * bunak - tecdoc@ukr.net
 * 25.10.2014
 ************************************************************/

class TMPL {
  private $img_data = ""; // путь к рисункам
  private $system_text = array();  // тексты системного интерфейса

  public function __construct(array $prms) {
    $this->img_data = $prms['img_data'];	// путь к рисункам
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// попробуем упростить
  //  21.06.2015
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function set_system_text(array $system_text) {
    $this->system_text = $system_text;	// путь к рисункам
  }

  public function get_system_text($lex_sys_desc) {
    $lex_lang_desc = ((!empty($this->system_text[$lex_sys_desc])) ? $this->system_text[$lex_sys_desc]['lex_lang_desc'] : "");
    return $lex_lang_desc;
  }

  

/*********************************************************************/
//  ОСНОВНАЯ НАВИГАЦИЯ

	//~~~~~~~~~~~~~~~~~~~~~~~~
	// список языков для выбора
	// 13.11.2014
	//~~~~~~~~~~~~~~~~~~~~~~~~	
	static function select_lang(array $prms){
    $_SYSTEM_TEXT = $prms['system_text'];
    $REQUEST = $prms['request'];
    $list_lang = $prms['list_lang'];
    
    $url_prms = self::get_request_url($REQUEST, 'lang_code');
    
		$html = "";
    $html .= "<div class='divlink'><b>".$_SYSTEM_TEXT['Language']['lex_lang_desc'].":</b></div>";
    foreach($list_lang as $row) {
      $html .= "<div class='divlink_b' align='center'>";
      $html .= "<a href='?lang_code=".$row['lang_code']."&".$url_prms."' title='".$row['lang_desc']."' >";
      $html .= $row['lex_desc'];
      $html .= "</a>";
      $html .= "</div>";
    }
    $html .= "<div style='clear:both;'></div>";
    
		return $html;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// окошко поиска по вину
	// 25.10.2014
	//~~~~~~~~~~~~~~~~~~~~~~~~	
	static function search_vin(array $prms){
    $_SYSTEM_TEXT = $prms['system_text'];
    $set_vin = $prms['set_vin'];
    $set_lang_code = $prms['set_lang_code'];
    
		$html = "";   
    $html .= "<div style='background-color:#B8B8B8; margin: -17px 0px; padding:0 10px;'>";
    $html .= "<form method='GET' action='view_vin_res.php'>";
    $html .= "<H3 style=''>";
    $html .= "VIN: ";
    $html .= "<input type='hidden' name='lang_code' value='$set_lang_code' />";
    $html .= "<input type='text' name='vin' value='$set_vin' size='30' />";
    $html .= "<input type='submit' value='".$_SYSTEM_TEXT['Search']['lex_lang_desc']." ' />";
    $html .= "</H3>";
    $html .= "</form>";
    $html .= "</div>";
    
		return $html;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// сгенерировать кнопку выбора каталога
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function breadcrumbs(array $prms){
    $_SYSTEM_TEXT = $prms['system_text'];
    $sep = isset($prms['sep']) ? $prms['sep'] : " &raquo; ";  // разделитель
    $lang_code = $prms['lang_code'];  
    $vin = isset($prms['vin']) ? $prms['vin'] : "";           // VIN 
    $cat_family = isset($prms['cat_family']) ? $prms['cat_family'] : "";  // Модельный ряд 
    $cat_year = isset($prms['cat_year']) ? $prms['cat_year'] : "";        // Год
    $vehicle_type = isset($prms['vehicle_type']) ? $prms['vehicle_type'] : ""; // Тип автомобиля
    $cat_region = isset($prms['cat_region']) ? $prms['cat_region'] : "";
    $catalog_code = isset($prms['catalog_code']) ? $prms['catalog_code'] : "";
    $maj_sect = isset($prms['maj_sect']) ? $prms['maj_sect'] : ""; // Oсновной раздел
    
    $root_req = "lang_code=$lang_code"; //по глубине будем формировать обязательные ключевые поля
  
    $html = "";
    $html .= "<div style='background:#D6EBFF; margin-top:1px;'>";    
    $html .= "<div style='border: 1px solid cornflowerblue;'></div>";
    $html .= "<a href='index.php?$root_req'><button style='margin-left:5px;'>"."<b>[".strtoupper($_SYSTEM_TEXT['Reset']['lex_lang_desc'])."]</b>"."</button></a>";
    if(!empty($vin)){         // VIN 
      $root_req .= "&vin=$vin";
      $html .= $sep."<b>VIN</b>: ";
      $html .= "<a href='view_vin_res.php?$root_req'>$vin</a>";    
    }
    if(!empty($cat_family)){
      $html .= $sep."<b>".$_SYSTEM_TEXT['Car Line']['lex_lang_desc']."</b>: ";
      $html .= "<a href='index.php?$root_req&cat_family=$cat_family'>$cat_family</a>";    
    }
    if(!empty($cat_year)){
      $html .= $sep."<b>".$_SYSTEM_TEXT['Year']['lex_lang_desc']."</b>: ";
      $html .= "<a href='index.php?$root_req&cat_year=$cat_year'>$cat_year</a>";    
    }
    if(!empty($vehicle_type)){
      $html .= $sep."<b>".$_SYSTEM_TEXT['Vehicle Type']['lex_lang_desc']."</b>: ";
      $html .= "<a href='index.php?$root_req&vehicle_type=$vehicle_type'>$vehicle_type</a>";    
    }
    if(!empty($cat_region)){
      $html .= $sep."<b>".$_SYSTEM_TEXT['Region']['lex_lang_desc']."</b>: ";
      $html .= "<a href='index.php?$root_req&cat_region=$cat_region'>$cat_region</a>";    
    }
    if(!empty($catalog_code)){
      $root_req .= "&catalog_code=$catalog_code";
      $html .= $sep."<b>".$_SYSTEM_TEXT['Catalogue']['lex_lang_desc']."</b>: ";
      $html .= "<a href='view_vehicle.php?$root_req'>$catalog_code</a>";    
    }
    if(!empty($maj_sect)){
      $html .= $sep."<b>".$_SYSTEM_TEXT['Major Section']['lex_lang_desc']."</b>: ";
      $html .= "<a href='view_veh_major.php?$root_req&maj_sect=$maj_sect'>$maj_sect</a>";    
    }
    $html .= "<div style='border: 1px solid cornflowerblue;'></div>";
    $html .= "</div>";
    return $html;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// сгенерировать кнопку выбора каталога
  //        ? может вместо _button лучше _cell
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function catalog_button(array $prms){
    $_SYSTEM_TEXT=$prms['system_text']; 
    $cat_image=$prms['catalog_image'];
    $cat_title=$prms['catalog_title']; 
    $cat_url=$prms['catalog_url']; 
    
    $html = "";
    $html .= "<div align='center'>";
    $html .= "<a href='$cat_url' title='$cat_title'>";
    $html .= "<button>";
    $html .= "<img src='$cat_image' alt='$cat_title' />";
    $html .= "<hr/>";
    $html .= "<b>[".$_SYSTEM_TEXT['Select']['lex_lang_desc']."]</b>";    
    $html .= "</button>";
    $html .= "</a>";
    $html .= "</div>";
    
    return $html;
  }
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// инфо каталога
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function catalog_info(array $prms){
    $MC_API = $prms['mc_api'];
    $_SYSTEM_TEXT=$prms['system_text']; 
    $catalog=$prms['catalog'];
    
    $html = "";
    $html .= self::get_catalog_code($catalog)."<br/>";
    $html .= $catalog['cat_name']."<br/>";
    $html .= "<b>".$_SYSTEM_TEXT['Line']['lex_lang_desc'].":</b> ".$catalog['family']."<br/>";
    $html .= "<b>".$_SYSTEM_TEXT['Region']['lex_lang_desc'].":</b> ".self::get_regions(array('mc_api'=>$MC_API, 'system_text'=>$_SYSTEM_TEXT, 'regions'=>$catalog['data_regions']))."<br/>";
    
    $vehicle_type = $MC_API->mc_lexicon_system_text_desc(array('system_text'=>$_SYSTEM_TEXT, 'lex_sys'=>$catalog['vehicle_type'], 'prepare_1st'=>1));
    $html .= "<b>".$_SYSTEM_TEXT['Vehicle Type']['lex_lang_desc'].":</b> ".$vehicle_type."<br/>";
    
    $html .= "<b>".$_SYSTEM_TEXT['Model year']['lex_lang_desc'].":</b> ".$catalog['year_from']."&minus;".$catalog['year_to']."<br/>";
    $html .= "<b>".$_SYSTEM_TEXT['Start Date']['lex_lang_desc'].":</b> ".self::get_date($catalog['c0_production_from'])."<br/>";
    $html .= "<b>".$_SYSTEM_TEXT['End Date']['lex_lang_desc'].":</b> ".self::get_date($catalog['c0_production_to'])."<br/>";
    
    return $html;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// код каталога
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function get_catalog_code(array $catalog){  
    if(empty($catalog))
      return "";
    
    return $catalog['catalogue_code']." (".$catalog['group_type'].")";
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// путь к рисунку каталога
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function get_catalog_image_path(array $prms){  
    if(empty($prms['cat_folder']))
      return "";
    
    return $this->img_data."Cutups/".$prms['cat_folder'].".rle";
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// путь к красивому рисунку каталога
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function get_catalog_image_ttl_path(array $prms){  
    if(empty($prms['cat_folder']))
      return "";
    
    return $this->img_data."Titles/".$prms['cat_folder'].".png";
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// путь к рисунку основной секции авто
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function get_cat_major_sect_image_path(array $prms){  
    if(empty($prms['major_sect_code']))
      return "";
    
    return $this->img_data."Maj/".$prms['major_sect_code'].".png";
  }
 
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// путь к рисунку дополнительных секции авто
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function get_cat_dat_image_path(array $prms){ 
    if(empty($prms['cat_folder']) or empty($prms['image_name']))
      return "";
    
    return $this->img_data."Cats/".$prms['cat_folder']."/".$prms['image_name'].".png";
  }
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Кординаты основного раздела рисунков
  //  схемы сектора лежат в одной таблице, имеют одинковый формат => используем в обоих случаях  
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function cat_dat_image_map(array $prms){
    if(empty($prms['dat_image']) or empty($prms['image_name']))
      return "";
    
    $html = "";
		$html .= "<map name='".$prms['image_name']."'>";
    foreach($prms['dat_image'] as $row){
			$x1 = $row['x1'];
			$y1 = $row['y1'];
			$x2 = $row['x2'];
			$y2 = $row['y2'];
			$alt = $row['ref'];
      
      // если не нашло, то - несовместимо!
      if(empty($prms['sectors'][$alt]))
        continue;
        
      // более полная информация сектора = array_group($MC_API->cat_map_minor_section, 'sector');
      $sector = $prms['sectors'][$alt][0]; //берем тупо первую запись
      $alt = "[".$sector['sector_format']."]"." - ".$sector['minor_lex_desc'];      
			$html .= "<area shape='rect' coords='$x1,$y1,$x2,$y2' alt='$alt' title='$alt'/>";		//href='#$alt' 
		}
		$html .= "</map>";
    
    return $html;
  }
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Кординаты элементов на рисунке minor_section = PNC + другие minor_section
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function cat_dat_image_pnc_map(array $prms){
    if(empty($prms['dat_image']) or empty($prms['image_name']))
      return "";
    $_SYSTEM_TEXT = $prms['system_text'];

    $html = "";
		$html .= "<map name='".$prms['image_name']."'>";
    foreach($prms['dat_image'] as $row){
			$x1 = $row['x1'];
			$y1 = $row['y1'];
			$x2 = $row['x2'];
			$y2 = $row['y2'];
			$ref = $row['ref'];
      
      //pnc
      if($row['ref_type'] == '1' and !empty($prms['cat_pnc'][$ref])){
        $pnc = $prms['cat_pnc'][$ref];
        $pnc_name = (!empty($pnc['name_loc_lex_desc'])) ? $pnc['name_loc_lex_desc'] : $pnc['name_def_lex_desc']; // на выбранном может не быть
        $pnc_desc = (!empty($pnc['desc_lex_desc'])) ? (", ".$pnc['desc_lex_desc']) : "";
        $pnc_url = (!empty($prms['pnc_url'])) 
                     ? ($prms['pnc_url']."&maj_sect=".$pnc['major_sect']."&min_sect=".$pnc['minor_sect']."&pnc=".$pnc['pnc']) 
                     : "";

        $alt = "[".$pnc['pnc']."]"." - ".$pnc_name.$pnc_desc;      
        $html .= "<area shape='rect' coords='$x1,$y1,$x2,$y2' href='$pnc_url' alt='$alt' title='$alt'/>";		//href='#$alt' 
      }
      
      //ссылки на сектора
      // $cat_image_minor_ref[sector_format] => array(
      //                    ['pnc'] => pnc in the ['sector']
      //                    ['sector'] => array of minor_section
      if($row['ref_type'] == '5' and !empty($prms['cat_image_minor_ref'])){
        // key в $prms['cat_image_minor_ref'] - уже распарсенный и найденный sector_format
        $sector_data_selected = array();
        foreach($prms['cat_image_minor_ref'] as $sector => $sector_data){
          if(strpos($ref, $sector) !== false){
            $sector_data_selected = $sector_data;
            break;    //нашли
          }
        }
        
        $alt = ""; $href = "";
        if(!empty($sector_data_selected)){
          //pnc
          $pnc_str = ""; $sect_pnc_url = "";
          if(!empty($sector_data_selected['pnc'])){
            $pnc_str .= "PNC: ".$sector_data_selected['pnc']." => ";
            $sect_pnc_url = "&pnc=".$sector_data_selected['pnc'];
          }
          
          // sector
          $sect_str = ""; $sect_url = "";
          if(!empty($sector_data_selected['sector'])){
            $minor_sec = $sector_data_selected['sector'][0];  //показать тупо первую запись minor_section из всего списка для sector
            //url
            $sect_url .= "&maj_sect=".$minor_sec['major_sect']."&min_sect=".$minor_sec['minor_sect'];
            //name
            $prms['minor_sect'] = $minor_sec;
            $minor_section_info = self::minor_section_info($prms);
            $sect_str = "[".$minor_section_info['sector_format']."] - ".$minor_section_info['name'];
          }
          
          $alt = $_SYSTEM_TEXT['Displays Section:']['lex_lang_desc']." ".$pnc_str.$sect_str;      
          $href = (!empty($prms['pnc_url']) and !empty($sect_url)) ? ($prms['pnc_url'].$sect_url.$sect_pnc_url) : "";
        }
        $html .= "<area shape='rect' coords='$x1,$y1,$x2,$y2' href='$href' alt='$alt' title='$alt'/>";		//href='#$alt' 
      } // sector
		}
		$html .= "</map>";
    
    return $html;
  }
 
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// сгенерировать кнопку выбора каталога
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function major_section_button(array $prms){
    $major_sect_image=$prms['major_sect_image'];
    $major_sect_url=$prms['major_sect_url']; 
    $major_section=$prms['major_section']; // array
    
    $maj_sect_code = $major_section['major_sect'];
    $major_sect_url .= "&maj_sect=$maj_sect_code";
    
    $html = "";
    $html .= "<div align='center'>";
    $html .= "<a href='?$major_sect_url' title='".$major_section['lex_desc']."'>";
    $html .= "<button>";
    $html .= "<img src='$major_sect_image' alt='".$major_section['lex_desc']."' />";
    $html .= "<br/>";
    $html .= "[$maj_sect_code] - ".$major_section['lex_desc'];    
    $html .= "</button>";
    $html .= "</a>";
    $html .= "</div>";
    
    return $html;
  }
 
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// MINOR SECTION: запись в виде кнопки
  //  is_show_local_logo - нужно ли показывать логотип?
  //  local_logo_width - размеры логотипа
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  public function minor_section_button(array $prms){
    $minor_sect = $prms['minor_sect'];
    $minor_sect_url = $prms['minor_sect_url'];
    if($minor_sect_url <> ""){
      $minor_sect_url .= "&min_sect=".$minor_sect['minor_sect'];
    }
    
    $html = "";
    $html .= "<a href='$minor_sect_url' title=''>";
    $html .= "<button style='text-align:left; width:100%;'>";
    // логотип 
    //if(($minor_sect['minor_sect_type'] == 'LOCAL') // можно показать только для типа LOCAL
    if(!empty($prms['is_show_logo'])){ // нужно ли показывать
      //$_IMG_DATA."Cats/".$minor_sect.".png";
      $dat_image = $this->get_cat_dat_image_path(array('cat_folder'=>$minor_sect['cat_folder'], 'image_name'=>$minor_sect['minor_sect']));
      $logo_width = (isset($prms['logo_width']) ? $prms['logo_width'] : '50px');
      $html .= "<img src='$dat_image' alt='' style='float: left; vertical-align: middle; width: $logo_width; border:1px solid black;' />";
    }
    // секция
    $minor_section_info = self::minor_section_info($prms);
    $html .= "[".$minor_section_info['sector_format']."] - ".$minor_section_info['name'];
    // ucc
    if(!empty($minor_section_info['ucc'])){
      $html .= "<br/><i>".$minor_section_info['ucc']."</i>";
    }
    // option
    if(!empty($minor_section_info['option'])){
      $html .= "<br/><i>".$minor_section_info['option']."</i>";
    }
    
    $html .= "</button>";
    $html .= "</a>";

    return $html;
  }
 
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Список pnc на рисунке
  //  Для построения списка лучше использовать cats_dat_parts.pnc1 + pnc2, чем cats_dat_ref.ref 
  //  cat_pnc - список pnc = MC_API::cat_dat_parts_pnc(cats_dat_parts)
  //  
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function pnc_image_list(array $prms){
    $cat_pnc = $prms['cat_pnc'];
    $lang_code = $prms['lang_code'];  
    $_SYSTEM_TEXT = $prms['system_text'];
    
    $html = "";
    $html .= "<table class='pnc_image_list'>";
    foreach($cat_pnc as $pnc){
      $pnc_url = $prms['pnc_url'];
      if(!empty($pnc_url)){
        $pnc_url .= "&maj_sect=".$pnc['major_sect']."&min_sect=".$pnc['minor_sect']."&pnc=".$pnc['pnc'];
      }
      
      $html .= "<tr>";
      //pnc
      $html .= "<td>";
      $html .= "<a href='$pnc_url' title=''>".$pnc['pnc']."</a>";
      $html .= "</td>";
      //name 
      $html .= "<td class='pnc_name'>";      
      $html .= self::pnc_part_name(array(
                  'lang_code'=>$prms['lang_code'], // выбранный язык
                  'name_def_lex_desc'=>$pnc['name_def_lex_desc'], // английский
                  'name_loc_lex_desc'=>$pnc['name_loc_lex_desc'], // текст на выбранном языке
                  'desc_lex_desc'=>$pnc['desc_lex_desc'], // примичание, на любом языке какое будет
                  'name_br'=>"<br/>", // разделитель между текстами разных языков
                  ));
      $html .= "</td>";
      $html .= "</tr>";
    }
    
    //ссылки на сектора
    // $cat_image_minor_ref[sector_format] => array(
    //                    ['pnc'] => pnc in the ['sector']
    //                    ['sector'] => array of minor_section
    if(!empty($prms['cat_image_minor_ref'])){
      foreach($prms['cat_image_minor_ref'] as $sector => $sector_data){

        //pnc
        $pnc = ""; $sect_pnc_url = "";
        if(!empty($sector_data['pnc'])){
          $pnc .= "PNC: <b>".$sector_data['pnc']."</b>, ";
          $sect_pnc_url = "&pnc=".$sector_data['pnc'];
        }
        
        // sector
        // тут можно извращатся: показать тупо первую запись minor_section из всего списка для sector или как в примере все записи
        if(empty($sector_data['sector'])){
          // sector не найдено, sect_url = некуда направлять
          $html .= "<tr class='sector'>";
          $html .= "<td>".$sector."</td>";
          $html .= "<td class='pnc_name'>".$pnc.$_SYSTEM_TEXT['Displays Section:']['lex_lang_desc']."</td>";
          $html .= "</tr>";         
        } else {
          foreach($sector_data['sector'] as $minor_sec){
            $sect_url = $prms['pnc_url'];
            if(!empty($sect_url)){
              $sect_url .= "&maj_sect=".$minor_sec['major_sect']."&min_sect=".$minor_sec['minor_sect'].$sect_pnc_url ;
            }
            
            $prms['minor_sect'] = $minor_sec;
            $minor_section_info = self::minor_section_info($prms);
            
            $html .= "<tr class='sector'>";
            $html .= "<td>";
            $html .= "<a href='$sect_url' title=''>".$sector."</a>";
            $html .= "</td>";
            $html .= "<td class='pnc_name'>";
            $html .= $pnc;
            // секция
            $html .= $minor_section_info['name'];
            // ucc
            if(!empty($minor_section_info['ucc'])){
              $html .= "<br/><i>".$minor_section_info['ucc']."</i>";
            }
            // option
            if(!empty($minor_section_info['option'])){
              $html .= "<br/><i>".$minor_section_info['option']."</i>";
            }
            $html .= "</td>";
            $html .= "</tr>";
          }
        }
      }
    }
    
    $html .= "</table>";
    
    return $html;
  }
 
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// MINOR SECTION: ВСЯ Инфо по записи
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static public function minor_section_info(array $prms){
    $minor_sect = $prms['minor_sect'];
    
    // sector_format of minor_section
    $html_sector_format = $minor_sect['sector_format'];
    
    // name
    $html_name = $minor_sect['minor_lex_desc'];
    // note
    if(!empty($minor_sect['note_lex_desc']))  
      $html_name .= ", ".$minor_sect['note_lex_desc'];
    // часть sector of minor_section
    if(!empty($minor_sect['sector_part']))  
      $html_name .= " "."(".$minor_sect['sector_part'].")";
    
    // ucc
    $html_ucc = ""; $sep = "";
    if(!empty($prms['catalog_ucctype']) and !empty($prms['catalog_ucc']) and !empty($minor_sect['compatibility_unpack']['ucc'])){
      $prms['ucc'] = $minor_sect['compatibility_unpack']['ucc'];
      $ucc_values_info = self::ucc_values_info($prms);
      foreach($ucc_values_info as $ucc_val_inf){
        if(!empty($ucc_val_inf['uccval_des'])){
          $html_ucc .= $sep;
          $html_ucc .= $ucc_val_inf['ucctype_des'].": ".$ucc_val_inf['uccval_des'];
          $sep = "<br/>";
        }
      }
    }
    
    // option
    $html_option = ""; $sep = ""; 
    if(!empty($prms['cat_options_des']) and !empty($minor_sect['compatibility_unpack']['option'])){
      foreach($minor_sect['compatibility_unpack']['option'] as $opt){
        $html_option .= $sep;
        $html_option .= "[$opt] ".$prms['cat_options_des'][$opt]['lex_desc']."";
        $sep = "<br/>";
      }
    }

    return array('sector_format'=>$html_sector_format, 'name'=>$html_name, 'ucc'=>$html_ucc, 'option'=>$html_option);
  }
 
  
  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// PNC,PART: формирования текстовго описания элемента
  //  PNC,PART - содержат одинаковые поля: имени и описания
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static public function pnc_part_name(array $prms){
    $name_br = isset($prms['name_br']) ? $prms['name_br'] : "<br/>";
  
    $desc = "";
    if(!empty($prms['desc_lex_desc'])){
      $desc = ", ".$prms['desc_lex_desc'];
    }
    
    $html = "";
    $html .= "<span class='pnc_name_def'>".$prms['name_def_lex_desc'].$desc."</span>"; // английский
    if(($prms['lang_code'] <> 'EN') and !empty($prms['name_loc_lex_desc'])){    // выбранный язык
      $html .= $name_br."<span class='pnc_name_loc'>".$prms['name_loc_lex_desc'].$desc."</span>";
    }
    
    return $html;
  }
  

/*********************************************************************/
//  ОПЦИИ КОМПЛЕКТАЦИИ


  //~~~~~~~~~~~~~~~~~~~~~~~~
	// Вывод инфо для установленных ucc
  // return array() => во view - самостоятельно пербираем 
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function ucc_values_info(array $prms){
    $ucc = $prms['ucc'];    // ucc-строка (VIN, cats_map.minor_section.compatibility, cats_dat_parts.compatibility)
    $catalog_ucctype = $prms['catalog_ucctype'];  // виды ucc-харакетристик авто
    $catalog_ucc = $prms['catalog_ucc'];          // значение ucc-харакетристик авто typ=>lists_of_values
    
    $ucc = rtrim($ucc, '|');   // для оптимизации кол-ва итераций, нужно убрать ненужные только справа!
    if($ucc == '') return array();
      
    $ucc = explode('|', $ucc); // позиция очень важная штука
    $res = array();
    foreach($ucc as $f_typ => $f_val){  // тип и значение ucc-элемента в ucc-строке
    
      // проверка существует ли текущий тип Характеристики в модели (тупо по позиции)
      if(!isset($catalog_ucctype[$f_typ])) continue; // KMXMSE1PPWU047971
      $ucctype = $catalog_ucctype[$f_typ];           // тип ucc-харакетристики 'ucc_type', 'lex_desc'
      $ucctype_typ = $ucctype['ucc_type'];
      $ucctype_des = $ucctype['lex_desc'];
      
      // тип ucc-харакетристик со своим списком возможных значений 
      $catalog_ucc_values = isset($catalog_ucc[$ucctype_typ]) ? $catalog_ucc[$ucctype_typ] : array();
      $ucc_val_des = isset($catalog_ucc_values[$f_val]) ? $catalog_ucc_values[$f_val]['ucc_lex_desc'] : "";
      
      $res[$f_typ] = array('ucctype_typ'=>$ucctype_typ, 'ucctype_des'=>$ucctype_des, 'uccval_val'=>$f_val, 'uccval_des'=>$ucc_val_des);
    }

    return $res;
  }

  
  //~~~~~~~~~~~~~~~~~~~~~~~~
	// определить код ucctype для параметра урла
  //  DT и WT не учавствуют в матрице совмеситмости, идут отдельной проверку, поэтому отдельно выносим чтобы Микрокат не смог сбить
  //
  //  view_vehicle.php?lang_code=RU&catalog_code=MES5A0GA98   В имени харакетристики: '&' => Door & Floor
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function get_catalog_ucctype_name(array $catalog_ucctype_key, $ucc_type=""){
    if($ucc_type == "DT")
      $ucctype_key = "veh_drive_type"; 
    elseif($ucc_type == "WT")
      $ucctype_key = "veh_weather_type";
    else{ 
      $ucctype_key = $catalog_ucctype_key[$ucc_type]['lex_desc']; 
      $ucctype_key = str_replace(array('&',' '), '_', strtolower($ucctype_key));
      $ucctype_key = "vo_".$ucctype_key; //vehicle option
    }
    
    return $ucctype_key;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// получить опции авто, относительно порядка следования опций в $_REQUEST
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function get_request_vehicle_options(array $REQUEST){
    $veh_options = "";
    foreach($REQUEST as $url_param => $val){
      if(substr($url_param,0,3) == "vo_"){ //get_catalog_ucctype_name
        $veh_options .= $val."|";  // стандарт Microcat
      }
    }
    
    return $veh_options;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// получить опции авто, независимо от порядка параметров в $_REQUEST
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function get_request_vehicle_options2(array $prms){
    $REQUEST = $prms['request'];
    $catalog_ucctype_key = $prms['catalog_ucctype_key'];
  
    $veh_options = "";
    foreach($catalog_ucctype_key as $ucc_type =>$ucctype_desc){
      if(in_array($ucc_type, array("DT", "WT")))
        continue;
      $ucctype_key = self::get_catalog_ucctype_name($catalog_ucctype_key, $ucc_type);
      
      $val = "";
      if(isset($REQUEST[$ucctype_key]))
        $val = $REQUEST[$ucctype_key];
        
      $veh_options .= $val."|";  // стандарт Microcat
    }
    
    return $veh_options;
  }
  
/* <<<*********************************************************** */

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// дата для вывода
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function get_date($date="", $sep='-'){  
    $date = trim($date);
    if(strlen($date) != 8)
      return $date;
    
    return substr($date,0,4).$sep.substr($date,4,2).$sep.substr($date,6,2);
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// дата для вывода
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function get_date_dmy($date="", $sep='.'){  
    $date = trim($date);
    if(empty($date))
      return '';  // очищаем, даже если 0
      
    if(strlen($date) != 8)
      return $date;
    
    return substr($date,6,2).$sep.substr($date,4,2).$sep.substr($date,0,4);
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// расшифровка регионов
	//~~~~~~~~~~~~~~~~~~~~~~~~ 
  static function get_regions(array $prms){
    $MC_API = $prms['mc_api'];
    $_SYSTEM_TEXT = $prms['system_text'];
  
    $regions = trim($prms['regions'], '|');
    if(empty($regions))
      return $regions;
      
    $regions = explode('|', $regions);
    
    $html = "";
    $sep = '';
    foreach($regions as $row){
      $html .= $sep.'['.$row.']'.' ';
      $html .= $MC_API->catalog_region_desc(array('system_text'=>$_SYSTEM_TEXT, 'region'=>$row));;
      $sep = ', ';
    }
    
    return $html;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// получить список пораметров _REQUEST
	// 13.11.2014
  //  $request - массив аля $_REQUEST
  //  $escape - что нужно пропускать (эти параметры задаются отдельно)
	//~~~~~~~~~~~~~~~~~~~~~~~~	
	static function get_request_url(array $request, $escape=''){
    //if(!empty($escape))
    $escape = explode(',', $escape);
  
    $url = "";
    $sep = '';
    foreach($request as $k=>$v){
      if(in_array($k, $escape)) continue;
      $url .= $sep."$k=$v";
      $sep = '&';
    }
    
    return $url;
  }


	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Скинуть фильтрацию по определнной группе фильтров - тупо заточка под опции фильтрации
	// 22.03.2015
	//~~~~~~~~~~~~~~~~~~~~~~~~	
	static function get_url_filter_all(array $prms){
    $_SYSTEM_TEXT = $prms['system_text'];
    $url_prms = $prms['url_prms'];

    $html = "<div class='divlink_b' align='center'>";
    $html .= "<a href='?".$url_prms."' >";
    $html .= "<b>[".$_SYSTEM_TEXT['ALL']['lex_lang_desc']."]</b>";
    $html .= "</a>";
    $html .= "</div>";    
    return $html;
  }

  
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// VIN: вывод опционных кодов-описаний
	// 25.10.2014
	//~~~~~~~~~~~~~~~~~~~~~~~~	
	static function vin_option_codes(array $prms){
    $OPTIONS_DES = $prms['options_des'];
    $vin_options = $prms['vin_options'];
    
		$html = "";   
    foreach($vin_options as $opt_code){
      $opt_desc = "";
      if(isset($OPTIONS_DES[$opt_code]))
        $opt_desc = $OPTIONS_DES[$opt_code]['lex_desc'];
      
      $html .= "[$opt_code]&emsp;$opt_desc<br/>";
    }
    
		return $html;
  }
	
}