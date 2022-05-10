<?php

namespace backend\controllers;

use common\models\user\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * user controller
 */
class UserController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['UserList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['status', 'bulk-status'],
                        'roles' => ['UserDisable'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['UserUpdate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['UserAdd'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'index' => \backend\controllers\user\Index::class,
            'view' => \backend\controllers\user\View::class,
            'status' => \backend\controllers\user\Status::class,
            'bulk-status' => \backend\controllers\user\BulkStatus::class,
            'update' => \backend\controllers\user\Update::class,
        ];
    }

    public function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
