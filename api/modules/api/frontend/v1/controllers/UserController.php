<?php

namespace api\modules\api\frontend\v1\controllers;

use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class UserController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['options'],
        ];
        
        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'update' => \api\modules\api\frontend\v1\controllers\user\Update::class,
            'view' => \api\modules\api\frontend\v1\controllers\user\View::class,
            'avatar' => \api\modules\api\frontend\v1\controllers\user\Avatar::class,
            'avatar-delete' => \api\modules\api\frontend\v1\controllers\user\AvatarDelete::class,
            'options' => \yii\rest\OptionsAction::class,
            
        ];
    }

    public static function getRoutes() {
        return
            [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => 'f1/user',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'extraPatterns' => [
                        'PUT' => 'update',
                        'GET' => 'view',
                        'POST avatar' => 'avatar',
                        'DELETE avatar' => 'avatar-delete',
                        
                        'OPTIONS avatar' => 'options',
                    ],
                ]
            ];
    }
}
