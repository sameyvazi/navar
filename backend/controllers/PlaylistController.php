<?php

namespace backend\controllers;

use common\models\playlist\Playlist;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * playlist controller
 */
class PlaylistController extends Controller {

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
                        'roles' => ['PlaylistList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['status', 'status-fa', 'status-app', 'bulk-status'],
                        'roles' => ['PlaylistDisable'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['PlaylistUpdate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['PlaylistAdd'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['PlaylistDelete'],
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
            'add' => \backend\controllers\playlist\Add::class,
            'index' => \backend\controllers\playlist\Index::class,
            'status' => \backend\controllers\playlist\Status::class,
            'status-fa' => \backend\controllers\playlist\StatusFa::class,
            'status-app' => \backend\controllers\playlist\StatusApp::class,
            'update' => \backend\controllers\playlist\Update::class,
            'delete' => \backend\controllers\playlist\Delete::class,
        ];
    }

    public function findModel($id) {
        if (($model = Playlist::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
