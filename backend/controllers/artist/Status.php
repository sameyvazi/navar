<?php

namespace backend\controllers\artist;

use common\models\artist\Artist;
use Yii;
use yii\base\Action;
use yii\web\Response;

class Status extends Action {

    public function run($id) {

        $output = [];
        
        $model = $this->controller->findModel($id);

        if (Yii::$app->getAuthManager()->checkAccess($id, 'Administrator')) {
            $output = [
                'error' => true,
                'message' => Yii::t('app', "You haven't enough permission to disable this artist!"),
            ];
        }
        else
        {

            if ($model->status == Artist::STATUS_ACTIVE) {
                $model->status = Artist::STATUS_DISABLED;
            } else {
                $model->status = Artist::STATUS_ACTIVE;
            }

            if ($model->save()) {
                $output = [
                    'error' => false,
                    'message' => Yii::t('app', 'Successfully status changed!'),
                ];
            }
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
