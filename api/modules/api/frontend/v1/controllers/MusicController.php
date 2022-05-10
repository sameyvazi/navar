<?php

namespace api\modules\api\frontend\v1\controllers;

use common\models\music\Music;
use common\models\music\MusicArtist;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class MusicController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {

        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['options', 'like', 'type', 'index', 'view', 'liked', 'counter', 'rand'],
        ];

        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'index' => \api\modules\api\frontend\v1\controllers\music\Index::class,
            'view' => \api\modules\api\frontend\v1\controllers\music\View::class,
            'type' => \api\modules\api\frontend\v1\controllers\music\Type::class,
            'like' => \api\modules\api\frontend\v1\controllers\music\Like::class,
            'liked' => \api\modules\api\frontend\v1\controllers\music\Liked::class,
            'follow' => \api\modules\api\frontend\v1\controllers\music\Follow::class,
            'counter' => \api\modules\api\frontend\v1\controllers\music\Counter::class,
            'rand' => \api\modules\api\frontend\v1\controllers\music\Rand::class,
            'options' => \yii\rest\OptionsAction::class,
            
        ];
    }

    public function findModel($id, $type) {

        $status = \Yii::$app->helper->getTypeStatus($type);

        if (is_numeric($id)){
            $model = Music::find()->where([
                'id' => $id,
                $status => Music::STATUS_ACTIVE
            ])->one();
        }else{
            $model = Music::find()->where([
                'key_pure' => $id,
                $status => Music::STATUS_ACTIVE
            ])->one();
        }

        if (($model) !== null) {

            $view = \Yii::$app->helper->getTypeView($type);

            $model->$view++;
            $model->save();

            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function findModelArtists($id) {
        if (($modelArtists = MusicArtist::find()->where(['music_id' => $id])->all()) !== null) {
            return $modelArtists;
        } else {
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public static function getRoutes() {
        return
            [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => 'f1/music',
                    'tokens' => [
                        //'{id}' => '<id:\\w+>',
                        '{id}' => '<id:.*>',
                    ],
                    'extraPatterns' => [
                        'GET type' => 'type',
                        'GET like/{id}' => 'like',
                        'GET liked/{id}' => 'liked',
                        'GET follow/{id}' => 'follow',
                        'GET counter/{id}' => 'counter',
                        'GET rand' => 'rand',

                        'OPTIONS type' => 'options',
                        'OPTIONS like/{id}' => 'options',
                        'OPTIONS liked/{id}' => 'options',
                        'OPTIONS follow/{id}' => 'options',
                        'OPTIONS counter/{id}' => 'options',
                        'OPTIONS rand' => 'options',
                    ],
                ]
            ];
    }
}
