<?php

namespace api\modules\api\frontend\v1\models\music;

use common\models\music\Music;
use common\models\tag\Tag;
use Yii;

class MusicLike extends Music
{

    public function like($id)
    {
        $type = Yii::$app->request->headers->get('type');

        if ($this->likeLog($type, $id)){
            $status = \Yii::$app->helper->getTypeStatus($type);
            if (($model = Music::find()->where([
                    'id' => $id,
                    $status => Music::STATUS_ACTIVE
                ])->orWhere([
                    'key' => $id,
                    $status => Music::STATUS_ACTIVE
                ])->one()) !== null) {
            } else {
                throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
            }

            $like = \Yii::$app->helper->getTypeLike($type);
            $model->updateCounters([$like => 1]);

            return $model;
        }else{
            throw new \yii\web\BadRequestHttpException(\Yii::t('app', 'This post before liked!'));
        }

    }

    protected function likeLog($type, $id){


        if (Yii::$app->user->isGuest)
            return true;

        if (!\common\models\like\Like::find()->where([
            'type' => $type,
            'post_id' => $id,
            'post_type' => Tag::TYPE_MUSIC,
            'author_ip' => Yii::$app->ip->getUserIp(),
            'user_id' => isset(Yii::$app->user->id) ? Yii::$app->user->id : 0
        ])->one()){

            $model = new \common\models\like\Like();
            $model->type = $type;
            $model->post_id = $id;
            $model->user_id = isset(Yii::$app->user->id) ? Yii::$app->user->id : 0;
            $model->author_ip = Yii::$app->ip->getUserIp();
            $model->created_at = time();
            $model->post_type = Tag::TYPE_MUSIC;
            $model->save();
            return true;
        }

        return false;


    }

}