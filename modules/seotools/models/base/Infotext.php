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
            [['meta_id', 'city_id'], 'integer'],
            [['infotext_before', 'infotext_after'], 'string'],
            [['meta_id', 'city_id'], 'unique', 'targetAttribute' => ['meta_id', 'city_id'], 'message' => 'The combination of Meta ID and City ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('seotools', 'ID'),
            'meta_id' => Yii::t('seotools', 'Meta ID'),
            'city_id' => Yii::t('seotools', 'City ID'),
            'infotext_before' => Yii::t('seotools', 'Text before content'),
            'infotext_after' => Yii::t('seotools', 'Text after content'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeta()
    {
        return $this->hasOne(Meta::className(), ['id_meta' => 'meta_id']);
    }

    public function getCity()
    {
        return $this->hasOne(\app\modules\city\models\City::className(), ['id' => 'city_id']);
    }

}