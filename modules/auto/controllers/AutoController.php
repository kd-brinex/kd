<?php

namespace app\modules\auto\controllers;

use yii\web\Controller;
use app\modules\auto\models\Auto;

class AutoController extends Controller
{
    public function actionIndex()
    {
        $catalog=$this->module->catalog;
        $oA2D = \Yii::$app->a2d;
        $oAdcpi = \Yii::$app->adcpi;
//        var_dump($oA2D);
        $aTypes = $oAdcpi->getTypeList();
        return $this->render('index',['catalog'=>$catalog,'aTypes'=>$aTypes,'oA2D'=>$oA2D,'oAdcpi'=>$oAdcpi]);
    }
    public function actionMarks()
    {
        $params=\Yii::$app->request->queryParams;
        $oA2D = \Yii::$app->a2d;
        $oAdcpi = \Yii::$app->adcpi;
        $sTypeID = $params['typeid'];
//        var_dump($params['typeid'],$sTypeID);die;
        $oMarkList = $oAdcpi->getMarkList($sTypeID);
        $aMarkList =$oAdcpi->property($oMarkList,'marks');
        $sTypeName = $oAdcpi->property($oMarkList,'typeName');
        $oAdcpi->aBreads = $oAdcpi->toObj([
            'types' => [
                "name" => 'Каталог',
                "breads" => []
            ],
            'marks' => [
                "name" => $sTypeName,
                "breads" => []
            ],
        ]);
        return $this->render('marks',[
            'oA2D'=>$oA2D,
            'oAdcpi'=>$oAdcpi,
            'aMarkList'=>$aMarkList,
            'sTypeName'=>$sTypeName,
            'oMarkList'=>$oMarkList,
        ]);
    }
    public function actionModels(){
        $params=\Yii::$app->request->queryParams;
        $oAdcpi = \Yii::$app->adcpi;
//        var_dump($params['markid'],$params['typeid']);die;
        $models=$oAdcpi ->getModelList($params['markid'],$params['typeid']);
        return $this->render('models',[
            'models'=>$models,
            'params'=>$params,
        ]);
    }
    public function actionTest(){
        $params=\Yii::$app->request->queryParams;
        $oAdcpi = \Yii::$app->adcpi;
    }
    public function actionTree(){

        $params=\Yii::$app->request->queryParams;
        $oAdcpi = \Yii::$app->adcpi;
        $oA2D = \Yii::$app->a2d;
        $bMultiArray=false;
        $tree=$oAdcpi->getTreeList($params['modelid'],$bMultiArray);
        return $this->render('tree',[
            'oTreelList'=>$tree,
            'oA2D'=>$oA2D,
            'bMultiArray'=>$bMultiArray,
            'sModelID'=>$params['modelid'],
            'tree'=>$tree,
            'oAdcpi'=>$oAdcpi,
        ]);

    }
    public function actionMap(){

        $params=\Yii::$app->request->queryParams;
        $oAdcpi = \Yii::$app->adcpi;
        $oA2D = \Yii::$app->a2d;
//        $bMultiArray=false;
//        $tree=$oAdcpi->getTreeList($params['modelid'],$bMultiArray);
        return $this->render('map',[
            'oA2D'=>$oA2D,
            'oAdcpi'=>$oAdcpi,
            'params'=>$params,
        ]);

    }
    public function actionDetailinfo(){
        $this->layout = false;
        $params=\Yii::$app->request->queryParams;
        $oAdcpi = \Yii::$app->adcpi;
        $oA2D = \Yii::$app->a2d;

//        $bMultiArray=false;
//        $tree=$oAdcpi->getTreeList($params['modelid'],$bMultiArray);
        return $this->render('detailinfo',[
            'oA2D'=>$oA2D,
            'oAdcpi'=>$oAdcpi,
            'params'=>$params,
        ]);

    }
}
