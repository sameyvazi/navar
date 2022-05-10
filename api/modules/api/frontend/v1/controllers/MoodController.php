<?php

namespace api\modules\api\frontend\v1\controllers;

use common\models\mood\Mood;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class MoodController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['options', 'index', 'list'],
        ];
        
        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'index' => \api\modules\api\frontend\v1\controllers\mood\Index::class,
            'list' => \api\modules\api\frontend\v1\controllers\mood\MoodList::class,
            'options' => \yii\rest\OptionsAction::class,
            
        ];
    }

    public function findModel($id, $type) {

        $status = \Yii::$app->helper->getTypeStatus($type);

        if (($model = Mood::find()->where([
                'id' => $id,
                $status => Mood::STATUS_ACTIVE
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
                    'controller' => 'f1/mood',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'extraPatterns' => [
                        'GET list' => 'list',

                        'OPTIONS list' => 'options'
                    ],
                ]
            ];
    }
}
