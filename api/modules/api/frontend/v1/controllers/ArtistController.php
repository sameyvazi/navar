<?php

namespace api\modules\api\frontend\v1\controllers;

use common\models\artist\Artist;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class ArtistController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['options', 'like', 'index', 'view', 'activity', 'music', 'page', 'liked'],
        ];
        
        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'index' => \api\modules\api\frontend\v1\controllers\artist\Index::class,
            'view' => \api\modules\api\frontend\v1\controllers\artist\View::class,
            'activity' => \api\modules\api\frontend\v1\controllers\artist\Activity::class,
            'music' => \api\modules\api\frontend\v1\controllers\artist\Music::class,
            'like' => \api\modules\api\frontend\v1\controllers\artist\Like::class,
            'liked' => \api\modules\api\frontend\v1\controllers\artist\Liked::class,
            'page' => \api\modules\api\frontend\v1\controllers\artist\Page::class,
            'follow' => \api\modules\api\frontend\v1\controllers\artist\Follow::class,
            'options' => \yii\rest\OptionsAction::class,
            
        ];
    }

    public function findModel($id, $type) {

        $status = \Yii::$app->helper->getTypeStatus($type);

        if (($model = Artist::find()->where([
                'id' => $id,
                $status => Artist::STATUS_ACTIVE
            ])->orWhere([
                'key' => $id,
                $status => Artist::STATUS_ACTIVE
            ])->one()) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public static function getRoutes() {
        return
            [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => 'f1/artist',
                    'tokens' => [
                        '{id}' => '<id:.*>',
                        '{act}' => '<act:\\w+>'
                    ],
                    'extraPatterns' => [
                        'GET activity' => 'activity',
                        'GET music' => 'music',
                        'GET like/{id}' => 'like',
                        'GET liked/{id}' => 'liked',
                        'GET {id}/page' => 'page',
                        'GET follow/{id}' => 'follow',

                        'OPTIONS activity' => 'options',
                        'OPTIONS music' => 'options',
                        'OPTIONS like/{id}' => 'options',
                        'OPTIONS liked/{id}' => 'options',
                        'OPTIONS {id}/page' => 'options',
                        'OPTIONS follow/{id}' => 'options',
                    ],
                ]
            ];
    }
}
