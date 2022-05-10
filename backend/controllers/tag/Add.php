<?php

namespace backend\controllers\tag;

use common\models\tag\Tag;
use Yii;
use yii\base\Action;

class Add extends Action {

    public function run() {

        $model = new Tag();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Tag successfuly created.'));
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
