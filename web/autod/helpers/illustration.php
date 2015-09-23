<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script>
    var jQueryA2D = jQuery;
</script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="/media/js/ZeroClipboard.js"></script>
<script src="/media/js/base.js"></script>

<script>
    /**
     * Copy to Clipboard
     */
    function c2c(obj){
        var bttn = jQueryA2D(obj).children('img')[0];
        /** create client */
        var clip = new ZeroClipboard.Client();
        clip.addEventListener('mousedown',function(){
            var t = jQueryA2D(obj).find('span.c2c').text();
            clip.setTxt(t);
        });
        clip.addEventListener('complete',function(client,text) {
            alert('Скопировано в буфер: '+text);
        });
        if( bttn ) clip.glue(bttn);
    }
    jQueryA2D.fn.setC2C = function( attr, val ){
        var c2cValues = jQueryA2D('.'+val);
        for( var i=0; i<c2cValues.length; i++ ){
            var obj = c2cValues[i];
            c2c( obj );
        }
        return this;
    };
    function readyZeroClipboard(){
        ZeroClipboard.setTitle('Копировать');
        ZeroClipboard.setMoviePath('/media/js/ZeroClipboard.swf');
        setTimeout( function( ){
            jQueryA2D(document).setC2C('class','c2cValue');
        }, 100 );
    }
</script>


<script>
    /**
     * Drag-N-Drop Image with Details Labels
     */
    var count = count||100; /// Zoom Percent, Declared in ZoomControl
    function dragAndDrop(select){
        var $frame      = document.getElementById('imageArea'),
            frameHeight = $frame.offsetHeight,
            frameWidth  = $frame.offsetWidth
            ;
        var cnt = count||100; /// Zoom Percent, Declared in ZoomControl
        //*/
        jQueryA2D(select).draggable({
            cursor: "crosshair"
            ,opacity: 0.35
            ///,create: function( event, ui ){}
            ///,start: function( event, ui ){}
            ,drag: function( event, ui ){

                var p = jQueryA2D(this).position();

                var imageLayoutW = this.offsetWidth;
                var imageLayoutH = this.offsetHeight;

                if( (frameWidth-p.left<100 && frameHeight-p.top<100) || (imageLayoutW+p.left<100 && imageLayoutH+p.top<100) ){
                    this.style.left = 0;
                    this.style.top = 0;
                    return false;
                }
                else if( frameWidth-p.left<50 || imageLayoutW+p.left<50 ){
                    this.style.left = 0;
                    return false;
                }
                else if( frameHeight-p.top<50 || imageLayoutH+p.top<50  ){
                    this.style.top = 0;
                    return false;
                }
            }
            ,stop: function( event, ui ){
                /// Тут можно выводить отладочную информацию
                ///console.log("zoom: "+cnt+"%");
            }
        });
    }

</script>


<script>
    /**
     * Table with Details List
    */
    var magic = 180; //У нас fixed меню в 130px
    function unsetActive(){
        jQueryA2D('.mapLabel.active').each(function(i,e){
            jQueryA2D(e).removeClass('active');
        });
        jQueryA2D('tr.active').each(function(i,e){
            jQueryA2D(e).removeClass('active');
        });
    }
    function labelClick(label,scroll){

        var id = jQueryA2D(label).attr('id').substr(1),
            tr = jQueryA2D('tr[data-position="'+id+'"]')
        ; ///console.log( id );

        unsetActive();

        jQueryA2D(label).addClass('active');
        tr.addClass('active');

        if( scroll ){
            jQueryA2D('html, body').animate({
                scrollTop: (tr.offset().top - magic)
            }, 2000);
        }

    }
    function trClick(tr,scroll){

        var id = jQueryA2D(tr).data('position'),
            lb = jQueryA2D('.l'+id)
        ; ///console.log( id );

        unsetActive();

        jQueryA2D(tr).addClass('active');
        lb.addClass('active');

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /// Open/Close Detail Info Table >>>
        var tableInfo = jQueryA2D(tr).siblings('#response.response');
        tableInfo.toggle(500);
        /// Open/Close Detail Info Table <<<
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /// Выравнивание лейблы по центру >>>
        var frame       = jQueryA2D("#imageArea"),
            frameWidth  = frame.width(),
            frameHeight = frame.height()
            ;
        var imgMap       = jQueryA2D("#imageLayout"),
            imgMapWidth  = imgMap.width(),
            imgMapHeight = imgMap.height()
            ;
        var lableTop  = lb.position().top;
        var lableLeft = lb.position().left;


        var md = 50;
        /** Сперва центруем */
        /** Vertical */
        var centerTop = frameHeight/2-lableTop;
        if( (lableTop + imgMap.position().top) !== centerTop ){
            imgMap.css('top',centerTop);
        }
        /** Horizontal */
        var centerLeft = frameWidth/2-lableLeft;
        if( (lableLeft + imgMap.position().left) !== centerLeft ){
            imgMap.css('left',centerLeft);
        }
        /** Проверяем края */
        /** Сперва проверяем низ и правый фланг */
        if( (imgMapWidth+imgMap.position().left)<frameWidth ){
            imgMap.css('left',(frameWidth-imgMapWidth));
        }
        if( (imgMapHeight+imgMap.position().top)<frameHeight ){
            imgMap.css('top',(frameHeight-imgMapHeight));
        }
        /** Потом проверяем верх и левый фланг */
        /// Левый край ушел в право
        if( imgMap.position().left>0 ){
            imgMap.css('left',0);
        }
        /// Если верхний край ушел вниз
        if( imgMap.position().top>0 ){
            imgMap.css('top',0);
        }

        if( scroll ){
            jQueryA2D('html, body').animate({
                scrollTop: (frame.offset().top - magic)
            }, 2000);
        }
        /// Выравнивание лейблы по центру <<<
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
</script>


<script>
    /** Section Drawing Canvas */
    function drawCanvas(){ //console.log('>>> Img Load Start <<<');
        var urlImg  = '<?=$imgUrl?>';
        var img    = document.createElement("img"); //console.log(urlImg);
        img.onload = function(){
            function canvasImg(jsArrCanvas){
                var canvas = document.getElementById("canvas");
                var ctx = canvas.getContext('2d');
                try{
                    for( var i = 0; i<jsArrCanvas.length; i++ ){
                        var sx = jsArrCanvas[i][0]*1;
                        var sy = jsArrCanvas[i][1]*1;
                        var dx = jsArrCanvas[i][2]*1;
                        var dy = jsArrCanvas[i][3]*1;
                        var sWidth  = dWidth  = jsArrCanvas[i][4]*1;
                        var sHeight = dHeight = jsArrCanvas[i][5]*1;
                        ctx.drawImage( img, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight );
                    }
                }catch( ex ){
                    console.log("Exeption: "+ex);
                    console.log("Error Create Image: "+img.src);
                    console.log("Complete: "+img.complete);
                    setTimeout(function(){
                        canvasImg();
                    },1000);
                }
            }
            /**  */
            var iSID = '<?=$iSID?>';
            var urlReq = 'http://auto2d.com/api/in.php?key='+iSID+'&f=cutCanvasKey';
            ///var urlReq = '/AjaxAPI/cutCanvasKey';
            //jQuery.support.cors = true;
            jQueryA2D.ajax({
                type: "POST",
                dataType: "json",
                //contentType: 'text/plain',
                url: urlReq,
                data: { key:iSID }
            }).done(function( msg ){
                ///console.log( "cutCanvasKey Done: " ); console.log( msg );
                if( msg.errors ){
                    alert( 'Error From AutoDealer: '+ msg.errors.msg );
                    console.log( 'Error From AutoDealer: '+ msg.errors.msg );
                }
                canvasImg(msg);
            }).fail(function(e){
                alert( "Request on cutCanvasKey: error" );
                console.log("Request on cutCanvasKey Errors: "); console.log( e.statusText );
            }).always(function(){});

        };
        img.onerror = function(){ alert("Error Loading Image: "+ urlImg); };
        img.src = urlImg; //console.log('>>> Img Load End <<<');
    }
</script>


<script>
    /** From Search */
    function checkDetailHash(){
        var dh = window.location.hash.substring(1);
        if( dh ){ ///console.log(dh);
            jQueryA2D('span.detailNumber').each(function(i,e){
                var el = jQueryA2D(e).text().replace(/\s+/g,'');
                if( el==dh ){
                    var tr = jQueryA2D(e).parents('tr')[0];
                    trClick(tr,1);
                }
            });
        }
    }
</script>


<script>
    /** Hint for Labels */
    function labelsTitle(){
        jQueryA2D('.mapLabel').each(function(){
            var id = this.id.substring(1);
            var $d = jQueryA2D('tr[data-position='+id+'] td.detailName');
            if( $d.length>0 ){
                this.title = $d.html().replace(/<br\/?>/g, "\n");
            }
        });
    }
</script>


<script>
    /** Ready Section */
    var offline = '<?=A2D::$offline?>';
    jQueryA2D(document).ready(function(){
        ///console.log(jQueryA2D.fn.jquery);
        drawCanvas();
        if( !offline ){
            setTimeout(function(){readyZeroClipboard()},100);
        }
        checkDetailHash();
        //*/
        setTimeout(function(){
            dragAndDrop("#imageLayout");
        },100);
        //*/
    });
</script>