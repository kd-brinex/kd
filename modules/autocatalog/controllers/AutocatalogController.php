<?php

namespace app\modules\autocatalog\controllers;

use app\controllers\MainController;

use app\modules\autocatalog\models\CatalogSearch;
use app\modules\autocatalog\models\PartsSearch;
use app\modules\autocatalog\models\FilterModel;
use app\modules\autocatalog\models\InfoSearch;
use app\modules\autocatalog\models\ModelsSearch;
use app\modules\autocatalog\models\CarsSearch;
use app\modules\autocatalog\models\CatalogsSearch;
use app\modules\autocatalog\models\SubcatalogSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\autocatalog\models\CCar;
use app\modules\tovar\models\Tovar;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class AutocatalogController extends MainController
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
                        'actions' => ['index', 'model', 'vin', 'frame','details','cars','models','catalogs','subcatalog','catalog','parts','podbor'],
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
        $catalog = $this->module->getModel();
        return $this->render('index', [
            'catalog' => $catalog,
            'params' =>$params,


        ]);
    }
    public function actionCars()
    {
        $params = \Yii::$app->request->queryParams;
        $car=$this->module->getClass();
        $regions=$car::Regions($params);
        $params['breadcrumbs']=$car::Breadcrumbs($params);
        foreach($regions->models as $region) {
            $reg=$region->region;
            $p=$params;
            $p['region']=$reg;
            $provider[$reg] = $car::Cars($p);
        }
        return $this->render('cars', [
            'provider' => $provider,
            'params' =>$params,
            'regions'=>$regions,
        ]);
    }
    public function actionModels()
    {

        $params = \Yii::$app->request->queryParams;
        if(empty($params['ModelsSearch']['region'])){$params['ModelsSearch']['region']=$params['region'];}
        $car=$this->module->getClass();
        $model=$car::ModelsSearch($params);
        $provider=$car::Models($params);
        $params['breadcrumbs']=$car::Breadcrumbs($params);
        return $this->render('models', [
            'provider' => $provider,
            'filterModel' => $model,
            'params' =>$params,
        ]);
    }
    public function actionCatalogs()
    {
        $params = \Yii::$app->request->queryParams;
        $params['post']=\Yii::$app->request->post();
        if (!empty($params['post'])){
        $params['option']=implode('|',$params['post']);}
        else{ $params['option']=base64_decode($params['option']);}
        $car=$this->module->getClass();
        $provider=$car::Catalogs($params);
        $podbor = $car::Podbor($params);
        $info=$car::Info($params);
        $params['breadcrumbs']=$car::Breadcrumbs($params);
        return $this->render('catalogs', [
            'provider' => $provider,
            'podbor' => $podbor,
            'info'=>$info,
            'params' =>$params
        ]);

    }
    public function actionPodbor()
    {
        $params = \Yii::$app->request->queryParams;



        $car=$this->module->getClass();
        $provider=$car::Podbor($params);


        if(empty($params['family'])){$params['family']='';}
        if(empty($params['year'])){$params['year']='';}
        if(empty($params['engine'])){$params['engine']='';}

        $familys=array_unique(ArrayHelper::map($provider->models,'family','family'));
        asort($familys);
        $params['familys']=$familys;

        if(!empty($params['family'])) {
            $years = explode(';', $provider->models[0]->years);
            foreach ($years as $y) {
                $params['years'][$y] = $y;
            }
        }

        if(!empty($params['year'])) {
            $engines=array_unique(ArrayHelper::map($provider->models,'key','value'));
            $b=[];
            foreach($engines as $key=>$value)
            {
                $a=array_combine (explode(';',$key),explode(';',$value) );
                foreach($a as $k=>$v){
                $b[$k]=$v;
                }

            }

            $params['engines']=$b;
        }


        return $this->render('podbor', [
            'params' =>$params
        ]);

    }
    public function actionCatalog()
    {
        $params = \Yii::$app->request->queryParams;
        $car=$this->module->getClass();
        $params['post']=\Yii::$app->request->post();
        $params['breadcrumbs']=$car::Breadcrumbs($params);
        unset($params['post']['StoreID']);
        if (empty($params['option']) & !empty($params['post'])){
            $params['option']=implode('|',$params['post']);}

        $provider = $car::Catalog($params);
        \app\modules\netcat\Netcat::kd_add_catalog($params);
        return $this->render('catalog', [
            'provider' => $provider,
            'params' =>$params,
        ]);
    }
    public function actionSubcatalog()
    {
        $params = \Yii::$app->request->queryParams;
        $params['option']=base64_decode($params['option']);
        $car=$this->module->getClass();
        $provider = $car::SubCatalog($params);
        $params['breadcrumbs']=$car::Breadcrumbs($params);
        return $this->render('subcatalog', [
            'provider' => $provider,
            'params' =>$params,
        ]);
    }
    public function actionParts()
    {

        $params = \Yii::$app->request->queryParams;
        $params['option']=base64_decode($params['option']);
        $car=$this->module->getClass();
        $provider = $car::Parts($params);
        $images=$car::Images($params);
        $params['breadcrumbs']=$car::Breadcrumbs($params);
        $model = $provider->models;
        $arr = [];
        foreach ($model as $item) {
            $arr['models'][$item['pnc']][$item['number']] = $item;
            $arr['labels'][$item['pnc']][] = $item;
        }
        return $this->render('parts', [
            'models' => $arr,
            'params' =>$params,
            'images' =>$images,
        ]);
    }

    public function actionVin()
    {
        $params = \Yii::$app->request->queryParams;
        $vin=$this->module->searchVin($params);
        if ($vin) {$model=$vin->models[0];


        $redirect='/autocatalogs/'.$model->marka.'/'.$model->region.'/'.$model->family.'/'.$model->cat_code.'/'.base64_encode($model->option);
        return $this->redirect($redirect);}
        else
        {
            $redirect='/autocatalogs/'.$params['marka'].'/'.$params['region'];
            return $this->redirect($redirect);
        }
    }
    public function actionDetails()
    {

        $params = \Yii::$app->request->queryParams;
        $catalog = $this->module->getModel();
        if (isset($params['article'])) {
            $parts = \Yii::$app->params['Parts'];

            $details = isset($params['article']) ? Tovar::findDetails($params) : [];
            $provider = new ArrayDataProvider([
                'allModels' => $details,
                'sort' => $parts['sort'],
                'pagination' => $parts['pagination'],
            ]);

            return $this->render('index', [
                'catalog' => $catalog,
                'provider' => $provider,
                'columns' => $parts['columns'],
                'params' =>$params]);
        }
        return $this->render('details', [
            'catalog' => $catalog,
            'params' =>$params
        ]);
    }


}
