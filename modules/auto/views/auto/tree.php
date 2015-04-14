<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.04.15
 * Time: 14:06
 */
//var_dump($tree);
//$wroot = 'auto/';

//$cssView = file_get_contents($wroot . 'media/css/style.css');
//$cssView .= file_get_contents($wroot . 'media/css/adc.css');
//$this->registerCss($cssView);
Yii::$app->view->registerCssFile('/assets/auto/css/style.css');
Yii::$app->view->registerCssFile('/assets/auto/css/adc.css');
/// В ответ вернулся объект с такими свойствами:
$sTypeID     = $oA2D->property($oTreelList,'typeID');    /// Идентификатор группы
$sTypeName   = $oA2D->property($oTreelList,'typeName');  /// Имя группы
$sMarkID     = $oA2D->property($oTreelList,'markID');    /// Идентификатор марки
$sMarkName   = $oA2D->property($oTreelList,'markName');  /// Имя марки
$sModelName  = $oA2D->property($oTreelList,'modelName'); /// Имя модели
$aTreelList  = $oA2D->property($oTreelList,'details');   /// список деталей

$this->params['breadcrumbs'][] = ['label'=>'Каталог','url'=>['/auto']];
$this->params['breadcrumbs'][] = ['label'=>$sTypeName,'url'=>['/auto/marks/'.$sTypeID]];
$this->params['breadcrumbs'][] = ['label'=>$sMarkName,'url'=>['/auto/models/'.$sTypeID.'_'.$sMarkID]];
$this->params['breadcrumbs'][] = ['label'=>$sModelName];
?>
<div class="col-xs-12">

    <div><h1><?=$oA2D->lang('h1')?> &laquo;<?=$sMarkName?> <?=$sModelName?>&raquo;</h1></div>

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
                $li->setAttribute("class", "tree-close");
                $a = $li->appendChild(new DOMElement("a"));
                $a->setAttribute("title",$f->tree_name);
                if($f->childs != 0){
                    $b = $a->appendChild(new DOMElement("b"));
                    $b->appendChild(new DOMText($f->tree_name));
                    $a->setAttribute("href", "javascript:;");
                    $ul = $li->appendChild(new DOMElement("ul"));
                    $ul->setAttribute("class", "tree-close");
                    $aObj->{$f->id} = $ul;
                }else{
                    $a->appendChild(new DOMText($f->tree_name));
                    $a->setAttribute("href", '/auto/auto/map?modelID='.$sModelID.'&treeID='.$f->id);//modelID='.$sModelID.'&treeID='.$f->id
                    $a->setAttribute("id", '_'.$f->id);
                }
            }
            echo $dom->saveHTML();
        }

        if( !$bMultiArray ){ buildTree($sModelID,$aTreelList); }
        else{ $oA2D->p((array)$aTreelList); }
        ?>


</div>
<!---->
<script type="text/javascript">


    var my_tree_closed;
    var nn6 = document.documentElement;
    if(document.all){ nn6 = false; }
    var ie4 = (document.all && !document.getElementById);
    var ie5 = (document.all && document.getElementById);

    function my_tree_click(el, f){//f: 1 - open, 2 - close, false - default
        el.className=(f===1?'':(f===2?'tree-close':(el.className?'':'tree-close')));
        if(el.getElementsByTagName('UL')[0])
            el.getElementsByTagName('UL')[0].className=(f===1?'':(f===2?'tree-close':(!el.className?'':'tree-close')));
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
                li.getElementsByTagName('A')[0].title='<?=$oA2D->lang('open-close')?>';
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