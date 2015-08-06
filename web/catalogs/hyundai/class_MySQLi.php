<?php
/*-----------------------
 *	Класс - MySQL Improved
 *	05.10.2014
 ----------------------*/

// оф. источник гласит http://www.php.net/manual/ru/function.mysql-connect.php
// mysql - расширение устарело, начиная с версии PHP 5.5.0, и будет удалено в будущем
// поэтому потихоньку пеерползаем на mysqli

class MySQLi_conn {

	private $mysqli;	// mysqli
	private $result;	// mysqli_query() вернет объект mysqli_result
	
	
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Создание объекта, подключение к БД MySQL
	//
	//	$prms
	//		mysqli 		- уже созданное раньше connection to the MySQL server
	//		host		- host name or an IP address
	//		username	- The MySQL user name
	//		passwd		- password
	//		dbname		- database to be used when performing queries
	//		charset		- The charset to be set as default
	//~~~~~~~~~~~~~~~~~~~~~~~~
	public function __construct($prms) {
		// ЕСЛИ хотим юзать готовое соединение просто инициализируемся
		if(isset($prms['mysqli']))
			$this->mysqli = $prms['mysqli'];
		
		// ИНАЧЕ создаем новое соеденение mysqli
		elseif(isset($prms['host']) and isset($prms['username']) and isset($prms['passwd']) and isset($prms['dbname'])){
			$this->mysqli = new mysqli($prms['host'], $prms['username'], $prms['passwd'], $prms['dbname'], $prms['port']);
			
			/*
			 * This is the "official" OO way to do it,
			 */
			if ($this->mysqli->connect_error) {
				die(__METHOD__.': Connect Error (' . $this->mysqli->connect_errno . ') '	. $this->mysqli->connect_error);
			}
		}
		
		// ну а если совсем ИНАЧЕ то глюк - ошибка
		else
			die(__METHOD__.': Bad parameters!');

			
		//Принудительная установка запросов и ответов БД в кодировку
		if(!empty($prms['charset']) and (!$this->mysqli->set_charset($prms['charset'])))
			die(__METHOD__.': Set Charset Error: '.$this->mysqli->error);
    }

	
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Отрабатывается самостоятельно при завершении отработки скриптов. PHP - молодца :)
	//~~~~~~~~~~~~~~~~~~~~~~~~
	public function __destruct(){
		/* close result */
		if(is_object($this->result))
			$this->result->close();		

		/* close connection */
		if(!empty($this->mysqli))
			$this->mysqli->close();		
	}

	
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Выполнить запрос к базе данных.
	//		Если ошибка - показать
	//
	//	$prms	
	//		query 	- SQL запрос
	//		is_echo	- показать SQL запрос ?
	//~~~~~~~~~~~~~~~~~~~~~~~~
	public function query($prms){
		/* Показать тело запроса */		
		if(!empty($prms['is_echo'])) 
			echo "<pre style='color:red;'>".$prms['query']."</pre>";
	
		$this->result = $this->mysqli->query($prms['query']);
		if(!$this->result){
			echo "<pre style='color:red;'>".$prms['query']."</pre>";
			die(__METHOD__.": Exec query Error: ".$this->mysqli->error);
		}
	}


	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Выполнить запрос. Если произойдет ошибка - вывести ее.
	//	Вернуть ассоциативный массив
	//	Версия: 25.01.2014
	//
	//	$prms	
	//		query 	- SQL запрос
	//		is_echo	- показать SQL запрос ?
	//~~~~~~~~~~~~~~~~~~~~~~~~
	public function query_fetch_all($prms){
		$this->query($prms);
		$res = $this->result->fetch_all(MYSQLI_ASSOC);
		$this->result->close();	// close result
		$this->result = NULL;	// set result to empty for Warning: mysqli_result::close(): Couldn't fetch mysqli_result in
		
		return $res;
	}
	
	
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Запрос - подготовить и передать на выполнение.
	//
	//	$q_s_f 	- SELECT - FROM - sql
	//	$q_w 	- WHERE - array
	//	$q_o 	- ORDER - sql - список полей через запятую
	//	$q_l 	- LIMIT - number
	//~~~~~~~~~~~~~~~~~~~~~~~~
	function query_prepare_exec($q_s_f, $q_w=array(), $q_o="", $q_l=0){
		if(empty($q_s_f)) die(__METHOD__.": Запрос пустой!!");
		
		//explain
		$q = "#EXPLAIN".$q_s_f; // проверка оптимизации
		//where
		if(!empty($q_w)) 
			$q .= "WHERE \n  ".implode("\n  AND ", $q_w)."\n";
		//order
		if(!empty($q_o))
			$q .= "ORDER BY \n  $q_o"; 
		//limit
		if(!empty($q_l))
			$q .= "\nLIMIT $q_l"; 
		//;
		$q .= ";";
		
		$res = $this->query_fetch_all(array('query'=>$q)); 
		return $res; //результат
	}


	//~~~~~~~~~~~~~~~~~~~~~~~~
	// обработать функцией поля
	//	
	//	$function 	- функция оброботки
	//	$arr 	- массив значений
	//	$keys 	- список полей для обробокти, 
	//				если не массив значит должна быть строка через запятую
	//				если пустой - все поля
	//~~~~~~~~~~~~~~~~~~~~~~~~
	static function function_fields($function, $arr, $keys){
		if(empty($arr)) return $arr;
		
		// если не массив значит должна быть строка через запятую
		if(!is_array($keys)) $keys = explode(',', $keys);
		
		// если пустой - все поля
		if(empty($keys)) $keys = array_keys($arr);
		
		// function
		foreach($keys as $k){
			$arr[$k] = $function($arr[$k]);
		}
		
		return $arr;
	}


	//~~~~~~~~~~~~~~~~~~~~~~~~
	// обработать функцией поля
	//	04.10.2014
  //
	//	$function 	- функция оброботки
	//	$arr 	- массив значений
	//	$keys 	- список полей для обробокти, 
	//				если не массив значит должна быть строка через запятую
	//				если * - все поля
	//~~~~~~~~~~~~~~~~~~~~~~~~
	static function function_fields2($function, $arr, $keys){
		if(empty($arr) or ($keys == '')) return $arr;
		
		// обработка если не массив
		if(!is_array($keys)){      
    
      if($keys == '*') // если * - все поля
        $keys = array_keys($arr);
      else             // значит должна быть строка через запятую
        $keys = explode(',', $keys);

    }
		
		// function
		foreach($keys as $k){
			$arr[$k] = $function($arr[$k]);
		}
		
		return $arr;
	}


	//~~~~~~~~~~~~~~~~~~~~~~~~
	// real_escape_string
	//	
	//	$arr 	- массив значений
	//	$keys 	- список полей для обробокти, 
	//				если не массив значит должна быть строка через запятую
	//				если пустой - все поля
	//~~~~~~~~~~~~~~~~~~~~~~~~
	public function escape_fields($arr, $keys){
		if(empty($arr)) return $arr;
		
		// если не массив значит должна быть строка через запятую
		if(!is_array($keys)) $keys = explode(',', $keys);
		
		// если пустой - все поля
		if(empty($keys)) $keys = array_keys($arr);
		
		// real_escape_string
		foreach($keys as $k){
			$arr[$k] = $this->mysqli->real_escape_string($arr[$k]);
		}
		
		return $arr;
	}


	//~~~~~~~~~~~~~~~~~~~~~~~~
	// real_escape_string
	//	
	//	$arr 	- массив значений
	//	$keys 	- список полей для обробокти, 
	//				если не массив значит должна быть строка через запятую
	//				если * - все поля
	//~~~~~~~~~~~~~~~~~~~~~~~~
	public function escape_fields2($arr, $keys){
		if(empty($arr) or ($keys == '')) return $arr;
		
		// обработка если не массив
		if(!is_array($keys)){      
    
      if($keys == '*') // если * - все поля
        $keys = array_keys($arr);
      else             // значит должна быть строка через запятую
        $keys = explode(',', $keys);

    }
		
		// real_escape_string
		foreach($keys as $k){
			$arr[$k] = $this->mysqli->real_escape_string($arr[$k]);
		}
		
		return $arr;
	}

	
	
	//~~~~~~~~~~~~~~~~~~~~~~~~
	// Генерирование группового INSERT.
	//		если гдето затупил то может вернуть пустую строку
	//
	//	$prms 
	//		table	- имя таблицы
	//		columns_case		- нужно ли менять регистр названий столбцов таблицы: strtolower, strtoupper
	//		values 	- array строк, каждая строка array значений. 
	//			Если array значений - ассоциативный - то перчислять название полей, если нет то тупо по порядку.
	//		flds_trim 			- список полей для trim, если задана но пустая - значит все поля
	//		flds_rtrim 			- список полей для rtrim, если задана но пустая - значит все поля
	//		flds_escape_string 	- список полей для mysqli_real_escape_string, если задана но пустая - значит все поля
	//		is_echo 			- показать запрос
	//		is_query 			- если задано - выполняет запрос
	//~~~~~~~~~~~~~~~~~~~~~~~~
	public function prepare_insert_array($prms){
		if(empty($prms['values']) or empty($prms['table'])) 
			die(__METHOD__.": Не заданы обязательные параметры!!");
		
		/* определим название полей	*/
		$columns = "";
		$arr_0 = reset($prms['values']);		// returns the value of the first array element
		//if(array_values($arr_0) !== $arr_0){ // array - ассоциативный?	
		if(!isset($arr_0[0])){	            // 19.10.14 - array - ассоциативный?	
			$arr_k = array_keys($arr_0);
			// регистр названий столбцов таблицы
			if(!empty($prms['columns_case'])){
				$arr_k = array_map($prms['columns_case'], $arr_k);
			}
			// формирвание строки для запроса
			$columns = "(`" . implode("`,`", $arr_k) . "`)";	
		}
		
		/* генерируем VALUES */
		$sql_query = ""; 
		$sep = "";
		foreach($prms['values'] as $row){
			if(empty($row)) continue;
			
			// RTRIM
			if(isset($prms['flds_rtrim']) and ($prms['flds_rtrim'] != ''))	
        $row = self::function_fields2('rtrim', $row, $prms['flds_rtrim']);	
			// REAL_ESCAPE_STRING
			if(isset($prms['flds_escape_string']) and ($prms['flds_escape_string'] != ''))	
        $row = $this->escape_fields2($row, $prms['flds_escape_string']);
			
			$sql_query .= $sep . "('" . implode("','", $row) . "')";
			$sep = ",\n";
		}
		
		/* результирующий запрос */
		if(!empty($sql_query))
			$sql_query = "INSERT INTO " . $prms['table'] . " $columns VALUES \n" . $sql_query . ";";
		
		/* показать запрос */
		if(!empty($prms['is_echo']))
			echo "<pre style='color:red;'>$sql_query</pre>";
		
		/* выполнить запрос */
		if(!empty($prms['is_query']))
			$this->query(array('query'=>$sql_query));
		
		return $sql_query;
	}	
}
?>