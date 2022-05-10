<?php

namespace backend\controllers\admin;

use Yii;
use yii\base\Action;
use common\models\admin\form\RegisterForm;

class Add extends Action {

    public function run() {

        $model = new RegisterForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Admin successfuly created.'));
        }
        
        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('add', [
                'model' => $model,
            ]) :
            $this->controller->render('add', [
                'model' => $model,
        ]);
    }

}
