<?php

namespace app\modules\catalog\controllers;

use yii\web\Controller;

use app\modules\catalog\models;
use yii\helpers\Url;


class CatalogController extends Controller
{
    public function actionIndex()
    {
        $params=\Yii::$app->request->queryParams;
        $searchModel = new models\Toyota();
        $searchModel->setData($params);
        //Крошки
        $params['title']=$searchModel->name;
        $params['breadcrumbs'][]=$searchModel->name;

        $dataProviderEU = $searchModel->search(['catalog'=>'EU']);
        $dataProviderEU->pagination=false;
        $dataProviderGR = $searchModel->search(['catalog'=>'GR']);
        $dataProviderGR->pagination=false;
        $dataProviderJP = $searchModel->search(['catalog'=>'JP']);
        $dataProviderJP->pagination=false;
        $dataProviderUS = $searchModel->search(['catalog'=>'US']);
        $dataProviderUS->pagination=false;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProviderEU' => $dataProviderEU,
            'dataProviderGR' => $dataProviderGR,
            'dataProviderJP' => $dataProviderJP,
            'dataProviderUS' => $dataProviderUS,
            'params' => $params,
        ]);

    }
    public function actionIndexvin()
    {
        $params=\Yii::$app->request->queryParams;
        $params['vin']=(isset($params['vin']))?$params['vin']:'';
        $searchModel = new models\Toyota();
        $searchModel->setData($params);
        $dataProvider = $searchModel->searchVin($params);
        //Крошки
        $params['title']=$searchModel->name;
        $params['breadcrumbs'][]=['label'=>$searchModel->name,'url'=>Url::to('toyota/catalog')];
        $params['breadcrumbs'][]=$searchModel->model_name;

        $params['model_name']=$dataProvider->models[0]['model_name'];
        return $this->render('model', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);

    }
    public function actionIndexframe()
    {
        $params=\Yii::$app->request->queryParams;
        $params['frame']=(isset($params['frame']))?$params['frame']:'';
        $params['number']=(isset($params['number']))?$params['number']:'';
        $params['model_name']=(isset($params['model_name']))?$params['model_name']:'';
        $searchModel = new models\Toyota();
        $searchModel->setData($params);
        //Крошки
        $params['title']=$searchModel->name;
        $params['breadcrumbs'][]=['label'=>$searchModel->name,'url'=>Url::to('toyota/catalog')];
        $params['breadcrumbs'][]=$searchModel->model_name;
        $dataProvider = $searchModel->searchFrame($params);
        return $this->render('model', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);

    }
    public function actionCatalog()
    {
        $params=\Yii::$app->request->queryParams;

        $searchModel = new models\Toyota();
        $searchModel->setData($params);

        //Крошки
        $params['title']=$searchModel->name;
        $params['breadcrumbs'][]=['label'=>$searchModel->name,'url'=>Url::to('toyota/catalog')];
        $params['breadcrumbs'][]=['label'=>$searchModel->model_name];
//        var_dump($searchModel);die;
        $dataProvider=$searchModel->searchCatalog($params);
        $dataProvider->pagination=false;
        return $this->render('catalog', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }
    public function actionModel()
    {
        $params=\Yii::$app->request->queryParams;

        $searchModel = new models\Toyota();
        $searchModel->setData($params);
        //Крошки
        $params['title']=$searchModel->name;
        $params['breadcrumbs'][]=['label'=>$searchModel->name,'url'=>Url::to('toyota/catalog')];
        $params['breadcrumbs'][]=['label'=>$searchModel->model_name];
        $dataProvider=$searchModel->searchModelSelect($params);
        return $this->render('model', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }
    public function actionAlbum()
    {
        $params=\Yii::$app->request->queryParams;

        $searchModel = new models\Toyota();
        $searchModel->setData($params);
        $dataProvider=$searchModel->searchAlbum($params);
        return $this->render('album', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }
}