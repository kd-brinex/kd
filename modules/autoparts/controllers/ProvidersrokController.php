<?php

namespace app\modules\autoparts\controllers;
use app\modules\autoparts\models\PartProviderSrokSearch;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\autoparts\models\PartProviderSrok;
use yii\data\ActiveDataProvider;
class ProvidersrokController extends \yii\web\Controller
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
    public function actionCreate()
    {
        $model = new PartProviderSrokSearch();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionIndex()
    {
        $searchModel = new PartProviderSrokSearch();
        $dataProvider=$searchModel->search(\Yii::$app->request->queryParams);

//        var_dump($this->user->identity);die;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
//var_dump(Yii::$app->request->post());die;
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

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
    protected function findModel($id)
    {
        if (($model = PartProviderSrok::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}
