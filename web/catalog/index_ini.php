<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/
include_once("index_db_functions.php");
 
/*---------------------------
 * MySQL connection
 *---------------------------*/
/* Переменные для соединения с базой данных */
$hostname = "127.0.0.1:1111";
$username = "brinexdev";
$password = "QwFGHythju8";
$dbName = "toyota";

/* создать соединение */
mysql_connect($hostname,$username,$password) OR DIE("Не могу создать соединение ");
/* выбрать базу данных. Если произойдет ошибка - вывести ее */
mysql_select_db($dbName) or die(mysql_error()); 




/*---------------------------
 * Дополнительные параметры
 *---------------------------*/
//define("_IMG_IllSTR_INDEX_PATH", "/project_result/Toyota_EPC/"); // путь к рисункам
define("_IMG_IllSTR_INDEX_PATH", "http://new.kolesa-darom.ru:8080/image/toyota/"); // путь к рисункам
//define("_GET_POST_SEP", "~"); // разделитель параметров, системный

 
/*---------------------------
 * Доп функции
 *---------------------------*/ 
 
 // $escape_keys - ключи которые нужно упускать при потсороении URL, разделитель <,>
function makeEscapedURL($escape_keys = ""){
	$buf = ""; 

	$escape_k_arr = array();
	if(!empty($escape_keys)) $escape_k_arr = explode(',', $escape_keys);
	
	//print_r($_GET);
	foreach ($_GET as $key => $val){
		// пропустим ключи которые нужно пропускать :)
		if(!empty($escape_keys) && in_array($key, $escape_k_arr)) 
			continue;
		
		$buf .= $key."=".$val."&";
	}

	return $buf;
}

/****************************************************************** 
  Отработать запрос и вывести результат 
 
  $params['query'], 			// запрос для отработки
  
  // выделение цвета строки в 1
  $params['f_sel_name'] = "", // имя поля влияющее на изменение цвета
  $params['f_sel_value'] = ""
  $params['f_sel_color'] = ""; 	// цвет подсветки строки
  // выделение цвета строк!!! в 2 (новая) - позволяет задавать разные подсветки для строки
  $params['f_sel_name_2'] = array('f_sel_value' => 'f_sel_color'); 	// матрица цветов выделения строк
  
  $params['exec_module'] = "", 	// имя модуля куда будет передаваться управление
  $params['f_exec_name'] = "", 	// имена параметры для передачи их как параметры в следующий модуль, значения вычисляються динамически по резултату выполнеия sql-запросов
  $params['url_main_params'] = ""; // обязательный статический параметр для передачи в следующий модуль
  $params['img4demo'] = "", 
 ******************************************************************/
function ExecQuery($params){
	$is_show = ((isset($params['is_show_sql']) and ($params['is_show_sql'] == 1)) or !isset($params['is_show_sql'])); // показать query или нет?
	$res_query = _mysql_query($params['query'], $is_show);
	
	/* Вывести демонстрационный рисунок */
	if(!empty($params['img4demo'])) echo '<img src="img/' . $params['img4demo'] . '.png" width="800" border="1">';
	
	
	// вывод результата
	echo "<table border=\"1\">";
	echo "<tr>";


	// Оглавление EXEC
	if(!empty($params['exec_module'])) echo "<th>EXEC</th>";

	// Оглавление Pic
	if(!empty($params['img_row_path'])) echo "<th>Picters</th>";
	
	// вывод наименовнаие колонок
	$numfields = mysql_num_fields($res_query);
	for ($i=0; $i < $numfields; $i++){
		echo "<th>";
		echo mysql_field_name($res_query, $i);
		echo "</th>";
	};
	echo "</tr>";


	// вывод содержимого
	while ($row = mysql_fetch_array($res_query, MYSQL_BOTH)) {
		// ПОДСВЕТКА ----------------->>>
		$sel_color = "";
		if(!empty($params['f_sel_name'])){
			$f_sel_name = $params['f_sel_name']; // как поле влияет на подсветку

			// подсветка в.1
			if(!empty($params['f_sel_value']) and ($row[$f_sel_name] == $params['f_sel_value']))
				$sel_color = 'bgcolor = "' . $params['f_sel_color'] . '"';	
		
			// подсветка в.2
			if(!empty($params['f_sel_val_col'])){
				$f_sel_val_col = $params['f_sel_val_col'];
				//print_r($f_sel_val_col);
				if(isset($f_sel_val_col[$row[$f_sel_name]])) $sel_color = 'bgcolor = "' . $f_sel_val_col[$row[$f_sel_name]] . '"';	
			}
		}
		echo "<tr $sel_color>";
		
		
		// EXEC filed ----------------->>>
		if(!empty($params['exec_module'])){
			echo "<td>";
			$url_exec = "";
			$url_exec .= '<a href="' . $params['exec_module']. '?';
			
			// разобрать статические параметры и вставить
			//$url_exec .=  makeEscapedURL(); убрать ато путаются
			if(!empty($params['url_main_params'])) $url_exec .= $params['url_main_params'];	// !!! может и не нужно ? обязательный статический параметр
			
			// распарсить динамические параметры и вставить
			if(!empty($params['f_exec_name'])){
				$url_params = array();
				$url_params = explode('~', $params['f_exec_name']);
				foreach ($url_params as $_url_param)
					$url_exec .= $_url_param . '=' . $row[$_url_param] . "&";
			}			
			
			$url_exec .= '">Exec</a>';
			echo $url_exec;
			echo "</td>";
		}
		// EXEC filed <<<-----------------

		
		// Оглавление Pic ----------------->>>
		if(!empty($params['img_row_path'])){
			echo "<td>";
			echo '<img src="' . $params['img_row_path'] . $row[$params['img_row_file']] . $params['img_ext'] . '"';
			if(isset($params['img_show_size']) and !empty($params['img_show_size']))
				echo " width = \"" . $params['img_show_size'] . "\"";
			echo '>';
			echo "</td>";
		}
		// Оглавление Pic <<<-----------------

		
		for ($i=0; $i < $numfields; $i++){
			echo "<td>";
			echo (($row[$i] == "" or is_null($row[$i])) ? "-" : $row[$i]);
			echo "</td>";
		}
			
		echo "</tr>";
	}
	echo "</table>";

	//mysql_close();
	return $res_query;
}



// просто вывод результата array - прямоугольной таблицы (без вложений)
// [0] => ([key1] => val_11, [key2] => val_12, ...)
// [1] => ([key1] => val_21, [key2] => val_22, ...)
function array_2_html($arr_res){
	$str = "";
	if(!empty($arr_res)){
		// ВЫВОД РЕЗУЛЬТАТА
		// наименовнаие колонок
		$t_header = array_keys($arr_res[0]);
		//echo "<pre>" . print_r($t_header) . "</pre>";
		$str .= "<table border='1'>";
		$str .= "<tr>";
		$str .= "<th>" . implode("</th><th>", $t_header) . "</th>";
		$str .= "</tr>";

		// вывод содержимого
		foreach($arr_res as $row_res){
			$str .= "<tr>";
			$str .= "<td>" . implode("&nbsp;</td><td>", $row_res) . "&nbsp;</td>";	
			$str .= "</tr>";
		}
		$str .= "</table>";
	}
	
	return $str;
}


// просто вывод результата array_data  - прямоугольной таблицы (без вложений)
//	array_h - оглавление
// 	$params - параметры могут формировать результат
function array_h_2_html($arr_d, $arr_h=array(),$params=array()){
	$str = "";

	// ВЫВОД ОГЛАВЛЕНИЯ
	if(!empty($arr_h)){
		// наименовнаие колонок
		$str .= "<table border='1'  style='empty-cells:show;'>";
		$str .= "<tr>";
		$str .= "<th>" . implode("</th><th>", $arr_h) . "</th>";
		$str .= "</tr>";
	}

	// ВЫВОД ДАННЫХ
	if(!empty($arr_d)){
		foreach($arr_d as $row_res){

			// ПОДСВЕТКА ----------------->>>
			$sel_color = "";
			if(isset($params['f_sel_name']) and isset($params['f_sel_val_col'])){
				$f_sel_name = $params['f_sel_name']; 		// как поле влияет на подсветку
				$f_sel_val_col = $params['f_sel_val_col'];	// каким цветом подсвечивать значения
				//print_r($f_sel_val_col);
				if(isset($f_sel_val_col[$row_res[$f_sel_name]])) $sel_color = 'bgcolor = "' . $f_sel_val_col[$row_res[$f_sel_name]] . '"';	
			}

			$str .= "<tr $sel_color>";
			$str .= "<td><pre>" . implode("</pre></td><td><pre>", $row_res) . "</pre></td>";	
			$str .= "</tr>";
		}
		$str .= "</table>";
	}
	
	return $str;
}

?>