<?php

namespace app\modules\tovar\models;

use app\modules\autoparts\models\PartProviderSearch;
use app\modules\basket\models\BasketSearch;
use Yii;
use app\modules\autoparts\models\PartProvider;
use yii\base\Exception;

/**
 * This is the model class for table "t_tovar".
 *
 * @property string $id
 * @property string $tip_id
 * @property string $category_id
 * @property string $name
 */
class Tovar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tovar';
    }

    /**
     * @inheritdoc
     */
//SELECT `tovar`.`id`,
//`tovar`.`tip_id`,
//`tovar`.`category_id`,
//`tovar`.`name`,
//`tovar`.`param_id`,
//`tovar`.`value_char`,
//`tovar`.`value_int`,
//`tovar`.`value_float`,
//`tovar`.`id_store`,
//`tovar`.`price`,
//`tovar`.`count`
//FROM `brinex1`.`tovar`;

    public function rules()
    {
        return [
            [['id', 'tip_id', 'category_id', 'name', 'price', 'count', 'value_char', 'param_id', 'description'], 'required'],
            [['id', 'category_id'], 'string', 'max' => 9],
            [['tip_id', 'param_id'], 'string', 'max' => 25],
            [['name', 'value_char', 'description'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 500],
            [['price', 'count'], 'int']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tip_id' => 'Tip ID',
            'category_id' => 'Category ID',
            'name' => 'Наименование',
            'price' => 'Цена',
            'count' => 'Кол.',
            'value_char' => 'Значение',
            'image' => 'Изображение',
            'param_id' => 'Характеристика',
            'bigimage' => 'Изображение',
            'description' => 'Описание',
        ];
    }

    public function getImage()
    {
        $p = Yii::$app->params;
        return (isset($p['image'][$this->tip_id]['name'])) ?
            $p['host'] . $p['image'][$this->tip_id]['normal'] . $this[$p['image'][$this->tip_id]['name']] . '.jpg' :
            'http://img2.kolesa-darom.ru/img/' . $this->tip_id . '/' . $this->category_id . '.jpg';
    }

    public function getBigimage()
    {
        $p = Yii::$app->params;
        return (isset($p['image'][$this->tip_id]['name'])) ?
            $p['host'] . $p['image'][$this->tip_id]['big'] . $this->category_id . '.jpg' :
            'http://img2.kolesa-darom.ru/img/' . $this->tip_id . '/' . $this->category_id . '.jpg';
    }

    public function getSrok()
    {
        return ($this->count > 0) ? '<span class="offer-v1-deliv-instock">✓В наличии</span>' : '<span class="offer-v1-deliv-days">• Доставка 3-5 дней</span>';
    }

    public function getHash()
    {
        $hash = '';
        $hash = base64_encode(Yii::$app->security->encryptByPassword('{"tovar_id":"' . $this->id . '","tovar_price":' . $this->price . '}', Yii::$app->params['securitykey']));
//        $hash=base64_encode('{tovar_id:'.$this->id.',tovar_price:'.$this->price.'}');
//        $hash=base64_decode($hash);
        return $hash;
    }

    public static function find()
    {
        $city_id = Yii::$app->request->cookies['city'];
        $p['store_id'] = 109;
        $query = parent::find();
//        $query->where('(store_id=:store_id) and not(title is null) and (value_char != \'\')',$p);
        $query->where('(store_id=:store_id)', $p);
        return $query;
    }

    public function getBasket()
    {
        $basket = BasketSearch::find()
            ->select('id')
            ->where(['uid' => 9])
            ->orWhere(['session_id' => Yii::$app->session->oldSessId])
            ->orWhere(['session_id' => Yii::$app->session->id])
            ->andWhere(['tovar_id' => $this->id])
            ->one();

        return $basket;
    }
    public function getInbasket()
    {
        return (isset($this->basket)) ? $this->basket->id : 0;
    }
    public function asCurrency($value)
    {
        $rub = str_replace(',00', '', Yii::$app->formatter->asCurrency($value, 'RUB'));
        return $rub;
    }
    public static function getProviderOrderState($params, $store){
        if(!is_array($params['order_id']) && count($params['order_id']) == 1 &&
           ($params['provider'] == 'Berg' || $params['provider'] == 'Emex'))
            $params['order_id'] = [$params['order_id']];

        $providerObj = Yii::$app->getModule('autoparts')->run->provider($params['provider'], ['store_id' => $store]);
        return $providerObj->getOrderState(['order_id' => $params['order_id']]);
    }


    /**
     * findDetails - описание функции
     */
    public static function findDetails($params){                                                                                   //  здесь приходят article и provider_id из URI
        $details = [];
        $providerObj = null;
        $providers = PartProviderSearch::find()
                    ->where('enable = :enable', [':enable' => 1])
                    ->all();
        foreach($providers as $provider){
            if($provider->cross){
                $options = ['provider_data' => $provider, 'article' => $params['article']];
                if(!empty($params['store_id'])) $options = array_merge($options, ['store_id' => (int)$params['store_id']]);
                if(!empty($params['city_id'])) $options = array_merge($options, ['city_id' => (int)$params['city_id']]);
                $providerObj = Yii::$app->getModule('autoparts')->run->provider($provider->name, $options);

                $search_settings = ['code' => $params['article']];

                $items = $providerObj->findDetails($search_settings);

                if(!empty($items) && is_array($items)){
                    foreach($items as $item){
                        array_push($details, $item);
                    }
                } else continue;
            }
        }
        foreach($details as $detail){
            $crosses[$detail['code']] = $detail['groupid'];
        }
        foreach($providers as $provider){
            if(!$provider->cross){
                $options = ['provider_data' => $provider];
                if(!empty($params['store_id'])) $options = array_merge($options, ['store_id' => (int)$params['store_id']]);
                if(!empty($params['city_id'])) $options = array_merge($options, ['city_id' => (int)$params['city_id']]);


                $providerObj = Yii::$app->getModule('autoparts')->run->provider($provider->name, $options);

                if(!empty($params['store_id'])) $providerObj->store_id = (int)$params['store_id'];

                $search_settings = ['code' => $params['article']];

                if($provider->name == 'Over')
                    $search_settings = array_merge($search_settings, ['flagpostav' => $provider->flagpostav]);

                $items = $providerObj->findDetails($search_settings);
                if(!empty($crosses)) {
                    $crossItems = null;
                    foreach ($crosses as $crossCode => $crossGroup) {
                        if (isset($items)) {
                            if (!is_array($items))
                                continue;

                            $crossItems = $providerObj->findDetails(['code' => $crossCode]);
                            foreach ($crossItems as $item) {
                                $item['groupid'] = $crossGroup;
                            }
                        }
                    }
                    if(!empty($items))
                        $items = array_merge($items, $crossItems);
                }
                if(!empty($items)) {
                    foreach ($items as $item) {
                        array_push($details, $item);
                    }
                }
            }
        }
        usort($details, function ($a, $b){
            $r = self::r_usort($a,$b ,'weight');
            if ($r == 0){
                $r = self::r_usort($a, $b, 'price');
                if ($r == 0) {
                    $r = self::r_usort($a, $b, 'srokmax');
                }
            }
            return $r;
        });

        return $details;

    }

    private function r_usort($a, $b, $key){
        $inta = intval($a[$key]);
        $intb = intval($b[$key]);

        if ($inta != $intb) {
            return ($inta > $intb) ? 1 : -1;
        }
        return 0;
    }

}
