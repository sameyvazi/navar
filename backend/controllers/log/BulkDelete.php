<?php

namespace backend\controllers\log;

use Yii;
use yii\base\Action;
use backend\models\log\Log;
use yii\web\Response;

class BulkDelete extends Action {

    public function run() {
        
        $result = 0;
        
        if ($ids = Yii::$app->getRequest()->post('ids', false))
        {
            $result = Log::deleteAll(['_id' => $ids]);
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'error' => $result < 1,
            'message' => $result ? Yii::t('app', 'Successfully deleted {count} item!', ['count' => $result]) : Yii::t('app', 'The requested page does not exist.'),
        ];
    }

}
