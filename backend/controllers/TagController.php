<?php

namespace backend\controllers;

use common\models\artist\Artist;
use common\models\tag\Tag;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * user controller
 */
class TagController extends Controller {

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
                        'actions' => ['status', 'bulk-status'],
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
            'add' => \backend\controllers\tag\Add::class,
            'index' => \backend\controllers\tag\Index::class,
            'update' => \backend\controllers\tag\Update::class,
        ];
    }

    public function findModel($id) {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
