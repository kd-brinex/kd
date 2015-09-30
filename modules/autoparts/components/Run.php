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

        if(!empty($shippingPeriod = $this->getShippingPeriod($provider, $options)))
            $options = BrxArrayHelper::array_replace_recursive_ncs($options, $shippingPeriod);

        $provider = new $class($provider, $options);
        return ($provider instanceof BrxProvider) ? $provider : false;
    }

    //TODO убрать отсюда это дело в модель
    private function getStoreId($options, $provider_id = ''){
        if(!empty($options['store_id'])){
            $this->storeId = $options['store_id'];
            unset($options['store_id']);
        } else if(!empty(($city = $this->getCityId($options)))) {
            $store = TStore::find()
                ->joinWith('partProviderUsers')
                ->where('city_id = :city_id AND part_provider_user.provider_id = :provider_id' , [':city_id' => $city, ':provider_id' => $provider_id])
                ->one();
            if ($store)
                $this->storeId = $store->id;
        }

        return $this->storeId;
    }

    private function getCityId($options){
        if(!empty($options['city_id'])){
            $this->cityId = $options['city_id'];
            unset($options['city_id']);
        } else if(!empty($options['store_id'])){
            $this->cityId = TStore::find()
                ->select('city_id')
                ->where('id = :id', [':id' => $options['store_id']])
                ->one()
                ->city_id;
        } else if (!empty(($cookie = Yii::$app->request->cookies['city'])))
            $this->cityId = (int)$cookie->value;

        return $this->cityId;
    }

    private function getAccess($provider, $options){
        if(empty(($provider_id = $this->getProviderId($provider))))
           return false;

        if(!empty(($store = $this->getStoreId($options, $provider_id))))
            $accessData = PartProviderUserSearch::find()
                ->select('login, password, marga, store_id')
                ->asArray()
                ->where('store_id = :store_id AND provider_id = :provider_id', [':store_id' => $store, ':provider_id' => $provider_id])
                ->one();
        return isset($accessData) ? $accessData : false;
    }

    private function getShippingPeriod($provider, $options){
        $shippingPeriod = PartProviderSrok::find()->select('days')->asArray()->where(['city_id' => $this->getCityId($options),'provider_id'=>$this->getProviderId($provider)])->one();
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