<?php

namespace backend\controllers;

use common\models\music\Music;
use common\models\music\MusicArtist;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * user controller
 */
class MusicController extends Controller {

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
                        'roles' => ['MusicList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['status', 'status-fa', 'status-app', 'status-site', 'bulk-status', 'status-zizz'],
                        'roles' => ['MusicDisable'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'zip', 'alert'],
                        'roles' => ['MusicUpdate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['MusicAdd'],
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
            'add' => \backend\controllers\music\Add::class,
            'index' => \backend\controllers\music\Index::class,
            'update' => \backend\controllers\music\Update::class,
            'zip' => \backend\controllers\music\Zip::class,
            'status-zizz' => \backend\controllers\music\StatusZizz::class,
            'status-site' => \backend\controllers\music\StatusSite::class,
            'status-app' => \backend\controllers\music\StatusApp::class,
            'status-fa' => \backend\controllers\music\StatusFa::class,
            'status' => \backend\controllers\music\Status::class,
            'view' => \backend\controllers\music\View::class,
            'alert' => \backend\controllers\music\Alert::class,
        ];
    }

    public function findModel($id) {
        if (($model = Music::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function findModelArtists($id) {
        if (($modelArtists = MusicArtist::find()->where(['music_id' => $id])->all()) !== null) {
            return $modelArtists;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
