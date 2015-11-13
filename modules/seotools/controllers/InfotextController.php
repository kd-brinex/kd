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
     * Displays a single Infotext model.
     * @param integer $meta_id
     * @param integer $city_id
     * @return mixed
     */
    public function actionView($meta_id, $city_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($meta_id, $city_id),
        ]);
    }

    /**
     * Creates a new Infotext model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Infotext();
        $model->meta_id = Yii::$app->request->get('meta_id');

        $city_list = $this->getCitylist();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //TODO костыль тчобы возаращаться на страницу информации мета тэгов
            if(Yii::$app->request->get('_j')==2)
            {
                return $this->redirect(['manage/view', 'id' => $model->meta_id]);
            }
            else {
                return $this->redirect(['view', 'meta_id' => $model->meta_id, 'city_id' => $model->city_id]);
            }
        } else {

            if(Yii::$app->request->isAjax) {
                $render = 'renderAjax';
            }
            else {
                $render = 'render';
            }
            return $this->$render('create', [
                'model' => $model,
                'city_list' => $city_list,
            ]);

        }
    }

    /**
     * Updates an existing Infotext model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $meta_id
     * @param integer $city_id
     * @return mixed
     */
    public function actionUpdate($meta_id, $city_id)
    {
        $model = $this->findModel($meta_id, $city_id);
        $city_list = $this->getCitylist();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //TODO костыль тчобы возаращаться на страницу информации мета тэгов
            if(Yii::$app->request->get('_j')==2)
            {
                return $this->redirect(['manage/view', 'id' => $model->meta_id]);
            }
            else {
                return $this->redirect(['view', 'meta_id' => $model->meta_id, 'city_id' => $model->city_id]);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
                'city_list' => $city_list,
            ]);
        }
    }

    /**
     * Deletes an existing Infotext model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $meta_id
     * @param integer $city_id
     * @return mixed
     */
    public function actionDelete($meta_id, $city_id)
    {
        $this->findModel($meta_id, $city_id)->delete();

        //TODO костыль тчобы возаращаться на страницу информации мета тэгов
        if(Yii::$app->request->get('_j')==2)
        {
            return $this->redirect(['manage/view', 'id' => $meta_id]);
        }
        else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Infotext model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $meta_id
     * @param integer $city_id
     * @return Infotext the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($meta_id, $city_id)
    {
        if (($model = Infotext::findOne(['meta_id' => $meta_id, 'city_id' => $city_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

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
