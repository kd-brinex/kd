<?php

namespace app\modules\catalog\controllers;

use yii\web\Controller;

use app\modules\catalog\models;
use yii\filters\VerbFilter;
use app\modules\netcat\Netcat;
use yii\filters\AccessControl;

class CatalogController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->getIsAdmin();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'model', 'indexvin', 'indexframe'],
                        'roles' => ['?', '@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['catalog', 'album', 'page'],
                        'roles' => ['?', '@']
                    ],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $params = \Yii::$app->request->queryParams;
        $user_id = (isset($params['user_id'])) ? $params['user_id'] : '0';
        $searchModel = new models\Toyota();
//        var_dump(\Yii::$app->request->cookies);die;
        $params['breadcrumbs'] = $searchModel->getBreadcrumbs($params);
        $dataProviderEU = $searchModel->search(['catalog' => 'EU', 'user_id' => $user_id]);
//        var_dump($dataProviderEU);die;
//        $dataProviderEU->pagination=false;
        $dataProviderGR = $searchModel->search(['catalog' => 'GR', 'user_id' => $user_id]);
//        $dataProviderGR->pagination=false;
        $dataProviderJP = $searchModel->search(['catalog' => 'JP', 'user_id' => $user_id]);
//        $dataProviderJP->pagination=false;
        $dataProviderUS = $searchModel->search(['catalog' => 'US', 'user_id' => $user_id]);
//        $dataProviderUS->pagination=false;
//        var_dump($dataProviderEU->query->getUrlParams('action'));die;
//        Крошки
//        $params['title']=$dataProviderEU->query->url_params['name'];
//        $params['breadcrumbs'][]=$params['title'];
        return $this->render('index', [
//            'searchModel' => $searchModel,
            'dataProviderEU' => $dataProviderEU,
            'dataProviderGR' => $dataProviderGR,
            'dataProviderJP' => $dataProviderJP,
            'dataProviderUS' => $dataProviderUS,
            'params' => $params,
        ]);

    }

    public function actionIndexvin()
    {
        $params = \Yii::$app->request->queryParams;
        $params['vin'] = (isset($params['vin'])) ? $params['vin'] : '';

//        $params['user_id']=(isset($params['user_id']))?$params['user_id']:'';
        $searchModel = new models\Toyota();
//        $vin_info = $searchModel->TOY_VIN_info($params);
        $dataProvider = $searchModel->searchVin2($params);

        //Крошки

        $params['breadcrumbs'] = $searchModel->getBreadcrumbs($params);
//        $params['title']=$dataProvider->query->name;
//        $params['breadcrumbs'][]=['label'=>$dataProvider->query->name,'url'=>Url::to('toyota/catalog')];
//        $params['breadcrumbs'][]=$dataProvider->query->model_name;

//        $params['model_name']=$dataProvider->models[0]['model_name'];

        return $this->render('model', [
//            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
//            'vin_info' => $vin_info,
        ]);

    }

    public function actionIndexframe()
    {
        $params = \Yii::$app->request->queryParams;

        $params['frame'] = (isset($params['frame'])) ? $params['frame'] : '';
        $params['number'] = (isset($params['number'])) ? $params['number'] : '';
        $params['model_name'] = (isset($params['model_name'])) ? $params['model_name'] : '';
        $params['user_id'] = (isset($params['user_id'])) ? $params['user_id'] : '';

        $searchModel = new models\Toyota();

        $dataProvider = $searchModel->searchFrame($params);
        $params['breadcrumbs'] = $searchModel->getBreadcrumbs($params);
//Крошки
//        $params['title']=$dataProvider->query->name;
//        $params['breadcrumbs'][]=['label'=>$dataProvider->query->name,'url'=>Url::to('toyota/catalog')];
//        $params['breadcrumbs'][]=$dataProvider->query->model_name;

        return $this->render('model', [
//            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);

    }

    public function actionCatalog()
    {
        $params = \Yii::$app->request->queryParams;
//        $params['user_id']= (isset($params['user_id'])) ? $params['user_id'] : '0';
        // добавляем каталог в личный кабинет пользователя
        \app\modules\netcat\Netcat::remote_add_catalog($params);

        $searchModel = new models\Toyota();
        $dataModel = $searchModel->searchModelOne($params);
        $dataCatalog = $searchModel->searchCatalog($params);
        $params['breadcrumbs'] = $searchModel->getBreadcrumbs($params);
        $dataCatalog->pagination = false;

        if (isset($params['vid'])) {
            return $this->render($params['vid'], [
                'searchModel' => $searchModel,
                'dataProvider' => $dataCatalog,
                'dataModel' => $dataModel,
                'params' => $params,
            ]);
        } else {
            return $this->render('catalog', [
                'searchModel' => $searchModel,
                'dataCatalog' => $dataCatalog,
                'dataModel' => $dataModel,
                'params' => $params,
//            'user_id' => $user_id,
            ]);
        }
    }

    public function actionModel()
    {
        $params = \Yii::$app->request->queryParams;

        $searchModel = new models\Toyota();
        $dataProvider = $searchModel->searchModelSelect($params);
        $params['breadcrumbs'] = $searchModel->getBreadcrumbs($params);


            return $this->render('model', [
                'dataProvider' => $dataProvider,
                'params' => $params,
            ]);

    }

    public function actionAlbum()
    {
        $params = \Yii::$app->request->queryParams;
        $searchModel = new models\Toyota();
        $dataProvider = $searchModel->searchAlbum($params);
        $params['breadcrumbs'] = $searchModel->getBreadcrumbs($params);
        return $this->render('album', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }

    public function actionPage()
    {
        $params = \Yii::$app->request->queryParams;
        $searchModel = new models\Toyota();
        $model = $searchModel->searchPage($params[1]);
        $params['breadcrumbs'] = $searchModel->getBreadcrumbs($params[1]);
        return $this->render('page', [
            'model' => $model,
            'params' => $params,
        ]);
    }


}