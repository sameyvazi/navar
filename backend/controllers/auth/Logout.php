<?php

namespace backend\controllers\auth;

use yii\base\Action;
use Yii;

class Logout extends Action {
    
    public function run()
    {
        Yii::$app->user->logout();

        return $this->controller->goHome();
    }
    
}
