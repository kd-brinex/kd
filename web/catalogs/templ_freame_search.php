<?php
/************************************************************
 * template module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/

//-----------------------
// параметры $_TEMPL - нужно свормировать заранее, до подключения шаблона
//
$frame = (!empty($_TEMPL['frame'])) ? $_TEMPL['frame'] : "ACA20" ;
$fr_number = (!empty($_TEMPL['fr_number'])) ? $_TEMPL['fr_number'] : "0002024" ;
?>
<div style="background:Pink;">
<form method="GET" action="view_frame_result.php">
<h4>Frame No Search: </h4> 
	Frame <input type="text" name="frame" value="<?php echo $frame ?>" size="30" /> 	
	Number <input type="text" name="fr_number" value="<?php echo $fr_number ?>" size="10" /> 	
	<input type="submit" value="Поиск " /> 
</form>
</div>