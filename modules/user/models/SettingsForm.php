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

use dektrium\user\models\SettingsForm as BaseModel;
use dektrium\user\Mailer;


/**
 * SettingsForm gets user's username, email and password and changes them.
 *
 * @property User $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsForm extends BaseModel
{
    /** @var string */
    public $telephone;
    public function __construct(Mailer $mailer, $config = [])
    {
        $this->module = \Yii::$app->getModule('user');
        $this->setAttributes([
            'telephone' => $this->user->telephone
        ], false);
        parent::__construct($mailer,$config);
    }
    /** @inheritdoc */
    public function rules()
    {
        $rules = parent::rules();
//        var_dump($rules);die;
        $telephone=[
            [['telephone'], 'required'],
            ['telephone', 'filter', 'filter' => 'trim'],
            ['telephone', 'match', 'pattern' => '/^[0-9]+$/'],

//            [['telephone'], 'unique', 'when' => function ($model, $attribute) {
//                return $this->user->$attribute != $model->$attribute;
//            }, 'targetClass' => $this->module->modelMap['User']],
        ];
        $rules=array_merge($rules,$telephone);
        return $rules;
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email'            => \Yii::t('user', 'Email'),
            'username'         => \Yii::t('user', 'Login'),
            'new_password'     => \Yii::t('user', 'New password'),
            'current_password' => \Yii::t('user', 'Current password'),
            'telephone'        => \Yii::t('user', 'Telephone'),

        ];
    }



    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $this->user->scenario = 'settings';
            $this->user->username = $this->username;
            $this->user->telephone = $this->telephone;
            $this->user->password = $this->new_password;
            if ($this->email == $this->user->email && $this->user->unconfirmed_email != null) {
                $this->user->unconfirmed_email = null;
            } else if ($this->email != $this->user->email) {
                switch ($this->module->emailChangeStrategy) {
                    case Module::STRATEGY_INSECURE:
                        $this->insecureEmailChange(); break;
                    case Module::STRATEGY_DEFAULT:
                        $this->defaultEmailChange(); break;
                    case Module::STRATEGY_SECURE:
                        $this->secureEmailChange(); break;
                    default:
                        throw new \OutOfBoundsException('Invalid email changing strategy');
                }
            }
            return $this->user->save();
        }

        return false;
    }


}
