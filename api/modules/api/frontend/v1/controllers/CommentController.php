<?php

namespace api\modules\api\frontend\v1\controllers;

use common\models\comment\Comment;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class CommentController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['options', 'create', 'view'],
        ];
        
        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'create' => \api\modules\api\frontend\v1\controllers\comment\Create::class,
            'view' => \api\modules\api\frontend\v1\controllers\comment\View::class,
            'options' => \yii\rest\OptionsAction::class,
            
        ];
    }

    public function findModel($id, $type) {

        if (($model = Comment::find()->where([
                'post_id' => $id,
                'status' => Comment::STATUS_ACTIVE,
                'type' => $type
            ])->all()) !== null) {
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
                    'controller' => 'f1/comment',
                    'tokens' => [
                        //'{id}' => '<id:\\w+>',
                        '{id}' => '<id:.*>',
                    ],
                    'extraPatterns' => [

                    ],
                ]
            ];
    }
}
