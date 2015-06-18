<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *		14.02.2013 
 * 		Предлагается сделать какойто модуль в котором будт собраны все обращения к БД, 
 * 		тогда заказчим будет проще адаптировать изменения в своих проектах. 
 *		Им достаточно будет просто подключить этот модуль и вызыватьнужные им функции
 ************************************************************/

 //class TOY_API { } может класом забабахать !?



/*---------------------------
 * Выполнить запрос. Если произойдет ошибка - вывести ее.
 *---------------------------*/
function _mysql_query($q, $is_show=true){
	// Показать тело запроса
	$is_show=false; 
	//$is_show=true; 
	if($is_show) echo "<pre><b>$q</b></pre>";

	$res_query = mysql_query($q) or die(mysql_error());	
	return $res_query;
}

/*---------------------------
 * Выполнить запрос. Отдать ассоциотивный массив
 *---------------------------*/
function _mysql_query_arr($q, $is_show=true){
	// выполним
	$r_q = _mysql_query($q,$is_show); 
	
	// соберем результат в массив
	$res = array();
	while($row = mysql_fetch_array($r_q, MYSQL_ASSOC)){
		$res[] = $row;
	}
	
	return $res; //результат
}



/*---------------------------
 * Вытянуть информацию про каталог автомобиля
 *	$prms - массив входящих параметров
 *---------------------------*/
function _TOY_shamei_info($prms){
	$q = "SELECT * FROM shamei WHERE catalog = '".$prms['catalog']."' AND catalog_code = '".$prms['catalog_code']."';";
	$r_q = _mysql_query($q); // выполним
	$res = mysql_fetch_array($r_q, MYSQL_ASSOC);
	return $res;
}



/*---------------------------
 * Вытянуть информацию про модель автомобиля по парметрам модели, при этом vin8 - упускается
 *	$prms - массив входящих параметров
 *---------------------------*/
function _TOY_johokt_model_info($prms){
	$q = "
SELECT DISTINCT
    catalog,catalog_code,model_code,prod_start,prod_end,frame,sysopt,compl_code,engine1,engine2,body,grade,atm_mtm,trans,f1,f2,f3,f4,f5 
FROM johokt 
WHERE catalog = '".$prms['catalog']."'
    AND catalog_code = '".$prms['catalog_code']."'
    AND model_code = '".$prms['model_code']."'
    AND sysopt = '".$prms['sysopt']."'
    AND compl_code = '".$prms['compl_code']."'
;";
	$res = _mysql_query_arr($q); // выполним
	
	// результатом должна быть только одна строчка, будем ловить если не так
	if(count($res)>1) var_dump('БЛЯХА - почемуто больше чем одна запись, а хочется однозначно найти модель!',$res);
	
	return $res[0];	
}
/*~~~
19.01.2014 - однозначно определяют выбранную модель johokt
SELECT catalog,	catalog_code, vin8, model_code, sysopt, compl_code, COUNT(*)
FROM johokt
GROUP BY catalog,	catalog_code, vin8, model_code, sysopt, compl_code
HAVING COUNT(*) > 1

из-за ошибок, prod_start - не заменяет compl_code
SELECT catalog,	catalog_code, vin8, model_code, prod_start, sysopt, COUNT(*)
FROM johokt
GROUP BY catalog,	catalog_code, vin8, model_code, prod_start, sysopt
HAVING COUNT(*) > 1

22.01.2014 - сторона руля
Значит LHD - определяется полями f1,f2 и f5, при этом оно может быть в любом поле. Потому оставлять именно f2 (я понял вы именно из-за расположение решили его вывести, смысла нету).
Но есть такие машины, которые не имеют значение LHD или RHD. Я предлагаю выводить авто которые конкретно НЕ праворукие (RHD) - определять по полям f1,f2 и f5.
~~~*/



/*---------------------------
 * Вытянуть всю информацию по VIN
 *	$prms - массив входящих параметров
 *---------------------------*/
function _TOY_VIN_info($prms){
	// VIN - проверка
	$vin = trim($prms['vin']); 
	if(empty($vin)){
		echo "_TOY_VIN_info - пустой VIN";
		return array();
	}
	// VIN - serial number
	$vin_sn = substr($vin, -7);

	// mysql_fetch_array($r_q, MYSQL_ASSOC) - будет глатать поля с одинаковыми именами, поэтму нужно предусмотреть для frames.catalog имя f_catalog
	$q = "
SELECT 
    frames.id,frames.catalog f_catalog,frames.frame_code,frames.serial_group,frames.serial_number,frames.ext,
    frames.model2,frames.vdate,frames.color_trim_code,frames.siyopt_code,frames.opt,
    johokt.*,
    shamei.*
  FROM johokt
	
    JOIN frames
	  ON frames.frame_code = johokt.frame
  
    JOIN shamei 
	  ON shamei.catalog = johokt.catalog
	  AND shamei.catalog_code = johokt.catalog_code
  WHERE
	# Первые 1-8(9) символов VIN
    johokt.vin8 LIKE CONCAT(SUBSTRING('$vin', 1, 8), '%') # включить поиск по индексу
    AND johokt.vin8 = SUBSTRING('$vin', 1, LENGTH(vin8)) # уточнить поиск по длине 1-9 символов VIN
	
	# уточняем вин по серийнику
    AND frames.catalog = IF(johokt.catalog = 'JP', 'DM', 'OV')	
    AND frames.serial_number = '$vin_sn'
    #AND CONCAT(frames.frame_code, SUBSTRING(frames.ext,-1), '-', SUBSTRING_INDEX(frames.model2, '(', 1)) = johokt.model_code
	# поле ext - уже коректно 2013.06.01 
    AND CONCAT(frames.frame_code, frames.ext, '-', SUBSTRING_INDEX(frames.model2, '(', 1)) = johokt.model_code
	
	# уточняем вин по дате
    AND (frames.vdate BETWEEN johokt.prod_start AND johokt.prod_end 
        OR IFNULL(frames.vdate, '') = '') # заглушка если vdate - пустая дата
		
	# подбираем корректный johokt для найденного frames 2013.02.09 
    AND (SUBSTRING(frames.siyopt_code, 1, 4) = johokt.sysopt
        OR IFNULL(johokt.sysopt, '') = ''       # заглушка если sysopt - пустой
        OR IFNULL(frames.siyopt_code, '') = '')	# заглушка если siyopt_code - пустой";

	// Если известен регион - значит мы получаем однозначный результ по VIN
	if(isset($prms['catalog']))
		$q.= "
    # Задано регион - нужно получить точную запись
    AND johokt.catalog = '".$prms['catalog']."'";

	$res = _mysql_query_arr($q); // выполним

	// просимофорим разработчика что с VIN получился не однозначный поиск
	// результатом должна быть только одна строчка, будем ловить если не так
	if(isset($prms['catalog']) and count($res)>1) var_dump('БЛЯХА - почемуто больше чем одна запись, а хочется однозначно найти VIN!',$res);
	
	return $res;	
}
/*~~~
 По идее VIN-запрос будет получать однозначные записи из johokt
	
 	Проверка
		Немного грубовато с prod_start, но дата участвует в отборе frames.vdate BETWEEN johokt.prod_start AND johokt.prod_end
	SELECT catalog,catalog_code,vin8,model_code,prod_start,sysopt, COUNT(compl_code)
	FROM johokt
	WHERE vin8 <> '' # при VIN поиске будет всегда задано
	GROUP BY catalog,catalog_code,vin8,model_code,prod_start,sysopt
	HAVING COUNT(compl_code) > 1


	некоторые frames имеют в этом поле 1-й непонятный символ, например JTEHT05J202087962 
	2013.06.01 = 2 символа в конце 1 части MHFFMRWK30K066683
	
// 		а также
//		OV ! ACV30 ! U160 ! U160344 ! 6 ! L ! CEANKA ! 200601 ! 4Q2FB45 ! 303WZ01E ! 0
//		OV ! ACV30 ! U160 ! U160345 ! 3 ! L ! CEPNKA ! 200212 ! 8Q0FB13 ! 184W ! 0
//
// Обязательно SUBSTRING_INDEX(frames.model2, '(', 1)) - бо бывают вот такие frames
//		DM ! CE121 ! 3001 ! 3001563 !  ! AEPNE(A) ! 200105 ! 040YG17 ! 186WZ07H ! 0
//		DM ! CE121 ! 3001 ! 3001564 !  ! AEMNE(A) ! 200105 ! 040YG17 ! 186WZ07H ! 0
// 		вот все возможные, для них johokt : CE121-AEMEE, CE121-AEMNE, CE121-AEPEE, CE121-AEPNE, CE121G-AWPNE
//		потому предлагается тупо убирать все что в собках (A)
~~~*/



/*---------------------------
 * Вытянуть всю информацию по VIN используя фрейм
 *  2014.11.22
 *	$prms - массив входящих параметров
 *---------------------------*/
function _TOY_VIN_FRAME_info($prms){
	// VIN - проверка
	$vin = trim($prms['vin']); 
	if(empty($vin)){
		echo __FUNCTION__ . ": - пустой VIN";
		return array();
	}
	//$vin_sn = substr($vin, -7); // VIN - serial number

	/* Запрос EPC Toyota */
	$query = "
SELECT
    johokt.*, 
    get_vdate_frameno(johokt.catalog, johokt.frame, SUBSTRING('$vin',-7)) vdate,
    shamei.f1 AS sh_f1, shamei.model_name, shamei.models_codes, shamei.opt,
    shamei.prod_start AS sh_prod_start, shamei.prod_end AS sh_prod_end, shamei.rec_num      
  FROM johokt
    JOIN shamei 
      ON shamei.catalog = johokt.catalog
      AND shamei.catalog_code = johokt.catalog_code    
  WHERE
    # Первые 1-8(9) символов VIN
    vin8 LIKE CONCAT(SUBSTRING('$vin', 1, 8), '%') # включить поиск по индексу
    AND vin8 = SUBSTRING('$vin', 1, LENGTH(vin8)) # уточнить поиск по длине 1-9 символов VIN
;";
  //echo "<pre>$query</pre>";
/*
        # коррекция даты выпуска VIN, она не может быть меньше чем дата производства модели авто
        # если версия MySQL меньше 5.0.13, при пустом значении get_vdate_frameno - результирющий vdate нужно обязательно опусташать, 
        #     т.е. считается что дата неизвестная
        GREATEST(get_vdate_frameno(johokt.catalog, johokt.frame, SUBSTRING('$vin',-7)), 
           johokt.prod_start) vdate,
*/

	$vin_frame = _mysql_query_arr($query); // выполним
  if(empty($vin_frame))
    return array();

  // необходимо поправить дату выпуска по фрейму-номеру
  foreach($vin_frame as $k => $row){   
    $vdate = $row['vdate'];
    if(($vdate != '')
      and (($vdate < $row['prod_start']) or ($vdate > $row['prod_end']))) // не должен выходить за рамки
        $vin_frame[$k]['vdate'] = '';
  }
  
	return $vin_frame;	
}



/*---------------------------
 * Вытянуть всю информацию по VIN используя фрейм без вызова функции get_vdate_frameno
 *  2014.11.22
 *	$prms - массив входящих параметров
 *---------------------------*/
function _TOY_VIN_FRAME_info_nofunc($prms){
	// VIN - проверка
	$vin = trim($prms['vin']); 
	if(empty($vin)){
		echo __FUNCTION__ . ": - пустой VIN";
		return array();
	}
	$vin_sn = substr($vin, -7); // VIN - serial number

	/* Запрос EPC Toyota */
	$query = "
SELECT
    johokt.*,
    '' vdate, # заглушка
    shamei.f1 AS sh_f1, shamei.model_name, shamei.models_codes, shamei.opt,
    shamei.prod_start AS sh_prod_start, shamei.prod_end AS sh_prod_end, shamei.rec_num      
  FROM johokt
    JOIN shamei 
      ON shamei.catalog = johokt.catalog
      AND shamei.catalog_code = johokt.catalog_code    
  WHERE
  # Первые 1-8(9) символов VIN
    vin8 LIKE CONCAT(SUBSTRING('$vin', 1, 8), '%') # включить поиск по индексу
    AND vin8 = SUBSTRING('$vin', 1, LENGTH(vin8)) # уточнить поиск по длине 1-9 символов VIN
;";
  //echo "<pre>$query</pre>";

	$vin_frame = _mysql_query_arr($query); // выполним
  if(empty($vin_frame))
    return array();
 
  // найдем дату выпуска по фрейму-номеру и поправим если она за пределами
  $res = array();
  foreach($vin_frame as $row){
    $vdate = _TOY_get_vdate_frameno(array('catalog'=>$row['catalog'], 'frame_code'=>$row['frame'], 'serial_number' => $vin_sn));
    
    if(($vdate != '')
      and (($vdate < $row['prod_start']) or ($vdate > $row['prod_end']))) // не должен выходить за рамки
        $vdate = '';

    $row['vdate'] = $vdate;
    $res[] = $row;
  }
  
	return $res;	
}



/*---------------------------
 * Получит дату производстав авто по frameno, заменить функцию MySQL - get_vdate_frameno
 *  2014-11-18
 *	$prms - массив входящих параметров
 *---------------------------*/
function _TOY_get_vdate_frameno($prms){
  static $vdate_frameno = array(); // уберем лишнии вызовы

	if(!isset($prms['catalog']) or !isset($prms['frame_code']) or !isset($prms['serial_number'])){ 		// обязательно задано и массив
		die(__FUNCTION__ . ": Незаданы обязательные параметры !!! ");
	}

  $_cat = $prms['catalog'];
  $_fr_c = $prms['frame_code'];
  $_ser = $prms['serial_number'];
  $key = $_cat.'~'.$_fr_c;
  
  // уберем лишнии вызовы sql
  if(isset($vdate_frameno[$key]))
    return $vdate_frameno[$key];
  
	$q = "
SELECT
    CONCAT(`year`, mon) vdate
  FROM (
    SELECT catalog, frame_code, `year`, '01' AS mon, m01 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    UNION
    SELECT catalog, frame_code, `year`, '02' AS mon, m02 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    UNION
    SELECT catalog, frame_code, `year`, '03' AS mon, m03 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    UNION
    SELECT catalog, frame_code, `year`, '04' AS mon, m04 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    UNION
    SELECT catalog, frame_code, `year`, '05' AS mon, m05 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    UNION
    SELECT catalog, frame_code, `year`, '06' AS mon, m06 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    UNION
    SELECT catalog, frame_code, `year`, '07' AS mon, m07 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    UNION
    SELECT catalog, frame_code, `year`, '08' AS mon, m08 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    UNION
    SELECT catalog, frame_code, `year`, '09' AS mon, m09 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    UNION
    SELECT catalog, frame_code, `year`, '10' AS mon, m10 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    UNION
    SELECT catalog, frame_code, `year`, '11' AS mon, m11 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    UNION
    SELECT catalog, frame_code, `year`, '12' AS mon, m12 AS ser, start_no
    FROM framno fn
    WHERE fn.catalog = '$_cat' AND fn.frame_code = '$_fr_c'
    ORDER BY catalog, frame_code, ser DESC
  ) ser_period
  WHERE 
    ser_period.ser <> ''
    AND ser_period.ser <= '$_ser' # верхняя граница периодов выпуска блогодоря ser DESC + LIMIT 1
    AND SUBSTRING(start_no, 1, 1) = SUBSTRING('$_ser', 1, 1) # нижняя граница (NOT! <=)
  LIMIT 1
";
  //echo "<pre>$q</pre>";

	$res = _mysql_query_arr($q); // выполним
  if(empty($res))
    $res = '';
  else
    $res = $res[0]['vdate'];
    
  $vdate_frameno[$key] = $res; // запомним результат
  
	return $res;
}



/*---------------------------
 * Вытянуть всю информацию по VIN используя Модель
 *  2014.11.22
 *	$prms - массив входящих параметров
 *---------------------------*/
function _TOY_VIN_MODEL_info($prms){
	// VIN - проверка
	$vin = trim($prms['vin']); 
	if(empty($vin)){
		echo __FUNCTION__ . ": - пустой VIN";
		return array();
	}
	//$vin_sn = substr($vin, -7); // VIN - serial number

	/* Запрос EPC Toyota */
	$query = "
SELECT DISTINCT johovn.*, shamei.*
  #johovn.vin8, johovn.model_code, shamei.* 
  FROM johovn
  
	JOIN shamei 
		ON shamei.catalog = johovn.catalog
		AND shamei.catalog_code = johovn.catalog_code
  WHERE
    vin8 LIKE CONCAT(SUBSTRING('$vin', 1, 8), '%') # включить поиск по индексу
    AND vin8 = SUBSTRING('$vin', 1, LENGTH(vin8)) # уточнить поиск по длине 1-9 символов VIN
;";
  //echo "<pre>$query</pre>";

	$res = _mysql_query_arr($query); // выполним
	return $res;	
}



/*---------------------------
 * Вытянуть всю информацию по фрейму, подтянуть информацию 
 *	$prms - массив входящих параметров
 *---------------------------*/
function _TOY_frame_info($prms){
	if(!isset($prms['frame_code']) or !isset($prms['serial_number'])){ 		// обязательно задано и массив
		die("_TOY_frame_info : Незадано или не корректно задано фрейм !!! ");
	}

	$q = "
#EXPLAIN 
SELECT DISTINCT
  johokt.catalog, johokt.catalog_code, johokt.model_code, johokt.prod_start, johokt.prod_end, johokt.frame, 
    johokt.sysopt, johokt.compl_code, johokt.engine1, johokt.engine2, johokt.body, johokt.grade, johokt.atm_mtm, johokt.trans, 
    johokt.f1,johokt.f2,johokt.f3,johokt.f4,johokt.f5,
  frames.catalog f_catalog, frames.frame_code, serial_group, frames.serial_number, frames.opt_n, frames.ext, 
    frames.model2, frames.vdate, frames.color_trim_code, frames.siyopt_code, frames.opt,
  shamei.f1, shamei.model_name, shamei.catalog_code, shamei.models_codes, 
    shamei.prod_start sh_prod_start, shamei.prod_end sh_prod_end, shamei.rec_num, shamei.date, shamei.opt 
FROM 
  frames 
  # модификации авто
  JOIN 
    johokt 
    ON (johokt.model_code = CONCAT(frames.frame_code, frames.ext, '-', SUBSTRING_INDEX(frames.model2, '(', 1))
      # нужно както применить скобки :)
      OR johokt.model_code = CONCAT(frames.frame_code, frames.ext, '-', REPLACE(REPLACE(frames.model2, '(', ''),')','')))
  # модель
  JOIN shamei 
	  ON shamei.catalog = johokt.catalog
	  AND shamei.catalog_code = johokt.catalog_code
WHERE 
  frames.catalog IN ('OV','DM') # чтобы включить индекс
  AND frames.frame_code = '".$prms['frame_code']."'
  AND frames.serial_number = '".$prms['serial_number']."'
  AND (frames.catalog = IF(johokt.catalog = 'JP', 'DM', 'OV')) # чтобы было в рамках регионов	

  # уточняем frame по дате
  AND (frames.vdate BETWEEN johokt.prod_start AND johokt.prod_end 
	OR IFNULL(frames.vdate, '') = '') # заглушка если vdate - пустая дата
	
  # подбираем корректный johokt для найденного frames 2013.02.09 
  AND (SUBSTRING(frames.siyopt_code, 1, 4) = johokt.sysopt
	OR IFNULL(johokt.sysopt, '') = ''       # заглушка если sysopt - пустой
	OR IFNULL(frames.siyopt_code, '') = '')	# заглушка если siyopt_code - пустой
";

	$res = _mysql_query_arr($q); // выполним
	return $res;
}
/*~~~
Проверка уникальности фреймов для первой части вина. JF1ZN12A605089848 - 6 фреймов
SELECT vin8, COUNT(DISTINCT frame) 
  FROM johokt
  WHERE vin8 > '9'
  GROUP BY vin8
~~~*/


/*---------------------------
 * Применяемость запчасти к авто, делается долго
 *	$prms - массив входящих параметров
 *---------------------------*/
function _TOY_part_type($prms){
	if(!isset($prms['part_code'])){ 		// обязательно задано и массив
		die("_TOY_part_type : Незадано part_code!!! ");
	}
	
	// можно передавать список номеров, пусть будут в массиве
	$part_list = "";
	if(is_array($prms['part_code'])){
		$part_list = "'".implode("','",$prms['part_code'])."'";
	} else {
		$part_list = "'".$prms['part_code']."'";
	}
	
	$q = "	
#EXPLAIN 
SELECT DISTINCT
  hnb.catalog region,  # регион
  hnb.part_code,
  hnb.pnc, 
  hnb.quantity,
  hnb.start_date part_date_start,
  hnb.end_date part_date_end,
  shamei.catalog_code,
  shamei.model_name,
  shamei.models_codes,
  johokt.model_code,
  johokt.engine1,
  johokt.engine2,
  johokt.body,
  johokt.grade,
  johokt.atm_mtm,
  johokt.trans,
  johokt.prod_start type_date_start,
  johokt.prod_end type_date_end
  FROM hnb 
    JOIN kpt 
      ON kpt.catalog = hnb.catalog
      AND kpt.catalog_code = hnb.catalog_code
    JOIN johokt 
      ON johokt.catalog = kpt.catalog
      AND johokt.catalog_code = kpt.catalog_code
      AND johokt.compl_code = kpt.compl_code  
    JOIN shamei 
      ON shamei.catalog = johokt.catalog
      AND shamei.catalog_code = johokt.catalog_code
  WHERE 
    hnb.part_code IN ($part_list) # список номеров
    AND hnb.field_type = 1  # интересует именно системная запись
    AND kpt.ipic_code = SUBSTRING(hnb.add_desc,1,6) # маска применяемости запчасти к авто
";

	$res = _mysql_query_arr($q); // выполним
	return $res;	
}

?>