<?php

namespace backend\controllers\admin;

use Yii;
use yii\base\Action;
use backend\models\admin\ChangePasswordForm;

class ChangePassword extends Action {

    public function run() {
        
        $updateModel = new ChangePasswordForm();
        
        if ($updateModel->load(Yii::$app->request->post()) && $updateModel->changePassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Your password successfuly changed.'));
            $updateModel = new ChangePasswordForm();
        }

        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('change-password', [
                'updateModel' => $updateModel,
            ]) :
            $this->controller->render('change-password', [
                'updateModel' => $updateModel,
        ]);
    }

}
