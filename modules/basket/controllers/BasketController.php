<?php

namespace app\modules\basket\controllers;

use Yii;
use yii\web\Controller;
use app\modules\basket\models\BasketSearch;
use yii\helpers\Url;


class BasketController extends Controller
{
    public function actionIndex()
    {
        $model = new BasketSearch();
        $dataProvider = $model->search([]);
        if ($dataProvider->totalCount) {
            $itogo = $this->summa($dataProvider, ['tovar_summa']);
            return $this->render('index', [
                'model' => $dataProvider,
                'itogo' => $itogo,
            ]);
        } else {
            return $this->render('not_tovar');
        }

    }

    public function summa($dp, $column)
    {

        foreach ($dp->models as $data) {
            foreach ($column as $c) {
                if (isset($result[$c])) {
                    $result[$c] += $data->$c;
                } else {
                    $result[$c] = $data->$c;
                }
            }
        }
        return $result;
    }

    public function actionPut()
    {
        $this->layout = false;
        $params = Yii::$app->request->post();
        $post = array_merge(Yii::$app->request->post());
        $params = Yii::$app->request->queryParams;
//        var_dump($post);die;
        $model = new BasketSearch();
        $result=$model->put($post);
        $dataProvider = $model->search([]);

        switch ($params['mode']) {
            case 'put':
//                $t=$model->findOne(['tovar_id'=>$post['id']]);

                return '<a class="btn" href="'.url::toRoute(['/basket/basket'], true).'"><i class="icon-shopping-cart icon-white"></i>Уже в корзине</a>';
                break;
            case 'count':
                return $this->basket_row($dataProvider);
                break;
            case 'del':
                return $this->basket_row($dataProvider);
                break;
        }
//        $model->put($params);


    }

    public function basket_row($dataProvider)
    {
        if ($dataProvider->totalCount) {
            $itogo = $this->summa($dataProvider, ['tovar_summa']);
            return $this->render('zakaz_tab', [
                'model' => $dataProvider,
                'itogo' => $itogo,
            ]);
        } else {
            return $this->render('not_tovar');
        }
    }
}
