<div id="catalogBreads" class="layout mt10 <?=A2D::$breadsClass?>">

    <?php if( A2D::$bLogo ){?>
        <img class="fl" src="<?=A2D::$breadsLogo?>">&emsp;
    <?php }?>

    <div class="breadsLink fl">
        <?php $i = 0; foreach( A2D::$aBreads AS $k=>$b ){ ++$i;

            /// В некоторых каталогах как ETKA возвращается марка
            $markExist = ( "mark"==current(A2D::$arrActions) ) ?TRUE :FALSE;

            /// $iface
            $catalogRoot = ( ($d=A2D::$catalogRoot) )?$d:A2D::property($b,'root');

            /// Если нужно модифицировать имя крошки, дополняем/расшираяем
            $name = A2D::getBreadsName(A2D::$mark,$k,$b);

            $breads = (array) A2D::property($b,'breads');
            $_gets  = A2D::cut($breads,'s');

            /// Нужно, если марка на втором уровне, как в случае с BMW [ /bmw/mini, "/mini" - this param ]
            A2D::$markRoute = ( $i==1 )?A2D::$markRoute:"";

            if( A2D::$humanURL ){
                $link = $catalogRoot.A2D::$markRoute;
                foreach( $breads AS $_b ) $link .= "/$_b";
                $gets = ""; /// Если есть GETS параметры, обработаем
                if( $_gets ){ $gets .= "?";
                    foreach( $_gets AS $k=>$v ) $gets .= "$k=$v&";
                }
            }
            else{
                if( $k=="refer" || A2D::property($b,'refer') ){
                    $gets = "";
                    $link = $b->url;
                    $name = $b->txt;
                }
                else{
                    if( $markExist || in_array($k,["types","marks"]) ){
                        $andMark = "";
                    }
                    else{
                        $andMark = "mark=".A2D::$mark."&";
                    }

                    $add = "";
                    $link = $catalogRoot."/{$k}.php?{$andMark}";
                    foreach( $breads AS $_k=>$_b ){
                        $link .= $add.A2D::$arrActions[$_k]."={$_b}";
                        $add = "&";
                    }
                    $link = rtrim($link,'?'); /// Что бы не вводить кучу условий, делнем лишний ? так
                    $gets = ""; if( $_gets ) foreach( $_gets AS $n=>$v ) $gets .= "&$n=$v";
                }

            }

            ?>

            <?php if( end(A2D::$aBreads)!==$b || $k=="refer" ){?>
                <a href="<?=BASE_PATH.$link.$gets?>"><?=$name?></a>&ensp;<?=( $k!="refer" )?A2D::$spBreads:""?>&ensp;
            <?php }else{?>
                <span><?=$name?></span>
            <?php }?>

        <?php }?>
    </div>
    <div class="clear"></div>

</div>