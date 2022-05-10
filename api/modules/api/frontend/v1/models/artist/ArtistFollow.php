<?php

namespace api\modules\api\frontend\v1\models\artist;

use common\models\artist\Artist;
use common\models\follower\Follower;
use Yii;

class ArtistFollow extends Artist
{

    public function follow($id)
    {

        $model = Follower::find()->where(['post_id' => $id, 'user_id' => Yii::$app->user->id, 'post_type' => Follower::TYPE_ARTIST])->one();

        if (!$model){
            $model = new Follower();
            $model->post_id = $id;
            $model->post_type = Follower::TYPE_ARTIST;
            $model->save(false);

            return $model;

        }else{

            $model->delete();
            return true;

            throw new \yii\web\BadRequestHttpException(\Yii::t('app', 'This artist before followed!'));
        }
    }
}