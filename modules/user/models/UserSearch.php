<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user\models;

use dektrium\user\models\UserSearch as BaseModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends BaseModel
{
    /** @var string */
    public $telephone;

    /** @var string */
    public $user_id_1c;


    /** @inheritdoc */
    public function rules()
    {
        return [
            [['username', 'email', 'telephone', 'registration_ip', 'created_at','user_id_1c'], 'safe'],
            ['created_at', 'default', 'value' => null]
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return
            array_merge(parent::attributeLabels(),
                [
                    'telephone' => \Yii::t('user', 'Telephone'),
                    'user_id_1c' => \Yii::t('user', 'UserId1c'),
                ]);
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->finder->getUserQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['user_id_1c'=> $this->user_id_1c])
            ->andFilterWhere(['registration_ip' => $this->registration_ip]);

        return $dataProvider;
    }
}
