<?php

namespace backend\controllers;

use common\models\artist\Artist;
use common\models\special\Special;
use common\models\user\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * special controller
 */
class SpecialController extends Controller {

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
                        'roles' => ['SpecialList'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['SpecialUpdate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => ['SpecialAdd'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['SpecialDelete'],
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
            'add' => \backend\controllers\special\Add::class,
            'index' => \backend\controllers\special\Index::class,
            'update' => \backend\controllers\special\Update::class,
            'delete' => \backend\controllers\special\Delete::class,
        ];
    }

    public function findModel($id) {
        if (($model = Special::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
