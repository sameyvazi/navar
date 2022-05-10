<?php

namespace backend\controllers\admin;

use Yii;
use yii\base\Action;
use backend\models\admin\UpdateForm;

class Update extends Action {

    public function run($id) {

        $admin = $this->controller->findModel($id);
        
        $updateModel = new UpdateForm();
        
        if ($updateModel->load(Yii::$app->request->post())) {
            if($updateModel->update($admin))
            {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Admin information updated.'));
            }
            else
            {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error in updating admin information!'));
            }
        }
        else
        {
            $updateModel->setAttributes($admin->setAttributes());
        }
        
        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('update', [
                'admin' => $admin,
                'updateModel' => $updateModel,
            ]) :
            $this->controller->render('update', [
                'admin' => $admin,
                'updateModel' => $updateModel,
        ]);
    }

}
