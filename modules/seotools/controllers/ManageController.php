<?php

namespace app\modules\seotools\controllers;

use Yii;
use app\modules\seotools\models\base\MetaBase;
use app\modules\seotools\models\base\Infotext;
use app\modules\seotools\models\InfotextSearch;
use app\modules\seotools\models\MetaSearch;
use app\modules\seotools\models\Meta;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ManageController implements the CRUD actions for MetaBase model.
 */
class ManageController extends Controller
{

    /**
     * @var \jpunanua\seotools\Module seotool module
     */
    public $module;


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
                        'roles' => $this->module->roles,
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all MetaBase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MetaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MetaBase model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'infotext' => $this->findInfotext($model->id_meta),
        ]);
    }

    /**
     * Creates a new MetaBase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Meta();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setMetalinks($model->keywords,true);
            return $this->redirect(['view', 'id' => $model->id_meta]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MetaBase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->keywords !== Yii::$app->request->post("keywords"))
        {
            $ch_keywords = true;
        }
        else {
            $ch_keywords = false;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setMetalinks($model->keywords,$ch_keywords);
            return $this->redirect(['view', 'id' => $model->id_meta]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MetaBase model.
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
     * Finds the MetaBase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MetaBase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Meta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function findInfotext($meta_id,$city_id = '')
    {
        $infotext = new InfotextSearch();
        $dataProvider = $infotext->search(['InfotextSearch' => ['meta_id' => $meta_id, 'city_id' => $city_id]]);

        if ($dataProvider !== null) {
            return $dataProvider;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function setMetalinks($keywords, $ch_keywords)
    {
        if($ch_keywords == true)
        {
            $keywords = explode(",",$keywords);

            foreach($keywords as $keyword)
            {
//                if(!preg_match("/\{\{[a-zA-Z_\-]{2,15}\}\}/",$keyword))
                if(!preg_match(\app\modules\seotools\Component::REGREPLACE,$keyword))
                {
                    $keyword = trim(mb_strtolower($keyword));
                    $meta = Meta::find()
                        ->where('keywords LIKE "%'.$keyword.'%"')
                        ->all();

                    $link = $this->setLink($meta,$keyword);

                    $meta_link = \app\modules\seotools\models\base\MetaLinks::findOne($keyword);
                    if($meta_link == null)
                    {
                        $meta_link = new \app\modules\seotools\models\base\MetaLinks();
                    }
                    $meta_link->keyword = $keyword;
                    $meta_link->link = $link['route'];
                    $meta_link->seq_number = $link['seq_number'];
                    $meta_link->save();
                }
            }
        }
    }

    public function setLink($meta,$keyword)
    {
        $route = null;
        $seq_number = null;
        foreach($meta as $m)
        {

            $keywords = array_map('strtolower',explode(",",$m->keywords));
            $seq_number_i = array_search($keyword,$keywords);
            if($keyword === $keywords[$seq_number_i])
            {
                if (empty($route) || $seq_number > $seq_number_i) {
                    $route = $m->route;
                    $seq_number = $seq_number_i;
                }

            }
        }
        return ['route' => $route, 'seq_number' => $seq_number];
    }
}
