<?php

namespace backend\controllers;

use common\models\playlist\PlaylistMusic;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * playlist music controller
 */
class PlaylistMusicController extends Controller {

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
                        'roles' => ['PlaylistMusicList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['status', 'status-fa', 'status-app', 'bulk-status'],
                        'roles' => ['PlaylistMusicDisable'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['PlaylistMusicUpdate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['PlaylistMusicAdd'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['PlaylistMusicDelete'],
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
            'add' => \backend\controllers\playlistMusic\Add::class,
            'index' => \backend\controllers\playlistMusic\Index::class,
            'update' => \backend\controllers\playlistMusic\Update::class,
            'delete' => \backend\controllers\playlistMusic\Delete::class,
        ];
    }

    public function findModel($id) {
        if (($model = PlaylistMusic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
