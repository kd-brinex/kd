<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 03.09.15
 * @time: 14:03
 */
namespace app\modules\autoparts\components;

use Yii;

use yii\base\Exception;
use yii\base\Component;

use app\modules\autoparts\models\TStore;
use app\modules\autoparts\models\PartProviderSearch;
use app\modules\autoparts\models\PartProviderUserSearch;
use app\modules\autoparts\models\PartProviderSrok;

use app\helpers\BrxArrayHelper;

class Run extends Component{
    //TODO убрать отсюда это дело в модель
    public $cityId;
    public $storeId;// = 109;

    public function provider($provider, array $options = []){
        $class = 'app\modules\autoparts\components\BrxProvider';
        if(!empty(($access = $this->getAccess($provider, $options))))
            $options = BrxArrayHelper::array_replace_recursive_ncs($options, $access);

        if(!empty($shippingPeriod = $this->getShippingPeriod($provider)))
            $options = BrxArrayHelper::array_replace_recursive_ncs($options, $shippingPeriod);

        $provider = new $class($provider, $options);

        return ($provider instanceof BrxProvider) ? $provider : false;
    }

    //TODO убрать отсюда это дело в модель
    private function getStoreId($options){
        if(!empty($options['store_id'])){
            $this->storeId = $options['store_id'];
            unset($options['store_id']);
        } else if(!empty(($city = $this->getCityId()))) {
            $store = TStore::find()
                ->select('id')
                ->where('city_id = :city_id', [':city_id' => $city])
                ->one();
            if ($store)
                $this->storeId = $store->id;
        }
        return $this->storeId;
    }

    private function getCityId(){
        if (!empty(($cookie = Yii::$app->request->cookies['city'])))
            $this->cityId = (int)$cookie->value;
        else
            $this->cityId = 1751;

        return $this->cityId;
    }

    private function getAccess($provider, $options){
        if(!empty(($store = $this->getStoreId($options))) && !empty(($provider_id = $this->getProviderId($provider))))
            $accessData = PartProviderUserSearch::find()
                ->select('login, password, marga, store_id')
                ->asArray()
                ->where('store_id = :store_id AND provider_id = :provider_id', [':store_id' => $store, ':provider_id' => $provider_id])
                ->one();

        return isset($accessData) ? $accessData : false;
    }

    private function getShippingPeriod($provider){
        $shippingPeriod = PartProviderSrok::find()->select('days')->asArray()->where(['city_id' => $this->getCityId(),'provider_id'=>$this->getProviderId($provider)])->one();
        return $shippingPeriod;
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