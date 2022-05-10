<?php

namespace backend\controllers\admin;

use Yii;
use yii\base\Action;
use common\models\admin\Admin;
use yii\web\Response;

class BulkStatus extends Action {

    public function run() {

        $count = 0;
        $ids = Yii::$app->getRequest()->post('ids', []);
        $state = Yii::$app->getRequest()->post('data', Admin::STATUS_DISABLED);
        $changedIds = [];
        foreach ($ids as $id) {
            if (!Yii::$app->getAuthManager()->checkAccess($id, 'Administrator')) {
                
                Admin::updateAll(
                        [
                            'status' => $state
                        ],
                        [
                            'id' => $id
                        ]);
                
                $count++;
                $changedIds[] = $id;
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'error' => $count < 1,
            'message' => $count ? Yii::t('app', 'Successfully {count} item status changed!', ['count' => $count]) : Yii::t('app', 'The requested page does not exist.'),
        ];
    }

}
