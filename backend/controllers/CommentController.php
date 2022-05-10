<?php

namespace backend\controllers;

use common\models\artist\Artist;
use common\models\comment\Comment;
use common\models\user\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * comment controller
 */
class CommentController extends Controller {

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
                        'roles' => ['CommentList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['status', 'bulk-status'],
                        'roles' => ['CommentDisable'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['CommentAdd'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['CommentDelete'],
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
            'index' => \backend\controllers\comment\Index::class,
            'status' => \backend\controllers\comment\Status::class,
            'delete' => \backend\controllers\comment\Delete::class,
        ];
    }

    public function findModel($id) {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
