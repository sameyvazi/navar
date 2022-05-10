<?php

namespace backend\controllers;

use common\models\admin\Admin;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Admin controller
 */
class AdminController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['user-list', 'change-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['AdminUserList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['status', 'bulk-status'],
                        'roles' => ['AdminUserDisable'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['AdminUserUpdate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['AdminUserAdd'],
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
            'index' => \backend\controllers\admin\Index::class,
            'admin-list' => \backend\controllers\admin\AdminList::class,
            'status' => \backend\controllers\admin\Status::class,
            'bulk-status' => \backend\controllers\admin\BulkStatus::class,
            'update' => \backend\controllers\admin\Update::class,
            'add' => \backend\controllers\admin\Add::class,
            'change-password' => \backend\controllers\admin\ChangePassword::class
        ];
    }

    public function findModel($id) {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
