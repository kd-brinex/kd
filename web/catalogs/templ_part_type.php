<?php
/************************************************************
 * template module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/

//-----------------------
// параметры $_TEMPL - нужно свормировать заранее, до подключения шаблона
//
$part_num = (!empty($_TEMPL['part_num'])) ? $_TEMPL['part_num'] : "SU00100811" ;
?>
<div style="background:Pink;">
<form method="GET" action="view_part_type_result.php">
<h4>Part to Type Search: </h4> 
	Part Number <input type="text" name="part_num" value="<?php echo $part_num ?>" size="15" /> 	
	<input type="submit" value="Поиск " /> 
</form>
</div>