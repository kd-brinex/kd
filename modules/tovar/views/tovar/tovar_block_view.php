<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.01.15
 * Time: 15:39
 */

?>
    <h1><?=$model->name?></h1>
    <div class="offer-page-info-kod">Код: <?=$model->id?></div>
<!--    <div class="clearfix"></div>-->
    <div class="offer-page-img" style="background-image:url(<?=$model->bigimage?>);"></div>
    <div class="offer-page-desc">
        <div class="offer-page-price">
            <div class="offer-page-price-cta">
                <a class="btn btn-warning" href="javascript:basketControlActivate1(48437,1);">
                    <i class="icon-shopping-cart icon-white"></i> Заказать</a>
                <input type="hidden" id="48437" class="basket-cnt" size="4" value="0" onchange="basketInputControlActivate('48437',108)">
<!--                <span class="basket-price" id="48437Controls"></span>-->
<!--                <div class="basket-price" id="48437Price">659</div>-->
<!--                <div class="basket-price" id="48437Sel">23</div>-->
            </div>
            <div class="offer-page-price-name">Цена</div>
            <div class="offer-page-price-new"><?=$model->price?> р.</div>
            <br>
            <div class="offer-page-bonus">
                <a href="/samara/buyer/program-ball/" target="_blank" title="Количество начисляемых баллов. Баллы начисляются при покупке товара через сайт! Начисленные баллы становятся активными по истечении 14 дней с момента покупки.">
                    <div class="product-bonustest">
                        <span>+30</span>
                    </div>
                </a>
            </div>
        </div>

        <table class="table offer-page-table-deliv">
            <tbody><tr>
                <td><div class="offer-page-table-deliv-1">&nbsp;</div></td>
                <td><div class="offer-page-table-deliv-2">В магазине<br><span>2</span></div></td>
                <td><div class="offer-page-table-deliv-2">На складе<br><span>8</span></div></td>
            </tr>
            </tbody></table>

    </div>
<!--    <div class="clearfix"></div>-->
    <div class="full-view-spec">
        <p></p>
    </div>
