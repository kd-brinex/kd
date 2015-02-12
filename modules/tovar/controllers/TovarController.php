<?php

namespace app\modules\tovar\controllers;

use Yii;
use app\modules\tovar\models\Tovar;
use app\modules\tovar\models\TovarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//use yii\filters\AccessControl;
/**
 * TovarController implements the CRUD actions for Tovar model.
 */
class TovarController extends Controller
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
        $params=Yii::$app->request->queryParams;
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
    public function actionView($id)
    {
        $searchModel = new TovarSearch();
        $params=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->find_tovar_param($params);
        $tovarProvider = clone $dataProvider;
        $tovarProvider->setModels( [$dataProvider->models[0]]);
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'tovarProvider'=>$tovarProvider,
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
        $params=Yii::$app->request->queryParams;
        if (!isset($params['viewType'])){$params['viewType']=1;}
        if ($params['viewType']==1)
        {
            $params['options']=['tag'=>'ul','class'=>'offer-v1-container'];
            $params['itemOptions']=['tag'=>'li'];
        }
        if ($params['viewType']==2)
        {
            $params['options']=['tag'=>'ul','class'=>'offer-v2-container'];
            $params['itemOptions']=['tag'=>'li'];
        }
        if ($params['viewType']==3)
        {
            $params['options']=['tag'=>'table','class'=>'offer-v3-table'];
            $params['itemOptions']=['tag'=>'tr'];
        }
        $searchModel = new TovarSearch();
        $dataProvider = $searchModel->category_list($params);
        return $this->render('category', [
            'dataProvider' => $dataProvider,
            'params'=>$params,
//            'searchModel'  => $searchModel,
        ]);
    }
}
