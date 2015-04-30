<?php

namespace app\modules\tovar\models;

use app\modules\basket\models\BasketSearch;
use Yii;
use app\modules\autoparts\models\PartProvider;
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
            [['id', 'tip_id', 'category_id', 'name', 'price', 'count', 'value_char', 'param_id','description'], 'required'],
            [['id', 'category_id'], 'string', 'max' => 9],
            [['tip_id', 'param_id'], 'string', 'max' => 25],
            [['name', 'value_char','description'], 'string', 'max' => 200],
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
//        var_dump($this);die;

        $p = Yii::$app->params;
//        var_dump( $p['image'][$this->tip_id]);die;

        return (isset($p['image'][$this->tip_id]['name'])) ?
            $p['host'] . $p['image'][$this->tip_id]['normal'] . $this[$p['image'][$this->tip_id]['name']]. '.jpg':
            'http://img2.kolesa-darom.ru/img/' . $this->tip_id . '/' . $this->category_id . '.jpg';
    }

    public function getBigimage()
    {
        $p = Yii::$app->params;
        return (isset($p['image'][$this->tip_id]['name'])) ?
            $p['host'] . $p['image'][$this->tip_id]['big'] .   $this->category_id . '.jpg':
            'http://img2.kolesa-darom.ru/img/' . $this->tip_id . '/' . $this->category_id . '.jpg';
    }

    public function getSrok()
    {
        return ($this->count > 0) ? '<span class="offer-v1-deliv-instock">✓В наличии</span>' : '<span class="offer-v1-deliv-days">• Доставка 3-5 дней</span>';
    }
    public function getHash()
    {
        $hash='';
        $hash= base64_encode(Yii::$app->security->encryptByPassword('{"tovar_id":"'.$this->id.'","tovar_price":'.$this->price.'}',Yii::$app->params['securitykey']));
//        $hash=base64_encode('{tovar_id:'.$this->id.',tovar_price:'.$this->price.'}');
//        $hash=base64_decode($hash);
    return $hash;
    }

    public static function find(){
        $city_id=Yii::$app->request->cookies['city'];
        $p['id_store']=999;
        $query =parent::find();
        $query->where('(id_store=:id_store) and not(title is null) and (value_char != \'\')',$p);
        return $query;
    }
    public function getBasket(){
    return $this->hasOne(BasketSearch::className(),['tovar_id'=>'id']);
}
    public function getInbasket(){
        return (isset($this->basket))?$this->basket->id:0;
    }
    public function asCurrency($value){
     $rub=str_replace(',00','',Yii::$app->formatter->asCurrency($value,'RUB'));
        return $rub;
}
    public static function findDetails($params){
        $parts = Yii::$app->params['Parts'];
        $avtoproviders=$parts['PartsProvider'];
        $details = [];

        $providers= PartProvider::find()->where('enable=1')->asArray()->all();
//        var_dump($_POST,$params, $providers);die;
        if(isset($params['article'])&&$params['article']!='') {
            if (!isset($params['store_id'])) {
                $params['store_id'] = 109;
            }
            foreach ($providers as $provider) {
                $provider = array_merge($avtoproviders[$provider['name']], $params);
                $fparts = new $provider['class']($provider);
                $e = [];
                $det = $fparts->findDetails($e);
                $details = array_merge($details, $det);
                $fparts->close();
            }
        }

           return $details;
    }
}
