<?php

namespace app\modules\autoparts\controllers;

use Yii;
use app\modules\autoparts\models\PartProvider;
use app\modules\autoparts\models\PartProviderSearch;
use app\modules\autoparts\models\PartProviderSrok;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * ProviderController implements the CRUD actions for PartProvider model.
 */
class ProviderController extends Controller
{
    public function behaviors()
    {
        $this->layout = "/admin.php";
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
                        'allow' =>true,
                        'roles' =>['Parts','Admin'],
                    ]
                ]
            ],
        ];
    }

    /**
     * Lists all PartProvider models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new PartProviderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        var_dump($this->user->identity);die;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PartProvider model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new PartProviderSrok();
        $query=$searchModel->find()->andWhere(['provider_id'=>$id]);
        $srokprovider = new ActiveDataProvider(['query'=>$query]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'srokProvider' => $srokprovider,
            'srokModel' =>$searchModel,
        ]);
    }

    /**
     * Creates a new PartProvider model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PartProvider();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PartProvider model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
//var_dump(Yii::$app->request->post());die;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PartProvider model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PartProvider model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PartProvider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PartProvider::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
