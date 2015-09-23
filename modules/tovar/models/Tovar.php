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
    /**
     * findDetails - описание функции
     */
    public static function findDetails($params){                                                                                   //  здесь приходят article и provider_id из URI

        if(!empty($params['store_id']) || !empty($params['provider_id']))
            return self::debugDetails($params);

        $details = [];
        $providerObj = null;
        $providers = PartProviderSearch::find()
                    ->where('enable = :enable', [':enable' => 1])
                    ->all();

        foreach($providers as $provider){
            if($provider->enable) {
                if($provider->cross){
                    $providerObj = Yii::$app->getModule('autoparts')->run->provider($provider->name, ['provider_data' => $provider]);
                    $items = $providerObj->findDetails(['code' => $params['article']]);
                    if(!empty($items) && is_array($items)){
                        foreach($items as $item){
                            array_push($details, $item);
                        }
                    } else continue;
                }
            }
        }
        foreach($details as $detail){
            $crosses[$detail['code']] = $detail['groupid'];
        }
        foreach($providers as $provider){
            if($provider->enable){
                if(!$provider->cross){
                    $providerObj = Yii::$app->getModule('autoparts')->run->provider($provider->name, ['provider_data' => $provider]);
                    $items = $providerObj->findDetails(['code' => $params['article']]);
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
                    foreach ($items as $item) {
                        array_push($details, $item);
                    }
                }
            }
        }
//        var_dump($details);die;
        function r_usort($a, $b, $key){
            $inta = intval($a[$key]);
            $intb = intval($b[$key]);

            if ($inta != $intb) {
                return ($inta > $intb) ? 1 : -1;
            }
            return 0;
        }
        usort($details, function ($a, $b){
            $r = r_usort($a,$b ,'weight');
            if ($r == 0){
                $r = r_usort($a, $b, 'price');
                if ($r == 0) {
                    $r = r_usort($a, $b, 'srokmax');
                }
            }
            return $r;
        });

        return $details;
    //        $parts = Yii::$app->params['Parts'];                                                                                        //  массив параметров из конфига params
//        $avtoproviders = $parts['PartsProvider'];                                                                                   //  праметры провайдеров из того же конфига params
//        $details = [];
//        $where = (isset($params['provider_id']) ? ['id' => $params['provider_id']] : ['enable' => 1]);                              //  массив condition для запроса в бд в таблицу part_provider (если есть provider_id то забираем по нему если нет то всех включенных)
//        //$providers= PartProvider::find()->where($where)->orderBy(['weight' => SORT_ASC])->asArray()->all();
//        $providers = PartProvider::find()->where($where)->orderBy(['cross' => SORT_DESC, 'weight' => SORT_ASC])->asArray()->all();  //  собственно сам запрос в таблицу и сортировка
////        $providers = PartProvider::find()->where('enable=1')->orderBy(['weight' => SORT_ASC])->asArray()->all();
////        var_dump($providers,$params);die;
////        $providers= PartProvider::find()->asArray()->all();
//        if (isset($params['article']) && $params['article'] != '') {                                                                //  работаем если есть артикуль
//            if (!isset($params['store_id'])) {                                                                                      //  устанавливаем идентификатор магазина
//                $params['store_id'] = 109;
//            }
//            foreach ($providers as $p) {
//                if (isset($avtoproviders[$p['name']])) {
//                   $provider = Yii::$app->getModule('autoparts')->run->provider($p['name']);
//                    $details = $provider->findDetails(['code' => $params['article']]);
////                   $provider = array_merge($avtoproviders[$p['name']], $params,$p);
////                    $fparts = new $provider['class']($provider);
////                    //$fparts->flagpostav = $p['flagpostav'];
////                    //$fparts->setData($p);
////                    $e = [];
////                    if ($p['cross'] == 1) {
////                        $det = $fparts->findDetails($e);
//////                        var_dump($det);die;
////                        foreach ($det as $i) {
////                            $cross[$i['code']] = $i['groupid'];
////                        }
////                    } else {
////                        if (empty($cross)){$cross[$params['article']] = 0;}
//////                        foreach ($cross as $key => $value) {
//////                            $fparts->article = $key;
//////                            $det = $fparts->findDetails($e);
//////                            if (isset($det[0]['code'])) {
//////                                $det[0]['groupid'] = $value;
//////                                $details = array_merge($details, $det);
//////                                $det=[];
//////                            }
//////                        }
////                        if (isset($params['test'])) {
////                            print_r($fparts->errors);
////                        }
////                    }
////                    if (isset($det[0]['code'])) {
////                        $details = array_merge($details, $det);
////                    }
////                    $fparts->close();
//                }
//            }
//                /**Сортировка массива поп полю srokmax
//             *
//             * */

//            return $details;
        }
    private function debugDetails($params){
        if(!empty($params['provider_id'])){
            $provider = (int)$params['provider_id'];
            $provider = PartProviderSearch::findOne($provider);

            $provider_data = ['provider_data' => $provider];

            if(!empty($params['store_id']))
                $provider_data = array_merge($provider_data, ['store_id' => $params['store_id']]);

            $providerObj = Yii::$app->getModule('autoparts')->run->provider($provider->name, $provider_data);

            $options = ['code' => $params['article']];

            $items = $providerObj->findDetails($options);
        } else {
            $providers = PartProviderSearch::find()->all();
           foreach($providers as $provider){
               $provider_data = ['provider_data' => $provider];

               if(!empty($params['store_id']))
                   $provider_data = array_merge($provider_data, ['store_id' => $params['store_id']]);

               if(empty(Yii::$app->getModule('autoparts')->params['providers'][$provider->name]))
                   continue;

               $providerObj = Yii::$app->getModule('autoparts')->run->provider($provider->name, $provider_data);

               $options = ['code' => $params['article']];

               $items[] = $providerObj->findDetails($options);
           }
        }
        var_dump($items);
    }

}
