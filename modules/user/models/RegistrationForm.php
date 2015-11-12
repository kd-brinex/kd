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

use dektrium\user\Module;
use dektrium\user\models\RegistrationForm as BaseModel;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RegistrationForm extends BaseModel
{


    /** @var string */
    public $telephone;


    /** @inheritdoc */
    public function rules()
    {
        $rules = [
            ['telephone', 'filter', 'filter' => 'trim'],
            ['telephone', 'match', 'pattern' => '/^[0-9]+$/'],
            ['telephone', 'required'],
            ['telephone', 'unique', 'targetClass' => $this->module->modelMap['User'],
                'message' => \Yii::t('user', 'Этот номер телефона уже используется.')],
            ['telephone', 'string', 'min' => 11, 'max' => 20],
        ];
        return array_merge(parent::rules(), $rules);

    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        $labels = ['telephone' => 'Телефон'];
        return array_merge(parent::attributeLabels(), $labels);
    }


    /**
     * Registers a new user account.
     * @return bool
     */
    public function register()
    {

        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $user = \Yii::createObject(User::className());
        $user->setScenario('register');
        $this->loadAttributes($user);

        if (!$user->register()) {
            return false;
        }

        \Yii::$app->session->setFlash(
            'info',
            \Yii::t('user', 'Your account has been created and a message with further instructions has been sent to your email')
        );

        return true;
    }
}