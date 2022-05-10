<?php

namespace api\modules\api\frontend\v1\controllers;

use common\models\music\Music;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class SearchController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['options', 'index', 'top', 'all', 'tag-count', 'tag', 'list'],
        ];
        
        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'index' => \api\modules\api\frontend\v1\controllers\search\Index::class,
            'top' => \api\modules\api\frontend\v1\controllers\search\Top::class,
            'all' => \api\modules\api\frontend\v1\controllers\search\All::class,
            'tag-count' => \api\modules\api\frontend\v1\controllers\search\Count::class,
            'tag' => \api\modules\api\frontend\v1\controllers\search\Tag::class,
            'list' => \api\modules\api\frontend\v1\controllers\search\Lists::class,
            'options' => \yii\rest\OptionsAction::class,
            
        ];
    }

    public static function getRoutes() {
        return
            [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => 'f1/search',
                    'tokens' => [
                        '{id}' => '<id:.*>',
                    ],
                    'extraPatterns' => [
                        'GET type' => 'type',
                        'GET top' => 'top',
                        'GET all/{id}' => 'all',
                        'GET tag-count' => 'tag-count',
                        'GET music/{id}' => 'music',
                        'GET artist/{id}' => 'artist',
                        'GET tag/{id}' => 'tag',
                        'GET list' => 'list',

                        'OPTIONS type' => 'options',
                        'OPTIONS top' => 'options',
                        'OPTIONS all/{id}' => 'options',
                        'OPTIONS tag-count' => 'options',
                        'OPTIONS music/{id}' => 'options',
                        'OPTIONS artist/{id}' => 'options',
                        'OPTIONS tag/{id}' => 'options',
                        'OPTIONS list' => 'options',
                    ],
                ]
            ];
    }
}
