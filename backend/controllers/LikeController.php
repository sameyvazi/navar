<?php

namespace backend\controllers;

use common\models\artist\Artist;
use common\models\comment\Comment;
use common\models\like\Like;
use common\models\user\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * like controller
 */
class LikeController extends Controller {

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
                        'actions' => ['index'],
                        'roles' => ['LikeList'],
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
            'index' => \backend\controllers\like\Index::class,
        ];
    }

    public function findModel($id) {
        if (($model = Like::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
