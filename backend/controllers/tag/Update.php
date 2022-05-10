<?php

namespace backend\controllers\tag;

use backend\models\artist\UpdateForm;
use Yii;
use yii\base\Action;

class Update extends Action {

    public function run($id) {

        $model = $this->controller->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'User successfuly updated.'));
        }
        else
        {

            $model->setAttributes($model->getAttributes());
        }

        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('manage', [
                'model' => $model,
            ]) :
            $this->controller->render('manage', [
                'model' => $model,
            ]);
    }

}
