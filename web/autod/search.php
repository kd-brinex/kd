<?php include "_lib.php";

$oAD = A2D::instance();

$refer = A2D::get($_SERVER,'HTTP_REFERER');
///$oAD->e([$_GET,$_POST,$_SERVER]);
?>

<meta charset="utf-8">
<link href="media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="media/css/style.css" media="all" rel="stylesheet" type="text/css">


<div class="warning">
    Пример с поиском по всему каталогу находится в разработке. <br/>
    Данный функционал Ваши программисты могут реализовать самостоятельно. <br/>
    <a href="<?=$refer?>"> >>> Вернуться на предидущую страницу <<< </a>
</div>