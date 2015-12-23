<?php

namespace app\modules\tovar\controllers;

use Yii;
use app\modules\tovar\models\Tovar;
use app\modules\tovar\models\TovarSearch;
use app\controllers\MainController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;


/**
 * TovarController implements the CRUD actions for Tovar model.
 */
class TovarController extends MainController
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
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['index', 'create', 'update'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return \Yii::$app->user->identity->getIsAdmin();
//                        }
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['list'],
//                        'roles' => ['@','?']
//                    ],
//                ]
//            ]
        ];
    }

    /**
     * Lists all Tovar models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;
//        var_dump($params);die;
        $searchModel = new TovarSearch();
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    /**
     * Displays a single Tovar model.
     * @param string $id
     * @return mixed
     */
    public function actionView()
    {
        $searchModel = new TovarSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->find_tovar_param($params);
        $tovarProvider = $searchModel->find_tovar($params);
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'tovarProvider' => $tovarProvider,
//            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Creates a new Tovar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tovar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Tovar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tovar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tovar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Tovar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tovar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCategory()
    {
        $params = Yii::$app->request->queryParams;
        if (!isset($params['viewType'])) {
            $params['viewType'] = 1;
        }
        if ($params['viewType'] == 1) {
            $params['options'] = ['tag' => 'div', 'class' => 'col-sm-12', 'style' => 'padding:0px'];
            $params['itemOptions'] = ['tag' => 'div', 'class' => 'col-sm-3 offer-v1-item-cont'];
        }
        if ($params['viewType'] == 2) {
            $params['options'] = ['tag' => 'div', 'class' => 'col-sm-12 offer-v2-container', 'style' => 'padding:0px'];
            $params['itemOptions'] = ['tag' => 'div', 'class' => 'offer-v2-item-cont borders-lite'];
        }
        if ($params['viewType'] == 3) {
            $params['options'] = ['tag' => 'table', 'class' => 'col-xs-12 table offer-v3-table'];
            $params['itemOptions'] = ['tag' => 'tr', 'class'=>'tr-hover'];
        }
//        var_dump($params);die;
        $searchModel = new TovarSearch();
        $dataProvider = $searchModel->category_list($params);
        return $this->render('category', [
            'dataProvider' => $dataProvider,
            'params' => $params,
//            'searchModel'  => $searchModel,
        ]);
    }

    public function actionFinddetails(){
        $params = \Yii::$app->request->queryParams;
        $parts = Yii::$app->params['Parts'];

        $details = (isset($params['article'])) ? Tovar::findDetails($params) : [];
        $provider = new ArrayDataProvider([
            'allModels' => $details,
            'sort' => $parts['sort'],
            'pagination' => $parts['pagination'],
        ]);


        $catalog = Yii::$app->getModule('autocatalog')->getModel();

        return $this->render('finddetails_tabs', [
            'provider' => $provider,
            'columns' => $parts['columns'],
            'params' => $params,
            'catalog' => $catalog
        ]);

    }
    public function actionBasket(){
        $data = Yii::$app->request->post();
        $toBasket = new \app\modules\basket\models\BasketSearch();
//        $toBasket->tovar_id = $data['code'];
        $toBasket->tovar_count = 1;
        $toBasket->tovar_price = $data['price'];
        $toBasket->session_id = Yii::$app->session->id;
        $toBasket->tovar_min = 1;
        $toBasket->manufacturer = $data['manufacture'];
        $toBasket->part_number = $data['code'];
        $toBasket->period = $data['srokmax'];
        $toBasket->part_name = $data['name'];
        $toBasket->allsum = $data['price'];
        $toBasket->provider_id = $data['pid'];
        if(Yii::$app->user->id)
            $toBasket->uid = Yii::$app->user->id;
        if($toBasket->save())
            return \yii\helpers\Html::a('В корзине', \yii\helpers\Url::to(['/basket']),[
                'title' => 'Заказать',
                'class' => 'btn btn-grey btn-xs',
                'target' => '_blank'
            ]);

    }



}
