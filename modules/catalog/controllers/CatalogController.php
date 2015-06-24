<?php

namespace app\modules\catalog\controllers;

use yii\web\Controller;

use app\modules\catalog\models;


class CatalogController extends Controller
{
    public function actionIndex()
    {
        $params=\Yii::$app->request->queryParams;
        $searchModel = new models\Toyota();
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);

    }
    public function actionIndexvin()
    {
        $params=\Yii::$app->request->queryParams;
        $params['vin']=(isset($params['vin']))?$params['vin']:'';
        $searchModel = new models\Toyota();
        $dataProvider = $searchModel->searchVin($params);
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
        $dataProvider=$searchModel->searchCatalog($params);
        $dataProvider->pagination=false;
        return $this->render('catalog', [
//            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }
    public function actionModel()
    {
        $params=\Yii::$app->request->queryParams;

        $searchModel = new models\Toyota();
        $dataProvider=$searchModel->searchModelSelect($params);
        return $this->render('model', [
//            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }
    public function actionAlbum()
    {
        $params=\Yii::$app->request->queryParams;

        $searchModel = new models\Toyota();
        $dataProvider=$searchModel->searchAlbum($params);
        return $this->render('album', [
//            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }
}