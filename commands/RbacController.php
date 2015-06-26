<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 18.05.15
 * Time: 9:38
 */
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\rbac\GroupRule;
use yii\rbac\DbManager;

class RbacController extends Controller
{
    public function actionInit($id = null)
    {
        $auth =new DbManager;
//        var_dump($auth);die;
        $auth->init();

        $auth->removeAll(); //удаляем старые данные
        // Rules
        $groupRule = new GroupRule();

        $auth->add($groupRule);

        // Roles
        $user = $auth->createRole('user');
        $user->description = 'User';
        $user->ruleName = $groupRule->name;
        $auth->add($user);

        $moderator = $auth->createRole('manager');
        $moderator ->description = 'Moderator ';
        $moderator ->ruleName = $groupRule->name;
        $auth->add($moderator);
        $auth->addChild($moderator, $user);

        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $admin->ruleName = $groupRule->name;
        $auth->add($admin);
        $auth->addChild($admin, $moderator);

        $superadmin = $auth->createRole('superadmin');
        $superadmin->description = 'Superadmin';
        $superadmin->ruleName = $groupRule->name;
        $auth->add($superadmin);
        $auth->addChild($superadmin, $admin);

        // Superadmin assignments
        if ($id !== null) {
            $auth->assign($superadmin, $id);
        }
    }
}