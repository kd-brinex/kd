<?php

$this->params['breadcrumbs'][] = ['label'=>'Каталог','url'=>['/auto']];
$this->params['breadcrumbs'][] = ['label'=>$auto->sTypeName,'url'=>['/auto/marks/'.$auto->sTypeID]];
$this->params['breadcrumbs'][] = ['label'=>$auto->sMarkName,'url'=>['/auto/models/'.$auto->sTypeID.'_'.$auto->sMarkID]];
$this->params['breadcrumbs'][] = ['label'=>$auto->sModelName];
?>
<div class="col-xs-12">

    <div><h1> <?=$auto->sMarkName?> <?=$auto->sModelName?>&raquo;</h1></div>

<?php

echo $auto->tree;