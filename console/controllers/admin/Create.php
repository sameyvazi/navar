<?php

namespace console\controllers\admin;

use yii\helpers\Console;
use yii\base\Action;
use common\models\admin\form\RegisterForm;
use Yii;

class Create extends Action {

    public function run() {
        
        Yii::$app->language = 'en';
        $user = new RegisterForm();
        $user->username = $this->controller->prompt('Please enter username:', [
            'required' => true,
        ]);
        $user->password = $this->controller->prompt('Please enter password:', [
            'required' => true,
        ]);
        $user->confirmPassword = $user->password;
        if ($user->register())
        {
            $this->controller->stdout("User successfully created.\n", Console::FG_GREEN);
        }
        else
        {
            print_r($user->getErrors());
        }
        
        return 0;
        
    }

}
