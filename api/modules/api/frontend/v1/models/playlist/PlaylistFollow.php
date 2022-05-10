<?php

namespace api\modules\api\frontend\v1\models\playlist;

use common\models\artist\Artist;
use common\models\follower\Follower;
use common\models\playlist\Playlist;
use common\models\playlist\PlaylistFollower;
use Yii;

class PlaylistFollow extends Playlist
{

    public function follow($id)
    {

        $model = Follower::find()->where(['post_id' => $id, 'user_id' => Yii::$app->user->id, 'post_type' => Follower::TYPE_PLAYLIST])->one();

        if (!$model){
            $model = new Follower();
            $model->post_id = $id;
            $model->post_type = Follower::TYPE_PLAYLIST;
            $model->save(false);

            $modelFollow = new PlaylistFollower();
            $modelFollow->playlist_id = $id;
            $modelFollow->user_id = Yii::$app->user->id;
            $modelFollow->no = 0;
            $modelFollow->save();

            return $model;

        }else{

            $model->delete();
            $modelFollow = PlaylistFollower::find()->where(['playlist_id' => $id, 'user_id' => Yii::$app->user->id])->one();
            $modelFollow->delete();
            return true;

            throw new \yii\web\BadRequestHttpException(\Yii::t('app', 'This playlist before followed!'));
        }
    }
}