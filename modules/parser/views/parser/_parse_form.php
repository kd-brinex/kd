<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 13.07.15
 * Time: 16:02
 */
?>
<form method="POST">
  <input name="url" type="text" value="<?=isset($_REQUEST['url'])?$_REQUEST['url']:'http://xdan.ru/parser/parser/test.html';?>"/><input type="submit" value="Пошел">
</form>