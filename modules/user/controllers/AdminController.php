<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user\controllers;

use dektrium\user\controllers\AdminController as BaseController;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use yii\helpers\Url;
use Yii;
/**
 * AdminController allows you to administrate users.
 *
 * @property Module $module
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class AdminController extends BaseController
{
    /** @var Finder */
    protected $finder;

    /**
     * @param string $id
     * @param Module2 $module
     * @param Finder $finder
     * @param array $config
     */
    public function actionUpdate($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        $user->scenario = 'update';

        $this->performAjaxValidation($user);
//var_dump(Yii::$app->request->post());die;
        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Account details have been updated'));
            return $this->refresh();
        }

        return $this->render('_account', [
            'user'    => $user,
        ]);
    }
    

}
