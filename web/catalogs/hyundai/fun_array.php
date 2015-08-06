<?php
/*-----------------------
 *	Дополнительные функции
 *	10.03.15
 ----------------------*/
/*
******************************************
	Array add
******************************************

array (+)
  - Array plus operation treats all array as assoc array.
  - When key conflict during plus, left(previous) value will be kept
  - null + array() will raise fatal error

array_merge()
  - array_merge() works different with index-array and assoc-array.
  - If both parameters are index-array, array_merge() concat index-array values.
  - If not, the index-array will to convert to values array, and then convert to assoc array.
  - Now it got two assoc array and merge them together, when key conflict, right(last) value will be kept.
  - array_merge(null, array()) returns array() and got a warning said, parameter #1 is not an array.
*/



//~~~~~~~~~~~~~~~~~~~~~~~~
// Return the Vector (values from a single column) in the input multidimensional array
// (PHP 5 >= 5.5.0) - уже есть = array_column
//
//		array 		- A multi-dimensional array (record set) from which to pull a column of values.
//		column_key	- The column of values to return.
//~~~~~~~~~~~~~~~~~~~~~~~~
function array_column_values(array $array, $column_key){
	$res = array();
	foreach($array as $row){
		$res[] = $row[$column_key];
	}
	
	return $res;
}


//~~~~~~~~~~~~~~~~~~~~~~~~
// Задает ключи значениями из указанного поля двухмерного массива - должно быть уникальным
//
//		_array 	- A multi-dimensional array (record set)
//		_key	- The column of values for new keys
//~~~~~~~~~~~~~~~~~~~~~~~~
function array_flip_2(array $_array, $_key){
	$res = array();
	foreach($_array as $row){
		$res[$row[$_key]] = $row;
	}
	return $res;
}


//~~~~~~~~~~~~~~~~~~~~~~~~
// Группирует строки по значению поля, не обязательно уникальным
//
//		_array 	- A multi-dimensional array (record set)
//		_key	- The column of values for new keys
//~~~~~~~~~~~~~~~~~~~~~~~~
function array_group(array $_array, $_key){
	$res = array();
	foreach($_array as $row){
		$res[$row[$_key]][] = $row;
	}
	return $res;
}



/******************************************
	VECTOR - одномерный массив
******************************************/

//~~~~~~~~~~~~~~~~~~~~~~~~
// обработать функцией значения полей указанных в keys
// применять только если не срабатывает array_map - нагуглил что для built-in function array_map быстрее чем foreach!?
//	
//	$function 	- функция оброботки
//	$vector 	- одномерный массив значений
//	$keys 		- список полей для обробокти, 
//				если не массив значит должна быть строка через запятую
//				если пустой - все поля - но тогда лучше array_map
//~~~~~~~~~~~~~~~~~~~~~~~~
function vector_map_keys($function, $vector, $keys=''){
	//var_dump($function, $vector, $keys);
	if(empty($vector)) return array();
	
	// если пустой - все поля
	if(empty($keys)) $keys = array_keys($vector);
	
	// если не массив значит должна быть строка через запятую
	if(!is_array($keys)) $keys = explode(',', $keys);
	
	// real_escape_string
	foreach($keys as $k){
		$vector[$k] = $function($vector[$k]);
	}
	
	return $vector;
}

?>