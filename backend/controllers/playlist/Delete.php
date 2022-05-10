<?php

namespace backend\controllers\playlist;

use Yii;
use yii\base\Action;
use yii\web\Response;

class Delete extends Action {

    public function run($id) {
        
        $model = $this->controller->findModel($id);
        $result = $model->delete();
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'error' => $result < 1,
            'message' => $result ? Yii::t('app', 'Successfully deleted!') : Yii::t('app', 'The requested page does not exist.'),
        ];
    }

}
