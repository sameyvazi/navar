<?php

namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Main controller
 */
class MainController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['clear-cache'],
                        'roles' => ['ClearCache'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['clear-assets'],
                        'roles' => ['ClearWebAssets'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['clear-thumbnails'],
                        'roles' => ['ClearCacheImage'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['clear-log'],
                        'roles' => ['ClearLog'],
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
            'error' => \yii\web\ErrorAction::class,
            'clear-cache' => \backend\controllers\main\ClearCache::class,
            'clear-assets' => \backend\controllers\main\ClearAssets::class,
            'clear-thumbnails' => \backend\controllers\main\ClearThumbnails::class,
            'clear-log' => \backend\controllers\main\ClearLog::class
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

}
