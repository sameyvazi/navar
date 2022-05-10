<?php

namespace backend\controllers;

use common\models\artist\Artist;
use common\models\mood\Mood;
use common\models\user\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * mood controller
 */
class MoodController extends Controller {

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
                        'roles' => ['MoodList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['status', 'status-fa', 'status-app', 'bulk-status'],
                        'roles' => ['MoodDisable'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['MoodUpdate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['MoodAdd'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['MoodDelete'],
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
            'add' => \backend\controllers\mood\Add::class,
            'index' => \backend\controllers\mood\Index::class,
            'status' => \backend\controllers\mood\Status::class,
            'status-fa' => \backend\controllers\mood\StatusFa::class,
            'status-app' => \backend\controllers\mood\StatusApp::class,
            'update' => \backend\controllers\mood\Update::class,
            'delete' => \backend\controllers\mood\Delete::class,
        ];
    }

    public function findModel($id) {
        if (($model = Mood::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
