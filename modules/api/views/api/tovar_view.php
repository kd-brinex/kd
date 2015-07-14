<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 14.07.15
 * Time: 10:50
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//var_dump($param_list  );die;
?>

<?php $form = ActiveForm::begin([
    'action' => [''],
    'method' => 'get',
    'options'=>['name' =>'tovar_api'],
]);
?>
    <div class="row">
        <div class="col-xs-10">
            <?= Html::label('Тип товара');?>
            <?= Html::dropDownList('tip_id',(isset($params['tip_id']))?$params['tip_id']:'',$tip_id,['class'=>'form-control','placeholder'=>'shina']);?>
            <?= Html::label('Магазин');?>
            <?= Html::input('text','store_id',(isset($params['store_id']))?$params['store_id']:'109',['class'=>'form-control','placeholder'=>'109']);?>
            <?= Html::label('Страница');?>
            <?= Html::input('text', 'page',(isset($params['page']))?$params['page']:'',['class'=>'form-control','placeholder'=>'1'] ) ?>
            <?= Html::label('Строк на странице');?>
            <?= Html::input('text', 'page_size',(isset($params['page_size']))?$params['page_size']:'',['class'=>'form-control','placeholder'=>'25'] ) ?>
            <?= Html::label('.. and where');?>
            <?= Html::input('text', 'where',(isset($params['where']))?$params['where']:'',['class'=>'form-control','placeholder'=>'p.price>0 and p.count>0'] ) ?>
            <?php
//            var_dump($params);die;
            foreach($param_list as $p)
            {
                echo Html::label($p['name'].'('.$p['id'].')');

                echo Html::input('text', 'options['.$p['id'].']',(isset($params['options'][$p['id']]))?$params['options'][$p['id']]:'',['class'=>'form-control','placeholder'=>$p['title']] );
            }
                ?>
            <?=Html::label('Ссылка');?>
            <?=Html::textarea('url',$url,['class'=>'form-control'])?>
            <?=Html::a('Перейти по ссылке ',$url,['target'=>'_blank','class'=>'btn btn-success'])?>
        </div>
        <?= Html::submitButton('Искать', ['class' => 'col-xs-2 btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
<?php
print_r($tovars);
?>