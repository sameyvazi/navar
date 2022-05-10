<?php

namespace backend\controllers\main;

use Yii;
use yii\base\Action;
use yii\web\Response;
use backend\models\log\Log;

class ClearLog extends Action {

    public function run() {

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'result' => Log::deleteAll()
        ];
    }

}
