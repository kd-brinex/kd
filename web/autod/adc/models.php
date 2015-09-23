<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс ADC


/// Устанавливаем объект $oA2D - объект для работы с АвтоКаталогом
$oA2D = ADC::instance();

/// Получаем переменные из своего окружения
$sTypeID = $oA2D->rcv('typeID');
$sMarkID = $oA2D->rcv('markID');

/// Получить список моделей для типа/группы и марки
$oModelList = $oA2D->getModelList($sMarkID,$sTypeID); ///$oA2D->e($oModelList);

/// Сперва проверим на ошибки
if( ($aErrors=A2D::property($oModelList,'errors')) ) $oA2D->error($aErrors,404);

/// В ответ вернулся объект с как минимум с 3-я свойствами:
$sTypeName = A2D::property($oModelList,'typeName'); /// Имя выбранной ранее группы
$sMarkName = A2D::property($oModelList,'markName'); /// Имя выбранной ранее марки
$oModels   = A2D::property($oModelList,'models');   /// Список доступных моделей для выбранных марки и группы

/// В текущих примерах используется второе
//$bMultiArray = TRUE; ///multiArray On  - массив с вложенными в него дочарними элементами
$bMultiArray = FALSE;  ///multiArray Off - последовательный массив без вложений - сперва корневой элемент, потом его дочерний и так далее по иерархии

/// Подготавливаем данные для конструктора "хлебных крошек" (helpers/breads.php)
A2D::$aBreads = A2D::toObj([
    'types' => [
        "name" => 'Каталог',
        "breads" => []
    ],
    'marks' => [
        "name" => $sTypeName,
        "breads" => [
            0 => $sTypeID,
            1 => $sMarkID
        ]
    ],
    'models' => [
        "name" => $sMarkName,
        "breads" => []
    ],
]);

/// Включаем интерфейс для ограничения поиска, по умолчанию активен поиск в заданных пределах ниже:
A2D::$searchWhere['tabs']  = ['detail'];            /// В какой вкладке включить
A2D::$searchWhere['name']  = "mark";                /// Поиск в пределах марки (либо модели)
A2D::$searchWhere['value'] = $sMarkID;              /// Идентификатор марки
A2D::$searchWhere['desc']  = "искать в $sMarkName"; /// Наименования чекбокса, второй "искать везде"
A2D::$searchWhere['hide']  = "_displayNone";        /// Если нужен только ограниченный поиск, то прописываем класс для скрытие чекбоксов
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/adc.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>


<?php include WWW_ROOT."helpers/breads.php"; /// Продключаем "хлебные крошки"?>
<?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>


<table border="0" align="center" cellpadding="3" cellspacing="2" class="brd">
    <tr><td align="center" colspan="2"><h1><?=A2D::lang('h1')?> &laquo;<?=$sMarkName?>&raquo;</h1></td></tr>
    <?php foreach( $oModels AS $aModel ){?>
    <tr>
        <td align="center" valign="middle">
            <a href="tree.php?modelID=<?=$aModel->model_id?>&multiArray=<?=($bMultiArray)?TRUE:FALSE?>">
                <img src="<?=$aModel->model_url?>" width="80" alt="<?=$aModel->model_name?>" border="0">
            </a>
        </td>
        <td align="left" class="rbrd">
            <a href="tree.php?modelID=<?=$aModel->model_id?>&multiArray=<?=($bMultiArray)?TRUE:FALSE?>" style="font-size: 10pt">
                <b><?=$aModel->model_name?></b><?php if($aModel->model_years) echo " ($aModel->model_years)"?>
            </a>
            <?=(A2D::property($aModel,'model_modification'))?'<br>'.A2D::lang('mod').': '.$aModel->model_modification:'';?>
            <?=(A2D::property($aModel,'model_actual'))?'<br>'.A2D::lang('act').': '.$aModel->model_actual:'';?>
        </td>
    </tr>
    <?php }?>
</table>