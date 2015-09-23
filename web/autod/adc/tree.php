<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс ADC


/// Устанавливаем объект $oA2D - объект для работы с АвтоКаталогом
$oA2D = ADC::instance();

/// Получаем переменные из своего окружения
$sModelID    = $oA2D->rcv('modelID');
$bMultiArray = (boolean) $oA2D->rcv('multiArray');

/// Получить список узлов и деталей для выбранной модели
$oTreelList  = $oA2D->getTreeList($sModelID,$bMultiArray); ///$oA2D->e($oTreelList);

/// Сперва проверим на ошибки
if( ($aErrors=A2D::property($oTreelList,'errors')) ) $oA2D->error($aErrors,404);

/// В ответ вернулся объект с такими свойствами:
$sTypeID     = A2D::property($oTreelList,'typeID');    /// Идентификатор группы
$sTypeName   = A2D::property($oTreelList,'typeName');  /// Имя группы
$sMarkID     = A2D::property($oTreelList,'markID');    /// Идентификатор марки
$sMarkName   = A2D::property($oTreelList,'markName');  /// Имя марки
$sModelName  = A2D::property($oTreelList,'modelName'); /// Имя модели
$aTreelList  = A2D::property($oTreelList,'details');   /// список деталей

/**
 * Подготавливаем данные для конструктора "хлебных крошек" (helpers/breads.php)
 * Так как:
 *      1. type.php и mark.php вынесены как точки входа для всех каталогов в корень,
 *      2. Остальные скрипты для каталога от АвтоДилер находятся в директории adc
 *      3. Из 5-ти шагов на последнем(map.php) крошки не нужны, на четвертом(tree.php) своя структура
 * Мы делаем:
 *      1. В файле adc/api.php выключаем рутовый каталог так - static::$catalogRoot = "";
 *      2. Добавляем в нужную единственную крошку дополнительную переменную: "root"=>"/adc"
 *      3. Расширяем конструктор крошек. Код можно подсмотреть в helpers/breads.php
*/
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
        "breads" => [
            0 => $sTypeID,
            1 => $sMarkID
        ],
        "root" => "/adc" /// Наша добавленная переменная
    ],
    'tree' => [
        "name" => $sModelName,
        "breads" => []
    ],
]);

/// Включаем интерфейс для ограничения поиска, по умолчанию активен поиск в заданных пределах ниже:
A2D::$searchWhere['tabs']  = ['detail'];                        /// В какой вкладке включить
A2D::$searchWhere['name']  = "model";                           /// Поиск в пределах модели (либо марки)
A2D::$searchWhere['value'] = $sModelID;                         /// Идентификатор модели
A2D::$searchWhere['desc']  = "искать в $sMarkName $sModelName"; /// Наименования чекбокса, второй "искать везде"
A2D::$searchWhere['hide']  = "_displayNone";                    /// Если нужен только ограниченный поиск, то прописываем класс для скрытие чекбоксов
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/adc.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>


<?php include WWW_ROOT."helpers/breads.php"; /// Продключаем "хлебные крошки"?>
<?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

<table border="0" align="center" cellpadding="3" cellspacing="2">
    <tr><td align="center" colspan="2"><h1><?=A2D::lang('h1')?> &laquo;<?=$sMarkName?> <?=$sModelName?>&raquo;</h1></td></tr>
    <tr>
        <td align="left">
        <?php /// Рекурсивная функция для построения HTML дерева деталей
        function buildTree($sModelID,$aTreelList){
            $dom = new DOMDocument();
            $ul = $dom->appendChild(new DOMElement("ul"));
            $ul->setAttribute("class", "my_tree");
            $ul->setAttribute("id", "l1");
            $aObj = new stdClass();
            $aObj->{0} = $ul;
            foreach($aTreelList as $f){
                $ul = $aObj->{$f->parent_id};
                $li = $ul->appendChild(new DOMElement("li"));
                $li->setAttribute("class", "close");
                $a = $li->appendChild(new DOMElement("a"));
                $a->setAttribute("title",$f->tree_name);
                if($f->childs != 0){
                    $b = $a->appendChild(new DOMElement("b"));
                    $b->appendChild(new DOMText($f->tree_name));
                    $a->setAttribute("href", "javascript:;");
                    $ul = $li->appendChild(new DOMElement("ul"));
                    $ul->setAttribute("class", "close");
                    $aObj->{$f->id} = $ul;
                }else{
                    $a->appendChild(new DOMText($f->tree_name));
                    $a->setAttribute("href", 'map.php?modelID='.$sModelID.'&treeID='.$f->id);
                    $a->setAttribute("id", '_'.$f->id);
                }
            }
            echo $dom->saveHTML();
        }
        if( !$bMultiArray ){ buildTree($sModelID,$aTreelList); }
        else{ $oA2D->p((array)$aTreelList); }
        ?>
        </td>
    </tr>
</table>

<!---->
<script type="text/javascript">


var my_tree_closed;
var nn6 = document.documentElement;
if(document.all){ nn6 = false; }
var ie4 = (document.all && !document.getElementById);
var ie5 = (document.all && document.getElementById);

function my_tree_click(el, f){//f: 1 - open, 2 - close, false - default
	el.className=(f===1?'':(f===2?'close':(el.className?'':'close')));
	if(el.getElementsByTagName('UL')[0])
		el.getElementsByTagName('UL')[0].className=(f===1?'':(f===2?'close':(!el.className?'':'close')));
	if((ie4 || ie5) && window.event && window.event.srcElement.type!=='checkbox'){
		window.event.cancelBubble=true;
		window.event.returnValue=false;
	}
	return false;
}
function my_tree_all(my_tree_id, f){//f: 1 - open, 2 - close
	if(f===2) my_tree_id.className='my_tree my_tree_close';
	for(i=0;i<my_tree_id.getElementsByTagName('LI').length;i++){
		var li=my_tree_id.getElementsByTagName('LI')[i];
		if(li.className!=='leaf') my_tree_click(li, f);
	};
	my_tree_id.className='my_tree';
}
function my_tree_init(my_tree_id){
	my_tree_closed=(my_tree_id.className.indexOf('close')>-1);
	for(i=0;i<my_tree_id.getElementsByTagName('LI').length;i++){
		var li=my_tree_id.getElementsByTagName('LI')[i];
		if(ie4 || ie5) li.onclick=new Function("window.event.cancelBubble=true");
		if(!li.getElementsByTagName('UL').length || li.className==='leaf') li.className='leaf';
		else if((tmp=li.getElementsByTagName('A')[0]) && tmp.parentNode===li){
			li.getElementsByTagName('A')[0].onclick=new Function("my_tree_click(this.parentNode)");
			li.getElementsByTagName('A')[0].title='<?=A2D::lang('open-close')?>';
			if(ie4 || ie5){
				li.style.cursor='hand';
				li.onclick=new Function("my_tree_click(this)");
			};
			if(my_tree_closed) li.getElementsByTagName('A')[0].onclick();
		}else{
			li.onclick=new Function("my_tree_click(this)");
			li.style.cursor='hand';
			if(my_tree_closed) li.onclick();
		}
	}
	my_tree_id.className='my_tree';
}
function f_loc(){
    if(window.location.hash){
        var el='_'+window.location.hash.substring(1);
        if(document.getElementById(el)){
            my_tree_click(document.getElementById(el).parentNode.parentNode.parentNode, 1);
            my_tree_click(document.getElementById(el).parentNode.parentNode.parentNode.parentNode.parentNode,1);
            document.getElementById(el).scrollIntoView(true);
            document.getElementById(el).focus();
        }
    }
}

window.onload = function(){
    my_tree_init(document.getElementById('l1'));
    setTimeout('f_loc()', 100);
};

//
</script>