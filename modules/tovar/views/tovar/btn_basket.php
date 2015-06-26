<?php
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 27.02.15
 * Time: 10:40
 */
//var_dump($model->inbasket);die;
    if ($model->inbasket>0){
        echo '<a class="btn" href="'.url::toRoute(['/basket/basket'], true).'"><i class="icon-shopping-cart icon-white"></i>Уже в корзине</a>';}
    else
    {
        echo '<div tovar_id="'.$model->id.'" onclick="put(this)">
                <div class="btn btn-basket">
                <i class="icon-shopping-cart icon-white"></i>'.(($viewtype==3)?'':'Заказать')
            .'</div></div>';}