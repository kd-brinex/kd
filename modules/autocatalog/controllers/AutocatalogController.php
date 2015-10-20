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
//        $provider=$catalog->getCars();

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
        //чтобы работал фильтр по региону
        if(empty($params['ModelsSearch']['region'])){$params['ModelsSearch']['region']=$params['region'];}
        $car=$this->module->getClass();
        $model=$car::ModelsSearch($params);
        $provider=$car::Models($params);

        return $this->render('models', [
            'provider' => $provider,
            'filterModel' => $model,
            'params' =>$params,
        ]);
    }
    public function actionCatalogs()
    {
        $params = \Yii::$app->request->queryParams;
        $car=$this->module->getClass();
        $provider=$car::Catalogs($params);
        $info=$car::Info($params);

        return $this->render('catalogs', [
            'provider' => $provider,
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
//        var_dump(111);die;
        $params = \Yii::$app->request->queryParams;
        $car=$this->module->getClass();
        $post = \Yii::$app->request->post();
        $params['option']=implode('|',$post);

        $provider = $car::Catalog($params);

        return $this->render('catalog', [
            'provider' => $provider,
            'params' =>$params,
        ]);
    }
    public function actionSubcatalog()
    {
//        var_dump(111);die;
        $params = \Yii::$app->request->queryParams;
        $car=$this->module->getClass();
        $provider = $car::SubCatalog($params);
        return $this->render('subcatalog', [
            'provider' => $provider,
            'params' =>$params,
        ]);
    }
    public function actionParts()
    {

        $params = \Yii::$app->request->queryParams;
        $car=$this->module->getClass();
        $provider = $car::Parts($params);
        $images=$car::Images($params);
        $model = $provider->models;
        $arr = [];
        foreach ($model as $item) {
            $arr['models'][$item['pnc']][$item['number']] = $item;

//            $arr['models'][$item['pnc']][$item['pnc']] = $item;
//            $arr['models'][$item['pnc']][] = $item;
//            $arr['labels'][][$item['number']][$item['x1'] . 'x' . $item['y1']] = $item;
            $arr['labels'][$item['pnc']][] = $item;
        }
//        var_dump($arr);die;
        return $this->render('parts', [
            'models' => $arr,
            'params' =>$params,
            'images' =>$images,
        ]);
    }

    public function actionVin()
    {
        $params = \Yii::$app->request->queryParams;
        $model=$this->module->searchVin($params)->models[0];
        $redirect='/autocatalogs/'.$model->marka.'/vin/'.$model->family.'/'.$model->cat_code.'?option='.$model->option;
//        var_dump($provider->models[0]->cat_code);die;
//        $params['cat_code']=$model->models[0]->cat_code;
//        $car=$this->module->getClass();
//        $provider=$car::Catalogs($params);
//        $info=$car::Info($params);

        return $this->redirect($redirect);
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
