<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 13.08.15
 * @time: 11:09
 */

namespace app\modules\autoparts\controllers;

use app\modules\autoparts\models\PartProviderSearch;
use Yii;
use yii\base\Exception;
use yii\web\Controller;

use app\modules\autoparts\models\TStore;
use app\modules\autoparts\models\PartProviderUserSearch;

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

}