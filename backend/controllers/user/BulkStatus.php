<?php

namespace backend\controllers\user;

use common\models\user\User;
use Yii;
use yii\base\Action;
use yii\web\Response;

class BulkStatus extends Action {

    public function run() {

        $ids = Yii::$app->getRequest()->post('ids', []);
        $state = Yii::$app->getRequest()->post('data', User::STATUS_DISABLED);

        $count = User::updateAll(
            [
                'status' => $state
            ],
            [
                'id' => $ids
            ]);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'error' => $count < 1,
            'message' => $count ? Yii::t('app', 'Successfully {count} item status changed!', ['count' => $count]) : Yii::t('app', 'The requested page does not exist.'),
        ];
    }

}
