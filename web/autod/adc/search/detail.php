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

/**
 * Получаем переменные с формы поиска
 *
 * detail - Обязательный параметр
 *
 * mark, model - По принципу ИЛИ, присутствовать одновременно не должны
 *
*/
$detail = $oA2D->rcv('detail'); /// Номер детали
$mark   = $oA2D->rcv('mark');   /// Марка, в рамках которой искать
$model  = $oA2D->rcv('model');  /// Модель, в рамках которой искать

/**
 * Если есть модель или марка, передаем в условия поиска вторым параметром
 * Это поможет усечь выборку на сервере, чтобы в ответе не вернулось:
 *      Найдено более 100 совпадений
 */

/// Если введена деталь, то делаем запрос
if( $detail ){

    $whereName = $whereValue = NULL;
    if( $mark ){ /// Строить поиск в пределах марки
        $whereName  = "mark"; /// На сервере принимает строго только mark или model
        $whereValue = $mark;  /// Идентификатор марки
    }
    if( $model ){ /// Строить посик в пределах модели
        $whereName  = "model"; /// На сервере принимает строго только mark или model
        $whereValue = $model;  /// Идентификатор модели
    }

    /**
     * Детальный поиск
     * При включенной опции ищет только точное совпадение
    */
    /// Отключаем детальный поиск по умолчанию
    $detailed = FALSE;
    /// Первый и последний символ запроса
    $fs = substr($detail, 0, 1); /// Первый символ
    $ls = substr($detail, -1);   /// Последний символ
    /// Любой парный символ из перечисленных по краям включает детальный поиск
    if( $fs==$ls && in_array($fs,['"',"'",'#','@','!',':',';','*','^','%','$','~']) ) $detailed = TRUE;
    /// Или текс заключенный в скобки
    if( $fs=="(" && $ls==")" ) $detailed = TRUE;
    /// Удаляем символя с запроса, более они не нужны
    if( $detailed ) $detail = trim( $detail, "\\{$fs}\\{$ls}" );
    ///$this->e([$fs,$ls,$detail ]);

    /// Получаем информацию по модели
    $aDetails = $oA2D->searchNumber($detail,$whereName,$whereValue,$detailed); ///$oA2D->e([$detail,$whereName,$whereValue,$aDetails]);
    /// При ошибки останавливаемся
    if( ($aErrors=A2D::property($aDetails,'errors')) )$oA2D->error($aErrors,404);
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


<?php if( !empty($aDetails) && count($aDetails)>0 ){?>
<h1>Результаты поиска по каталогу запчастей</h1>
<table border="0" align="center" cellpadding="3" cellspacing="2">
<tr>
    <td align="left">
        <?php
        /**
         * Мы выбрали такой подход для отдачи контента
         * Как всегда можно реализовать что душе угодно
        */
        if( count($aDetails)>100 ){
            $_out = "<br>Найдено: <b style='color:red'>больше 100 деталей, введите более точные критерии поиска</b>";
        }
        else{
            $_out = "<br>Найдено: <b style='color:red'>".count($aDetails)."</b>";
        }
        $_out .= "\n".'<hr size=1 style=width:100%><ul id="l1" class="my_tree">'."\n";
        $old_mark_name = $old_auto = $old_t1 = $old_t2 = $old_t3 = false;
        foreach( $aDetails as $d ){
            if ($d->mark_id!=$old_mark_name){
                $_out         .= ($old_mark_name!==false?str_repeat('</ul></li>', 5):'').'<li>'.$d->mark_name.'<ul>'."\n";
                $old_mark_name = $d->mark_id;
                $old_auto      = $old_t1=$old_t2=$old_t3=false;
            }
            if ($d->model_id!=$old_auto){
                $_out    .= ($old_auto!==false?str_repeat('</ul></li>', 4):'').'<li>'.$d->model_name.' ('.$d->modification.')<ul>'."\n";
                $old_auto = $d->model_id;
                $old_t1   = $old_t2=$old_t3=false;
            }
            if ($d->tree_id1!=$old_t1){
                $_out  .= ($old_t1!==false?str_repeat('</ul></li>', 3):'').'<li>'.$d->t1.'<ul>'."\n";
                $old_t1 = $d->tree_id1;
                $old_t2 = $old_t3=false;
            }
            if ($d->tree_id2!=$old_t2){
                $_out  .= ($old_t2!==false?str_repeat('</ul></li>', 2):'').'<li>'.$d->t2.'<ul>'."\n";
                $old_t2 = $d->tree_id2;
                $old_t3 = false;
            }
            if ($d->tree_id3!=$old_t3){
                $_out  .= ($old_t3!==false?str_repeat('</ul></li>', 1):'').'<li>'.$d->t3.'<ul>'."\n";
                $old_t3 = $d->tree_id3;
            }
            $_out .= '<li class=leaf><a href="../map.php?modelID='.$d->model_id.'&treeID='.$d->tree_id3.'#l'.$d->detail_id.'">'.$d->detail_name.'</a> ('.$d->detail_no.')</li>'."\n";
        }
        $_out.=str_repeat('</ul></li>', 5).'</ul>';
        echo $_out;
        ?>
    </td>
</tr>
</table>
<?php }else{?>
    <div class="warning">По вашему запросу ничего не найдено</div>
<?php }