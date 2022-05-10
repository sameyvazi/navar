<?php

namespace backend\controllers\main;

use Yii;
use yii\base\Action;
use yii\web\Response;

class ClearThumbnails extends Action {

    public function run() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'result' => Yii::$app->helper->removeFilesOfDirectory(Yii::getAlias('@static/images-cache/*'))
        ];
    }

}
