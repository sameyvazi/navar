<?php

namespace backend\controllers\user;

use backend\models\user\UpdateForm;
use Yii;
use yii\base\Action;

class Update extends Action {

    public function run($id) {

        $model = $this->controller->findModel($id);

        $updateModel = new UpdateForm();

        if ($updateModel->load(Yii::$app->request->post()) && $updateModel->save($model)) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'User successfuly updated.'));
        }
        else
        {

            $updateModel->setAttributes($model->getAttributes());
        }
        
        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('manage', [
                'model' => $updateModel,
            ]) :
            $this->controller->render('manage', [
                'model' => $updateModel,
        ]);
    }

}
