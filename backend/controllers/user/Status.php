<?php

namespace backend\controllers\user;

use common\models\user\User;
use Yii;
use yii\base\Action;
use yii\web\Response;

class Status extends Action {

    public function run($id) {

        
        $output = [];
        
        $model = $this->controller->findModel($id);

        if ($model->status == User::STATUS_ACTIVE) {
            $model->status = User::STATUS_DISABLED;
        } else {
            $model->status = User::STATUS_ACTIVE;
        }

        if ($model->save()) {

            $output = [
                'error' => false,
                'message' => Yii::t('app', 'Successfully status changed!'),
            ];
        }

        if (empty($output)) {
            $output = [
                'error' => true,
                'message' => Yii::t('app', 'The requested page does not exist.'),
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

}
