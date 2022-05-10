<?php

namespace api\modules\api\frontend\v1\models\artist;

use common\models\artist\Artist;
use common\models\tag\Tag;
use Yii;

class ArtistLike extends Artist
{

    public function like($id)
    {
        $type = Yii::$app->request->headers->get('type');

        if ($this->likeLog($type, $id)){
            $status = \Yii::$app->helper->getTypeStatus($type);
            if (($model = Artist::find()->where([
                    'id' => $id,
                    $status => Artist::STATUS_ACTIVE
                ])->orWhere([
                    'key' => $id,
                    $status => Artist::STATUS_ACTIVE
                ])->one()) !== null) {
            } else {
                throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
            }

            $like = \Yii::$app->helper->getTypeLike($type);
            $model->updateCounters([$like => 1]);

            return $model;
        }else{
            throw new \yii\web\BadRequestHttpException(\Yii::t('app', 'This artist before liked!'));
        }

    }

    protected function likeLog($type, $id){

        if (Yii::$app->user->isGuest)
            return true;

        if (!\common\models\like\Like::find()->where([
            'type' => $type,
            'post_id' => $id,
            'author_ip' => Yii::$app->ip->getUserIp(),
            'post_type' => Tag::TYPE_ARTIST,
            'user_id' => isset(Yii::$app->user->id) ? Yii::$app->user->id : 0
        ])->one()){

            $model = new \common\models\like\Like();
            $model->type = $type;
            $model->post_id = $id;
            $model->user_id = isset(Yii::$app->user->id) ? Yii::$app->user->id : 0;
            $model->author_ip = Yii::$app->ip->getUserIp();
            $model->created_at = time();
            $model->post_type = Tag::TYPE_ARTIST;
            $model->save();
            return true;
        }

        return false;

    }

}