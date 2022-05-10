<?php

namespace backend\controllers\auth;

use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;
use backend\models\admin\LoginForm;
use Yii;

class Login extends Action {

    public function run() {
        
        $this->controller->layout = 'login';
        
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->controller->goBack();
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        return $this->controller->render('login', [
            'model' => $model,
        ]);
    }

}
