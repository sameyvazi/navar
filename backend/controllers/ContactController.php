<?php

namespace backend\controllers;


use common\models\contact\Contact;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * comment controller
 */
class ContactController extends Controller {

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
            'index' => \backend\controllers\contact\Index::class,
            'status' => \backend\controllers\contact\Status::class,
        ];
    }

    public function findModel($id) {
        if (($model = Contact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
