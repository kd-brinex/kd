<?php

/** Обязательно к применению */
include "../../_lib.php";    /// После подключения доступен класс A2D
include "../../adc/api.php"; /// После подключения доступен класс ADC

/// Устанавливаем объект $oA2D - объект для работы с АвтоКаталогом
$oA2D = ADC::instance();
/// Раскомментировав строку нижу, мы можем подглядеть что передается в форму. Можно добавить свои параметры
///$oA2D->e([$_GET,$_POST,$_SERVER]);

/// Получаме рефер ссылку, чтобы пользователя можно было вернуть на предыдущую страницу
$refer  = A2D::get($_SERVER,'HTTP_REFERER');

/// Получаем переменные с формы поиска
$sModel = $oA2D->rcv('model');

/// Если пользователь ввел значение, отправляем запрос на сервер
if( $sModel ){
    /// Получаем список моделей
    $aModels = $oA2D->searchModels($sModel); ///$oA2D->e($aModels);
    /// Если ошибка, выводим ее пользователю
    if( ($aErrors=A2D::property($aModels,'errors')) ) $oA2D->error($aErrors,404);
    /**
     * Так как в результате работы мы получим ссылки на дерево деталей
     * Не забываем сказать, что нам не нужен мульти-архив
    */
    $multiArray = FALSE;
}
/// Иначе сообщаем пользователю о вводе данных
else{
    $oA2D->error("Пустой поисковый запрос",404);
}

/**
 * Строим две "хлебные крошки": в корень каталога и на прошлую страницу
 * ~ можете что то свое реализовать ~
 *
 * У нас появился новый параметр, который раньше не описывался (чтобы не путать)
 * Как успели заметить - последняя крошка идет не ссылкой, а текстом, так как нет смысла ссылаться на самого себя
 * Если мы в ключе крошки передадим refer, система обработает эту крошку как ссылку
 * Да и как такого файла на старт нам не надо
 */
A2D::$aBreads = A2D::toObj([
    'types' => [
        "name" => 'Каталог',
        "breads" => []
    ],
    'refer' => [
        'url' => $refer,
        'txt' => 'Вернуться на предыдущую страницу'
    ],
]);
?>

<meta charset="utf-8">
<link href="../../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../../media/css/adc.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../../media/js/myTree.js"></script>


<?php include WWW_ROOT."helpers/breads.php"; /// Продключаем "хлебные крошки"?>

<?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>


<?php if( count($aModels)>0 ){?>
<h1>Результаты быстрого поиска марок и моделей</h1>
<table border="0" align="center" cellpadding="3" cellspacing="2">
    <tr>
        <td align="left">
            <?php $tmpmark = false;
            $_out = "\n".'<hr size=1 style=width:100%><ul id="l1" class="my_tree">'."\n";
            foreach( $aModels AS $aModelList ){
                if( $tmpmark != $aModelList->mark_id ){
                    $_out .= ($tmpmark!==false?str_repeat("</ul></li>", 1):"")."<li><b class=b>".$aModelList->mark_name."</b><ul>\n";
                    $tmpmark = $aModelList->mark_id;
                }
                $_out .= "<li class=leaf>".
                    "<a href='../tree.php?modelID=$aModelList->model_id&multiArray=$multiArray'>$aModelList->model_name</a>&nbsp;".
                    "<span style='font-size: 7pt; color: #AAAAAA;'>$aModelList->modification</span>".
                    "</li>";
            } echo $_out;?>
        </td>
    </tr>
</table>
<?php }else{?>
    <div class="warning">По вашему запросу ничего не найдено</div>
<?php }?>

<script type="text/javascript">
<!--
window.onload = function(){
    my_tree_init(document.getElementById('l1'));
};
// -->
</script>