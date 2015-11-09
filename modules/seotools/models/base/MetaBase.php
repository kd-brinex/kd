<?php

namespace app\modules\seotools\models\base;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "meta".
 *
 * @property integer $id_meta
 * @property string $hash
 * @property string $route

 * @property string $robots_index
 * @property string $robots_follow
 * @property string $author
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $info
 * @property string $h1_title
 * @property string $infotext_after
 * @property string $infotext_before
 * @property integer $sitemap
 * @property string $sitemap_change_freq
 * @property string $sitemap_priority
 * @property string $created_at
 * @property string $updated_at
 */
class MetaBase extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hash', 'route', 'created_at', 'updated_at'], 'required'],
            [['robots_index', 'robots_follow', 'keywords', 'description', 'info','h1_title', 'infotext_before', 'infotext_after'], 'string'],
            [['sitemap'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['hash', 'route', 'author', 'title'], 'string', 'max' => 255],
            [['sitemap_change_freq'], 'string', 'max' => 20],
            [['sitemap_priority'], 'string', 'max' => 4],
            [['hash'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_meta' => 'Id Meta',
            'hash' => 'Hash',
            'route' => 'Route',
            'robots_index' => 'Robots Index',
            'robots_follow' => 'Robots Follow',
            'author' => 'Author',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'sitemap' => 'Sitemap',
            'sitemap_change_freq' => 'Sitemap Change Freq',
            'sitemap_priority' => 'Sitemap Priority',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'h1_title' => 'H1 title',
            'infotext_before' => Yii::t('seotools', 'Text before content'),
            'infotext_after' => Yii::t('seotools', 'Text after content'),
        ];
    }

    public function getInfotext()
    {
        return $this->hasMany(\app\modules\seotools\models\base\Infotext::className(), array('meta_id' => 'id_meta'));
    }

}
