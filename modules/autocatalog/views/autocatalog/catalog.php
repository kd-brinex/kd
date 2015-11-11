<?php
use yii\widgets\Breadcrumbs;
app\modules\autocatalog\CatalogAsset::register($this);
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 03.10.15
 * Time: 15:37
 */
//var_dump($option);die;
echo (!empty($params['breadcrumbs']))?Breadcrumbs::widget(['links'=>$params['breadcrumbs']]):'';
?>
<div class="models">
    <?= $this->render('listview',['dataProvider'=>$provider,'view'=>'block','params'=>$params])?>
</div>

<?php
//$params = \Yii::$app->request->queryParams;
$p['marka']=$params['marka'];
$p['catalog']=$params['region'];
$p['family']=$params['family'];
$p['cat_code']=$params['cat_code'];
$p['option']=$params['option'];
$p['model_name']=$params['cat_folder'];
$p['model_code']=$params['cat_folder'];
$p['version']=1;
$p['vin']=(!empty($params['vin']))?$params['vin']:'';
$this->registerJs("var options = ".json_encode($p).";", \yii\web\View::POS_HEAD, 'getOptions');
?>
