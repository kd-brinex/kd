<?php

namespace app\modules\api\controllers;
use app\modules\api\models\UploadForm;
use app\modules\loader\models\VLoader;
use yii\web\Controller;
use app\modules\api\models\Api;
use yii\web\UploadedFile;
class ApiController extends Controller
{
    public function actionFinddetails()
    {

        $this->layout=false;
    $params = \Yii::$app->request->queryParams;
//    $params = $_POST;
        $params=array_merge($params,$_POST);
//        var_dump($params);die;
    $details=Api::findDetails($params);
//    var_dump($details);die;
    return $details;
    }
    public function actionTovar_tip()
    {
        $this->layout=false;
        $params = \Yii::$app->request->queryParams;
        $params=array_merge($params,$_POST);
        $tovars=Api::tovar_tip($params);
        return $tovars;
    }
    public function actionTovar()
    {
        $this->layout=false;
        $params = \Yii::$app->request->queryParams;
        $params=array_merge($params,$_POST);
        $tovars=Api::tovar($params);
        return $tovars;
    }
    public function actionTtovar_tip()
    {
        $this->layout=false;
        $params = \Yii::$app->request->queryParams;
//        var_dump($params);die;
        $params=array_merge($params,$_POST);
        $tovars=Api::ttovar_tip($params);
//        var_dump($tovars);die;
        return $tovars;
    }
    public function actionIndex()
    {
       return $this->render('index');
    }
    public function actionTovar_view()
    {
        $params = \Yii::$app->request->queryParams;
        $params['page']=isset($params['page'])?$params['page']:1;
        $params['page_size']=isset($params['page_size'])?$params['page_size']:25;
        $params['tip_id']=isset($params['tip_id'])?$params['tip_id']:'shina';
        $params['options']=isset($params['options'])?$params['options']:[];
        $params['store_id']=isset($params['store_id'])?$params['store_id']:109;
        $params['where']=isset($params['where'])?$params['where']:'';
        $params['orderby']=isset($params['orderby'])?$params['orderby']:'';
        $tovars=Api::ttovar_tip($params);
        $tip_id=Api::ttovar_tip_id_list();
        $param_list=Api::tparam_list($params);
        $url=Api::getUrl_ttovar_tip($params);
//        var_dump($tip_id);die;
        return $this->render('tovar_view',['params'=>$params,'tovars'=>$tovars,'tip_id'=>$tip_id,'param_list'=>$param_list,'url'=>$url]);
    }
    public function actionLoader()
    {
        $provider= VLoader::loader();
        $model = new UploadForm();
        $text ='';
        if (\Yii::$app->request->isPost) {
            $model->textFile = UploadedFile::getInstance($model, 'textFile');
            if ($model->upload()) {
                $text = $model->getSql();
            }
        }
        return $this->render('loader',['provider'=>$provider,'model'=>$model,'text'=>$text]);
    }
}