<?php

namespace api\modules\api\frontend\v1\controllers;

use common\models\artist\Artist;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class SpecialController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['options', 'index'],
        ];
        
        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'index' => \api\modules\api\frontend\v1\controllers\special\Index::class,
            'options' => \yii\rest\OptionsAction::class,
            
        ];
    }

    public static function getRoutes() {
        return
            [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => 'f1/special',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'extraPatterns' => [

                    ],
                ]
            ];
    }
}
