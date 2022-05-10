<?php

namespace api\modules\api\frontend\v1\controllers;

use yii\rest\Controller;

class AuthController extends Controller {

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'register' => \api\modules\api\frontend\v1\controllers\auth\Register::class,
            'login' => \api\modules\api\frontend\v1\controllers\auth\Login::class,
            'activate' => \api\modules\api\frontend\v1\controllers\auth\Activate::class,
            'forgot-password' => \api\modules\api\frontend\v1\controllers\auth\ForgotPassword::class,
            'reset-password' => \api\modules\api\frontend\v1\controllers\auth\ResetPassword::class,
            'options' => \yii\rest\OptionsAction::class,
            
        ];
    }

    public static function getRoutes() {
        return
            [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => 'f1/auth',
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'extraPatterns' => [
                        'POST register' => 'register',
                        'POST login' => 'login',
                        'POST activate/{id}' => 'activate',
                        'POST forgot-password' => 'forgot-password',
                        'POST reset-password' => 'reset-password',

                        'OPTIONS register' => 'options',
                        'OPTIONS login' => 'options',
                        'OPTIONS activate/{id}' => 'options',
                        'OPTIONS forgot-password' => 'options',
                        'OPTIONS reset-password' => 'options',
                    ],
                ]
            ];
    }
    
}
