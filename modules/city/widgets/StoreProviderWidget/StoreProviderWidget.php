<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 30.12.15
 * Time: 15:21
 */
namespace app\modules\city\widgets\StoreProviderWidget;

use app\modules\autoparts\models\PartProvider;
use app\modules\autoparts\models\PartProviderSearch;
use app\modules\autoparts\models\PartProviderSrok;
use app\modules\autoparts\models\PartProviderUser;
use app\modules\city\models\City;
use app\modules\city\models\Store;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

class StoreProviderWidget extends Widget
{
    public $store_id;
    public $provider_user;
    public $provider_srok;
    public $city_id;

    public function init()
    {
        $this->city_id = Store::findOne(['id' => $this->store_id])->city_id;
        $this->provider_srok = new ActiveDataProvider([
            'query' => PartProviderSrok::find()->andWhere(['city_id' => $this->city_id]),
        ]);
        $this->provider_user = new ActiveDataProvider([
            'query' => PartProviderUser::find()->andWhere(['store_id' => $this->store_id])
        ]);
    }

    public function run()
    {
        $params = [
            'provider_srok' => $this->provider_srok,
            'provider_user' => $this->provider_user,
        ];
        return $this->render('index', $params);
    }
}