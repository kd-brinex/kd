<?php
/************************************************************
 * EPC module
 * bunak - tecdoc@ukr.net
 *
 ************************************************************/
 
 
//include_once("Catalog_Code_Select.php");
?>
<table border='1' frame='box' rules='all' style='empty-cells:show;'>
<tr>
<td>
	<form method="GET" action="Vehicle_Input_VIN_Search_Result.php">
	<h2>Поиск по VIN: </h2> 
		<input type="text" id="vin" name="vin" value="JTJBT20X740046047" size="30" /> 	
		<input type="submit" value="Поиск " /> 
	</form>
</td>
<td>
	<?php include "templ_freame_search.php" ?>
</td>
</tr>
</table>

<hr/>
<a href="Catalog_Code_Select.php?catalog=EU">EU</a><br/>
<a href="Catalog_Code_Select.php?catalog=GR">GR</a><br/>
<a href="Catalog_Code_Select.php?catalog=JP">JP</a><br/>
<a href="Catalog_Code_Select.php?catalog=US">US</a><br/>

<hr />
<table border='1' frame='box' rules='all' style='empty-cells:show;'>
<tr>
<td>
	<div style="background:CornflowerBlue;">
	<form method="GET" action="index_VIN_PNC.php">
	<h4>VIN + Part Name Code Search: </h4> 
		VIN <input type="text" name="vin" value="JTJBT20X740046047" size="30" /> 	
		PNC <input type="text" name="pnc" value="86841" size="10" /> 	
		<input type="submit" value="Поиск " /> 
	</form>
	</div>
</td>
<td>
	<?php include "templ_part_type.php"; ?>
</td>
</tr>
</table>
