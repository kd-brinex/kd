<div class="zoomControl bgGrey90 absolute bottom w100p" onmousedown="return false" onselectstart="return false">
    <div class="labelsCtrl ctrlItems fl">
        <input type="checkbox" checked onclick="showlabels(this.checked);" value="1" id="cl1" title=""/>&nbsp;<span class="cBlue">Метки</span>
    </div>
    <div class="zoomCtrl ctrlItems fl">
        <span class="cBlue mr5">Масштаб</span>
        <span class="bttn pointer pd10 titleTooltip" onclick="izoom(-1);" title="Уменьшить масштаб изображения">&mdash;</span>
        <span class="bttn pointer pd10 titleTooltip" onclick="izoom(0);" title="Вернуть изображения в исходное состояние">100%</span>
        <span class="bttn pointer pd10 titleTooltip" onclick="izoom(1);" title="Увеличить масштаб изображения">+</span>
        или&ensp;
        <span class="pointer titleTooltip" title="Наведите на изображение, нажмите &laquo;Z&raquo; и регулируйте масштаб колесом мышки">
            <b>Z</b>+<img style="margin-left:-4px;" src="/media/images/zoomer/mouseScroll.png" alt=""/>
        </span>
    </div>
    <div class="dragCtrl ctrlItems fl pointer titleTooltip" title="Наведите на изображение, зажмите левую кнопку мыши и перемещайте изображение внутри окна">
        <span class="cBlue">Прокрутка</span> картинки
        <img src="/media/images/zoomer/mouseLBttn.png" alt=""/>
        <img src="/media/images/zoomer/moveArrows.png" alt=""/>
    </div>
    <div class="activeCtrl ctrlItems fl pointer titleTooltip" title="Двойным кликом левой кнопкой мыши по детали на изображении можно подсветить ее номер и наименование в таблице">
        <span class="cBlue">Выделение</span> детали в таблице
        <img src="/media/images/zoomer/mouseLBttn.png" alt=""/>
        X 2
    </div>
    <div class="clear"></div>
</div>
<script>
    /// Show/Hide Coord
    function showlabels(v){
        var ml = document.getElementById('<?=$rootZoom?>'), $divs = ml.getElementsByTagName("div");
        if( $divs.length>0 ) for( var i=0; i<$divs.length; i++ ) if( $divs[i].className!=='active' && $divs[i].className!=='' ) $divs[i].className = ($divs[i].className = v)?'':'invis';
    }
    /// Zoomer
    var count = count||100;
    function izoom(w){
        if(w>0) count += count >= 300 ? 0 : 20;
        else if(w<0) count -= count <= 20 ? 0 : 20;
        else count = 100;
        var pr = count/100;
        document.getElementById('<?=$rootZoom?>').setAttribute(
            "style",
            "position:absolute;top:0px;left:0px;"+
            "-moz-transform: scale("+pr+","+pr+");" +
            "-moz-transform-origin: top left;"+
            "-webkit-transform: scale("+pr+","+pr+");" +
            "-webkit-transform-origin: top left;"+
            "-ms-transform: scale("+pr+","+pr+");" +
            "-ms-transform-origin: top left;"+
            "-o-transform: scale("+pr+","+pr+");" +
            "-o-transform-origin: top left;"+
            "transform: scale("+pr+","+pr+");"+
            "transform-origin: top left;"+
            "");
        /// Если нужно отдать значение в интерфейс обратно
        if(parent.document.getElementById('map_info')) parent.document.getElementById('map_info').value = count + "%";
        return false;
    }
    jQueryA2D(document).ready(function(){

        var $zoom = jQueryA2D('#<?=$rootZoom?>'),$frame = $zoom.parent(),startZoom=false;

        $frame.bind('wheel mousewheel',function(e){
            if( startZoom ){
                e.preventDefault();
                var delta;
                if( e.originalEvent.wheelDelta !== undefined )
                    delta = e.originalEvent.wheelDelta;
                else
                    delta = e.originalEvent.deltaY * -1;
                var z = (delta>0)?1:-1;
                izoom(z);
            }
        });

        var focus;
        $frame.mouseover(function(){ focus = true; }).mouseout(function(){ focus = false; });

        jQueryA2D(document)
            .keydown(function(event){ if( event.keyCode===90 && focus ) startZoom = true;})
            .keyup(function(event){ if( event.keyCode===90 ) startZoom = false;});

        titleTooltip('.titleTooltip');

    });
</script>