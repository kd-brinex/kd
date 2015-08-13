<?php

namespace app\modules\autocatalog\controllers;
use app\controllers\MainController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\autocatalog\models\Car;
class AutocatalogController extends MainController
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
                        'actions' => ['index', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->getIsAdmin();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'model', 'indexvin', 'indexframe'],
                        'roles' => ['?', '@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['catalog', 'album', 'page'],
                        'roles' => ['?', '@']
                    ],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $params = \Yii::$app->request->queryParams;
        $regionList = $this->module->catalog->getRegionList();
        $params['region']=(isset($params['region'])?$params['region']:key($regionList));
//        var_dump($params);die;
        $this->module->catalog->setAttributes($params);
        $data=$this->module->getModelList($params);
//        var_dump($data);die;
//        var_dump($prm);die;
        return $this->render($data['properties']['marka'].'/index.php',[
            'data'=>$data,
            'regionList'=>$regionList,
            'params'=>$params,

        ]);
    }
    public function actionModel()
    {
        $params = \Yii::$app->request->queryParams;
        $data = $this->module->getCatalogList($params);
//        var_dump($catalog);die;
        return $this->render($data['properties']['marka'].'/model',[
            'data'=>$data,
            'params'=>$params,
             ]);

    }

}
