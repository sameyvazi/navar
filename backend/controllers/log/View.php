<?php

namespace backend\controllers\log;

use Yii;
use yii\base\Action;


class View extends Action {

    public function run($id) {

        $model = $this->controller->findModel($id);
        
        return Yii::$app->request->isAjax ?
                $this->controller->renderAjax('view', [
                    'model' => $model,
                ]) :
                $this->controller->render('view', [
                    'model' => $model,
        ]);
    }

}
