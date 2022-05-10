<?php

namespace api\modules\api\frontend\v1\controllers;

use common\models\music\Music;
use common\models\music\MusicArtist;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class FollowController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['options', 'like'],
        ];
        
        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'index' => \api\modules\api\frontend\v1\controllers\follow\Index::class,
            'type' => \api\modules\api\frontend\v1\controllers\follow\Type::class,
            'options' => \yii\rest\OptionsAction::class,
            
        ];
    }

    public static function getRoutes() {
        return
            [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => 'f1/follow',
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                    ],
                    'extraPatterns' => [
                        'GET type' => 'type',

                        'OPTIONS type' => 'options',
                    ],
                ]
            ];
    }
}
