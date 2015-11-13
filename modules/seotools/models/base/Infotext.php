<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 30.10.15
 * Time: 8:06
 */
namespace app\modules\seotools\models\base;

use Yii;
use yii\db\ActiveRecord;

class Infotext extends ActiveRecord
{
    public static function tableName()
    {
        return 'meta_infotext';
    }

    public function rules()
    {
        return [
            [['meta_id', 'city_id'], 'required'],
            [['infotext_before', 'infotext_after'], 'string'],
            [['meta_id', 'city_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'meta_id' => 'Meta id',
            'city_id' => 'City id',
            'infotext_before' => Yii::t('seotools', 'Text before content'),
            'infotext_after' => Yii::t('seotools', 'Text after content'),
        ];
    }

    public function getCity()
    {
        return $this->hasOne(\app\modules\city\models\City::className(), ['id' => 'city_id']);
    }

}