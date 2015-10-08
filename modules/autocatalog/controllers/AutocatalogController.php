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
use app\modules\autocatalog\models\Car;
use app\modules\tovar\models\Tovar;


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
                        'actions' => ['index', 'model', 'vin', 'frame','details','cars','models','catalogs','subcatalog','catalog','parts'],
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
        $models = new CarsSearch();
        $provider = new ActiveDataProvider([
            'query' => $models->find(),
//            'sort' => ['attributes' => ['name']],
            'id' => 'cat_code',
        ]);
        $provider->pagination=false;
        return $this->render('cars', [
            'provider' => $provider,
            'params' =>$params
        ]);
    }
    public function actionModels()
    {
        $params = \Yii::$app->request->queryParams;
        $models = new ModelsSearch();
        $provider= $models->search($params);

        return $this->render('models', [
            'provider' => $provider,
            'filterModel' => $models,
            'params' =>$params
        ]);
    }
    public function actionCatalogs()
    {
        $params = \Yii::$app->request->queryParams;
        $models = new CatalogsSearch();
        $info = new InfoSearch();

        return $this->render('catalogs', [
            'provider' => $models->search($params),
            'info'=>$info->search($params),
            'params' =>$params
        ]);

    }
    public function actionCatalog()
    {
        $params = \Yii::$app->request->queryParams;
        $post = \Yii::$app->request->post();
//        var_dump($post);
        $option=implode('|',$post);
//        var_dump($option);die;
        $allparams=array_merge($params,$post);
        $models= new CatalogSearch();
        return $this->render('catalog', [
            'provider' => $models->search($allparams),
            'params' =>$allparams,
            'option' => $option,
        ]);
    }
    public function actionSubcatalog()
    {
        $params = \Yii::$app->request->queryParams;
        $models = new SubcatalogSearch();
        return $this->render('subcatalog', [
            'provider' => $models->search($params),
            'params' =>$params,
        ]);
    }
    public function actionParts()
    {

        $params = \Yii::$app->request->queryParams;
        $models = new PartsSearch();
        return $this->render('parts', [
            'models' => $models->search($params),
            'params' =>$params,
        ]);
    }
    public function actionVin()
    {
        $params = \Yii::$app->request->queryParams;
        $catalog = $this->module->getCatalog();
        $model = $this->module->searchVIN($params);
        return $this->render('vin', [
            'catalog' => $catalog,
            'params' =>$params,
            'model' => $model
        ]);
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
