<?php

namespace app\modules\user\models;

use app\modules\autoparts\models\OrderUpdate1c;
use app\modules\autoparts\models\ProviderStateCode;
use Yii;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\modules\autoparts\models\TStore;
/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $product_id
 * @property integer $quantity
 * @property string $reference
 * @property integer $status
 * @property string $datetime
 *
 * @property User $u
 */
class Orders extends \yii\db\ActiveRecord
{
    public $normalizeDate;

    const ORDER_IN_STOCK = 1;
    const ORDER_GOING_TO_SHOP = 2;
    const ORDER_IN_SHOP = 3;
    const ORDER_ISSUED = 5;
    const ORDER_CANCELED = 6;
    const ORDER_ADOPTED = 10;
    const ORDER_SHIPPED = 11;
    const ORDER_BOUGHT = 12;
    const ORDER_GOING_TO_STOCK = 13;
    const ORDER_CANCELED_BY_PROVIDER = 14;

    const DEFAULT_DELIVERY_DAYS = 5;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quantity', 'status', 'datetime', 'part_price'], 'required', 'except' => 'update'],
            [['quantity', 'status',  'provider_id','order_id', 'delivery_days'], 'integer'],
            [['datetime'], 'safe'],
            [['product_id'], 'string', 'max' => 9],
            [['product_article'], 'string', 'max' => 32],
            [['reference'], 'string', 'max' => 50],
            [['part_name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 255],
            [['manufacture'],'string','max'=>45],
            [['1c_orders_id'],'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID заказа',
            'product_id' => 'ID товара',
            'quantity' => 'Количество',
            'reference' => 'Reference',
            'status' => 'Статус',
            'datetime' => 'Дата заказа',
//            'pay_datetime' => 'Дата платежа'
            'delivery_days' => 'Срок доставки'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct_url()
    {
        if($this->product_id != ''){
            $url = ['/tovar/'.$this->product_id];}

       else {
            $url = ['/autocatalog/autocatalog/details', 'article' => $this->product_article];}
        return Url::to($url);

    }

    public function getOrder_class()
    {
        return $this->orderClass[$this->status];
    }
    public function getOrderClass()
    {
        return [
            self::ORDER_ADOPTED => 'ORDER_ADOPTED',
            self::ORDER_SHIPPED => 'ORDER_SHIPPED',
            self::ORDER_BOUGHT => 'ORDER_BOUGHT',
            self::ORDER_IN_STOCK => 'ORDER_IN_STOCK',
            self::ORDER_GOING_TO_STOCK => 'ORDER_GOING_TO_STOCK',
            self::ORDER_GOING_TO_SHOP => 'ORDER_GOING_TO_SHOP',
            self::ORDER_IN_SHOP => 'ORDER_IN_SHOP',
            self::ORDER_ISSUED => 'ORDER_ISSUED',
            self::ORDER_CANCELED => 'ORDER_CANCELED',
            self::ORDER_CANCELED_BY_PROVIDER => 'ORDER_CANCELED_BY_PROVIDER'
        ];
    }
    public function getProduct()
    {
        return $this->hasOne(\app\modules\tovar\models\TovarSearch::className(), ['id' => 'product_id']);
    }
    public function getOrder()
    {
        return $this->hasOne(OrderSearch::className(),['id'=>'order_id']);
    }

    public function getStore_id()
    {
        return $this->order->store_id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getUserData()
//    {
//        return $this->hasOne(\app\modules\user\models\Profile::className(), ['user_id' => 'uid']);
//    }

    public function getState(){
        return $this->hasOne(OrdersState::className(), ['id' => 'status']);
    }

    public function getStateAll(){
        $states = OrdersState::find()->asArray(true)->all();
        return ArrayHelper::map($states, 'id', 'status_name');
    }

    public function getMinState(){
        $states = [];
        $positions = $this->find()->asArray()->select('status')->where('order_id = :order_id AND related_detail IS NOT NULL AND product_article = :product_article', [':order_id' => $this->order->id, ':product_article' => $this->product_article])->all();
        foreach($positions as $position){
            $states[] = $position['status'];
        }
        if(!empty($states))
            return min($states);
    }

    public function getStore(){
        return $this->hasOne(TStore::className(), ['id' => 'store_id']);
    }

    public function getProvider(){
        return $this->hasOne(\app\modules\autoparts\models\PartProviderSearch::className(), ['id' => 'provider_id']);
    }

    public function getProviderOrderStatusName(){
        return $this->hasOne(\app\modules\autoparts\models\ProviderStateCode::className(), ['provider_id' => 'provider_id', 'status_code' => 'order_provider_status']);
    }

    public function getAllProviderOrderStatusName(){
      return $this->hasMany(\app\modules\autoparts\models\ProviderStateCode::className(), ['provider_id' => 'provider_id']);
    }



    public function beforeSave($insert){
//        if($this->isNewRecord){
//            $this->status = self::ORDER_IN_WORK;
//            $this->datetime = new \yii\db\Expression('NOW()');
//        }

//        $date = new \DateTime($this->pay_datetime);
//        $this->pay_datetime = $date->format('Y-m-d H:i:s');

//        $date = new \DateTime($this->datetime);
//        $this->datetime = $date->format('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }
    public function afterSave($insert, $changedAttributes){
//        $date = new \DateTime($this->pay_datetime);
//        $this->pay_datetime = $date->format('d.m.Y H:i');
    }
    public function afterFind(){
//        $date = new \DateTime($this->pay_datetime);
//        $this->pay_datetime = $date->format('d.m.Y H:i');

        $date = new \DateTime($this->datetime);
        $this->datetime = $date->format('d.m.Y H:i');
    }
    public function getCost()
    {
        return $this->quantity * $this->part_price;
    }
}
