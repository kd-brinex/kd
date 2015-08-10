<?php
/*---------------------------------------------
 *	Инициация веб-мордочки
 *	
 --------------------------------------------*/

 

// ---------------------------------
// Доступы к рисункам
$_IMG_VIEW = ""; //путь к рисункам веб-интерфейса
$_IMG_DATA = "/project_result/Hyundai_MicroCat/Imgs/"; // путь к рисункам, можно менять на абсолютный типа C:\Img\


// ---------------------------------
// ПОДКЛЮЧЕНИЕ
$mysql_сonnection = array(
	'host' => '127.0.0.1',
	'port' => '1111',
	'username' => 'root',
//	'dsn' => 'mysql:host=127.0.0.1;dbname=hyundai;port=1111',
	'passwd' => '',
	'dbname' => 'hyundai',
	'charset' => 'utf8',
);



// ---------------------------------
// инициализируем класс для работы с api Microcat
include_once("fun_array.php");
include_once("class_MySQLi.php");
include_once("api_mc.php");
$MC_API = new MC_API($mysql_сonnection);



// ---------------------------------
// инициализируем класс для работы с шаблонами веб-морд
include_once("tmpl.php");
//$TMPL = new TMPL(array('img_data'=>$_IMG_DATA));
$TMPL = new TMPL(array('img_data'=>"http://3.kolesa-darom.ru:8080/image/hyundai/Imgs/"));
//define("_IMG_IllSTR_INDEX_PATH", "http://new.kolesa-darom.ru:8080/image/toyota/"); // путь к рисункам



/*---------------------------
 * Доп функции
 *---------------------------*/ 

// просто вывод результата array_data  - прямоугольной таблицы (без вложений)
//	array_h - оглавление
// 	$params - параметры могут формировать результат
//		f_sel_name - какие поля будут влиять на подсветку записи
//		f_sel_val_col - массив цветов подсветки в зависимости от значений
function array_h_2_html($arr_d, $arr_h=array(),$params=array()){
	$str = "";
	$str .= "<table border='1' frame='box' rules='all' style='empty-cells:show;'>"; //frame='box' rules='all' для IE

	// ВЫВОД ОГЛАВЛЕНИЯ
	if(!empty($arr_h)){
		// наименовнаие колонок
		$str .= "<tr>";
		$str .= "<th>" . implode("</th><th>", $arr_h) . "</th>";
		$str .= "</tr>";
	}

	// ВЫВОД ДАННЫХ
	if(!empty($arr_d)){
		foreach($arr_d as $row_res){
			// преобразовать массив
			foreach($row_res as $key => $field){
				if(is_array($field))
					$row_res[$key] = print_r($field, true);
			}
		
			// ПОДСВЕТКА ----------------->>>
			$sel_color = "";
			if(isset($params['f_sel_name']) and isset($params['f_sel_val_col'])){
				$f_sel_name = $params['f_sel_name']; 		// как поле влияет на подсветку
				$f_sel_val_col = $params['f_sel_val_col'];	// каким цветом подсвечивать значения
				//print_r($f_sel_val_col);
				if(isset($f_sel_val_col[$row_res[$f_sel_name]])) $sel_color = 'bgcolor = "' . $f_sel_val_col[$row_res[$f_sel_name]] . '"';	
			}

			$str .= "<tr $sel_color >";
			$str .= "<td><pre>" . implode("</pre></td><td><pre>", $row_res) . "</pre></td>";	
			$str .= "</tr>";
		}
		$str .= "</table>";
	}
	
	return $str;
}
?>