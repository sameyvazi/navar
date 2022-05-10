<?php

namespace api\modules\api\frontend\v1\controllers;

use common\models\follower\Follower;
use common\models\mood\Mood;
use common\models\playlist\Playlist;
use common\models\playlist\PlaylistMusic;
use common\models\user\User;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class PlaylistController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['options', 'index', 'view', 'java'],
        ];
        
        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'index' => \api\modules\api\frontend\v1\controllers\playlist\Index::class,
            'view' => \api\modules\api\frontend\v1\controllers\playlist\View::class,
            'create' => \api\modules\api\frontend\v1\controllers\playlist\Create::class,
            'update' => \api\modules\api\frontend\v1\controllers\playlist\Update::class,
            'delete' => \api\modules\api\frontend\v1\controllers\playlist\Delete::class,
            'add' => \api\modules\api\frontend\v1\controllers\playlist\AddMusic::class,
            'save' => \api\modules\api\frontend\v1\controllers\playlist\Save::class,
            'user-playlist' => \api\modules\api\frontend\v1\controllers\playlist\UserPlaylist::class,
            'follow' => \api\modules\api\frontend\v1\controllers\playlist\Follow::class,
            'java' => \api\modules\api\frontend\v1\controllers\playlist\Java::class,
            'options' => \yii\rest\OptionsAction::class,
            
        ];
    }

    public function findModel($id, $type) {

        $status = \Yii::$app->helper->getTypeStatus($type);

        $playlist = Playlist::find()->where(['id' => $id, $status => Playlist::STATUS_ACTIVE])->one();

        $follower = null;
        $authHeader = \Yii::$app->request->headers->get('Authorization');
        if ($authHeader){
            preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches);
            $identity = User::findIdentityByAccessToken($matches[1]);
            $follower = Follower::find()->where(['post_id' => $id, 'user_id' => $identity ? $identity->id : false, 'post_type' => Follower::TYPE_PLAYLIST])->one();
        }

        if (($model = PlaylistMusic::find()->where([
                'playlist_id' => $id,
            ])->orderBy(['no' => SORT_ASC])->all()) !== null && $playlist !== null) {
            return [
                'model' =>$model,
                'follow' => !is_null($follower) ? true : false,
                'follow_count' => Follower::find()->where(['post_id' => $id, 'post_type' => Follower::TYPE_PLAYLIST])->count()
                ];

        } else {
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public static function getRoutes() {
        return
            [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => 'f1/playlist',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'extraPatterns' => [
                        'POST add' => 'add',
                        'POST save' => 'save',
                        'GET user-playlist' => 'user-playlist',
                        'GET follow/{id}' => 'follow',
                        'GET java/{id}' => 'java',

                        'OPTIONS add' => 'options',
                        'OPTIONS save' => 'options',
                        'OPTIONS user-playlist' => 'options',
                        'OPTIONS follow/{id}' => 'options',
                        'OPTIONS java/{id}' => 'options',
                    ],
                ]
            ];
    }
}
