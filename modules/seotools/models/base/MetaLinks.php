<?php

namespace app\modules\seotools\models\base;

use Yii;

/**
 * This is the model class for table "{{%meta_links}}".
 *
 * @property string $keyword
 * @property string $link
 * @property integer $seq_number
 * @property integer $meta_id
 */
class MetaLinks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%meta_links}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword', 'link'], 'required'],
            [['keyword', 'link'], 'string', 'max' => 255],
            [['seq_number','meta_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'keyword' => Yii::t('seotools', 'Keyword'),
            'link' => Yii::t('seotools', 'Link'),
            'seq_number' => Yii::t('seotools', 'Sequence number'),
            'meta_id' => Yii::t('seotools', 'ID meta'),
        ];
    }

    public function getMetaBase()
    {
        return $this->hasOne(\app\modules\seotools\models\base\MetaBase::className(), array('id_meta' => 'meta_id'));
    }
}
