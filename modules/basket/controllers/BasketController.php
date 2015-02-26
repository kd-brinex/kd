<?php

namespace app\modules\basket\controllers;

use Yii;
use yii\web\Controller;
use app\modules\basket\models\BasketSearch;


class BasketController extends Controller
{
    public function actionIndex()
    {
        $model = new BasketSearch();
        $dataProvider = $model->search([]);
        if($dataProvider->totalCount){
        $itogo=$this->summa($dataProvider,['tovar_summa']);
            return $this->render('index', [
                'model' => $dataProvider,
                'itogo' => $itogo,
            ]);}
        else{
            return $this->render('not_tovar');
        }

    }

    public function summa($dp,$column){

    foreach($dp->models as $data) {
        foreach ($column as $c) {
            if(isset($result[$c])){$result[$c]+=$data->$c;}else{$result[$c]=$data->$c;}
    }
    }
    return $result;
    }
    public function actionPut(){
        $this->layout = false;
        $params=Yii::$app->request->post();
        $model = new BasketSearch();
        $model->put($params);
        $dataProvider = $model->search([]);
        if($dataProvider->totalCount){
        $itogo=$this->summa($dataProvider,['tovar_summa']);
        return $this->render('zakaz_tab', [
            'model' => $dataProvider,
            'itogo' => $itogo,
        ]);}
        else{
            return $this->render('not_tovar');
        }

     }


}
