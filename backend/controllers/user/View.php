<?php

namespace backend\controllers\user;

use Yii;
use yii\base\Action;

class View extends Action {

    public function run($id) {

        $application = $this->controller->findModel($id);
        
        return Yii::$app->getRequest()->isAjax ?
                $this->controller->renderAjax('view', [
                    'model' => $application,
                ])
                :
                $this->controller->render('view', [
                    'model' => $application,
                ]);
    }

}
