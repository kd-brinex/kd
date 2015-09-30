<?php

namespace app\modules\autoparts\controllers;

use Yii;

use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;

use app\modules\autoparts\models\PartProviderSearch;
use app\modules\autoparts\models\TStoreSearch;
use app\modules\autoparts\models\PartOver;
use app\modules\autoparts\models\PartOverSearch;
use app\modules\autoparts\models\UploadForm;

/**
 * OverController implements the CRUD actions for PartOver model.
 */
class OverController extends Controller
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
                            'allow' => true,
                            'roles' => ['Parts', 'Admin'],
                        ]
                    ]
                ],

        ];
    }

    /**
     * Lists all PartOver models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PartOverSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PartOver model.
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
     * Creates a new PartOver model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $flagpostav = new PartProviderSearch();
        $flag_postav_list = $flagpostav->get_flag_postav();


        $model = new PartOver();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id]);


        } else {
            return $this->render('create', [
                'model' => $model,'flag_postav_list'=>$flag_postav_list
            ]);
        }
    }

    /**
     * Updates an existing PartOver model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $flagpostav = new PartProviderSearch();
        $flag_postav_list = $flagpostav->get_flag_postav();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,'flag_postav_list'=>$flag_postav_list
            ]);
        }
    }

    /**
     * Deletes an existing PartOver model.
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
     * Finds the PartOver model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PartOver the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PartOver::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpload()
    {
//        $cities = new TStoreSearch();
//        $citylist = $cities->getCityList();
        $flagpostav = new PartProviderSearch();
        $flag_postav_list = $flagpostav->get_flag_postav();




        $model = new UploadForm();

        if (Yii::$app->request->isPost) {

            $model->setAttributes(Yii::$app->request->post('UploadForm'));

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {
                $runtime=\Yii::getAlias('@runtime').'/';
                $model->file->saveAs($runtime . $model->file->baseName . '.' . $model->file->extension);
                $text['f'] = file($runtime . $model->file);
                $model->insertData($text);
                $this->redirect('index');
            }
        }

        return $this->render('upload', ['model' => $model,'flag_postav_list'=>$flag_postav_list]);
    }
}
