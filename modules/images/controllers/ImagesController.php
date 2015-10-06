<?php

namespace app\modules\images\controllers;

use Yii;
use app\modules\images\models\ImgImage;
use app\modules\images\models\ImgImageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * File_inputController implements the CRUD actions for ImgImage model.
 */
class ImagesController extends Controller
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
        ];
    }

    /**
     * Lists all ImgImage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ImgImageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ImgImage model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ImgImage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ImgImage();

        if (!empty(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            echo '<pre>';
            print_r($image);
            echo '</pre>';die;
            die;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ImgImage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
     * Deletes an existing ImgImage model.
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
     * Finds the ImgImage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ImgImage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ImgImage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function  actionUpload()
    {
        $a = Yii::$app->request->post('file_id');

        if (isset($a))
            {
            $model = new ImgImage();
            $model->image = UploadedFile::getInstance($model, 'image');
            $model->table = 'test';
                $ran = time();
            $model->src = '/uploads/'.$model->table .'/'. $ran.$model->image->baseName. '.' . $model->image->extension;
            $model->save();
            return $model->image->saveAs('uploads/' . $model->table . '/' . $ran . $model->image->baseName . '.' . $model->image->extension);
        }
        return true;


    }
}
