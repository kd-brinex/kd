<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 13.08.15
 * @time: 11:09
 */

namespace app\modules\autoparts\controllers;
use app\modules\autoparts\models\PartProvider;
use app\modules\autoparts\models\PartProviderSearch;
use Yii;
use yii\base\Exception;
use yii\web\Controller;

use app\modules\autoparts\models\TStore;
use app\modules\autoparts\models\PartProviderUserSearch;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\helpers\BrxArrayHelper;

class ProviderController extends Controller
{
    //TODO убрать отсюда это дело в модель
    public $cityId;
    public $storeId = 109;

    protected function provider($provider, array $options = []){
        $class = 'app\modules\\'.$this->module->id.'\components\BrxProvider';

        if(!empty(($access = $this->getAccess($provider))))
            $options = BrxArrayHelper::array_replace_recursive_ncs($options, $access);

        $provider = new $class($provider, $options);

        return $provider;
    }

    //TODO убрать отсюда это дело в модель
    private function getStoreId(){
        if(!empty(($city = $this->getCityId())))
          $this->storeId = TStore::find()
                            ->select('id')
                            ->where('city_id = :city_id', [':city_id' => $city])
                            ->one()
                            ->id;

        return $this->storeId;
    }

    private function getCityId(){
        if (!empty(($cookie = Yii::$app->request->cookies['city'])))
            $this->cityId = (int)$cookie->value;

        return $this->cityId;
    }

    private function getAccess($provider){
        if(!empty(($store = $this->getStoreId())) && !empty(($provider_id = $this->getProviderId($provider))))
            $accessData = PartProviderUserSearch::find()
                            ->select('login, password')
                            ->asArray()
                            ->where('store_id = :store_id AND provider_id = :provider_id', [':store_id' => $store, ':provider_id' => $provider_id])
                            ->one();

        return $accessData;
    }

    private function getProviderId($provider){
        $provider = PartProviderSearch::find()
                        ->select('id')
                        ->where('name = :name', [':name' => $provider])
                        ->one();

        if(empty($provider))
            throw new Exception('Провайдер не определен в базе данных');

        return $provider->id;
    }
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
                        'allow' => true,
                        'roles' => ['Parts', 'Admin'],
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
        return $this->render('view', [
            'model' => $this->findModel($id),
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