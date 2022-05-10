<?php

namespace backend\controllers;

use common\models\artist\Artist;
use common\models\user\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * user controller
 */
class ArtistController extends Controller {

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
                        'roles' => ['ArtistList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['status', 'status-fa', 'status-app', 'status-site', 'bulk-status'],
                        'roles' => ['ArtistDisable'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['ArtistUpdate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['ArtistAdd'],
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
            'add' => \backend\controllers\artist\Add::class,
            'index' => \backend\controllers\artist\Index::class,
            'status' => \backend\controllers\artist\Status::class,
            'status-fa' => \backend\controllers\artist\StatusFa::class,
            'status-app' => \backend\controllers\artist\StatusApp::class,
            'status-site' => \backend\controllers\artist\StatusSite::class,
            'update' => \backend\controllers\artist\Update::class,
        ];
    }

    public function findModel($id) {
        if (($model = Artist::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
