<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;
use yii\helpers\Console;
use common\models\admin\Admin;

class RbacController extends Controller {

    public function actionBackend() {
        
        Yii::$app->language = 'en';
        
        $username = $this->prompt('Please enter username for assigning the roles for that user:', [
            'required' => true
        ]);
        
        if (!$user = Admin::findOne(['username' => $username]))
        {
            $this->stdout("Entered username is not exist\n", Console::FG_RED);
            return 1;
        }
        
        $roles = include (Yii::getAlias('@console/storage/rbac/backend.php'));
        
        $auth = Yii::$app->getAuthManager();
        if (!$administrator = $auth->getRole('Administrator'))
        {
            $administrator = $auth->createRole('Administrator');
            $auth->add($administrator);
            $this->stdout("Administrator role added\n", Console::FG_YELLOW);
        }
        
        foreach($roles as $roleName => $permissions)
        {
            if (!$role = $auth->getRole($roleName))
            {
                $this->stdout("{$roleName} role added\n", Console::FG_YELLOW);
                $role = $auth->createRole($roleName);
                $auth->add($role);
                $auth->addChild($administrator, $role);
            }
            
            foreach($permissions as $permission)
            {
                if (!$per = $auth->getPermission($permission))
                {
                    $this->stdout("{$permission} permission added\n", Console::FG_BLUE);
                    $per = $auth->createPermission($permission);
                    $auth->add($per);
                    $auth->addChild($role, $per);
                }
            }
        }
        if(!$auth->checkAccess($user->id, 'Administrator'))
        {
            $auth->assign($administrator, $user->id);
            $this->stdout("Administrator role assigned to user\n", Console::FG_GREEN);
        }
        
        $this->stdout("Finished\n", Console::FG_GREEN);
        
        return 0;
        
    }

}
