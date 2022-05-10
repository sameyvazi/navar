<?php

namespace backend\controllers\main;

use Yii;
use yii\base\Action;
use yii\web\Response;

class ClearAssets extends Action {

    public function run() {
        
        $result = Yii::$app->helper->removeDirectories(Yii::getAlias('@static/backend/assets')) &&
            Yii::$app->helper->removeDirectories(Yii::getAlias('@static/frontend/assets'));
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'result' => $result
        ];
    }

}
