<?php

namespace app\modules\seotools\controllers;

use Yii;
use app\modules\seotools\models\base\Infotext;
use app\modules\seotools\models\InfotextSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InfotextController implements the CRUD actions for Infotext model.
 */
class InfotextController extends Controller
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
     * Lists all Infotext models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InfotextSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Infotext1 model.
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
     * Creates a new Infotext1 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Infotext();
        $city_list = $this->getCitylist();
        $meta_id = Yii::$app->request->get('_j');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //TODO костыль тчобы возаращаться на страницу информации мета тэгов
            if(!empty($meta_id))
            {
                return $this->redirect(['manage/view', 'id' => $meta_id]);
            }
            else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->meta_id = $meta_id;
            return $this->render('create', [
                'model' => $model,
                'city_list' => $city_list,
            ]);
        }

    }

    /**
     * Updates an existing Infotext1 model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $city_list = $this->getCitylist();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //TODO костыль тчобы возаращаться на страницу информации мета тэгов
            if(!empty($meta_id = Yii::$app->request->get('_j')))
            {
                return $this->redirect(['manage/view', 'id' => $meta_id]);
            }
            else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'city_list' => $city_list,
            ]);
        }

    }

    /**
     * Deletes an existing Infotext1 model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        //TODO костыль тчобы возаращаться на страницу информации мета тэгов
        if(!empty($meta_id = Yii::$app->request->get('_j')))
        {
            return $this->redirect(['manage/view', 'id' => $meta_id]);
        }
        else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Infotext1 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Infotext1 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Infotext::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // получаем список городов
    protected function getCitylist()
    {
        $city_list =  \app\modules\city\models\City::find()
            ->where(['enable' => 1])
            ->orderBy('name')
            ->asArray()
            ->all();
        return $city_list;
    }
}
