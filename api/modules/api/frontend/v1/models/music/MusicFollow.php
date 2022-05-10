<?php

namespace api\modules\api\frontend\v1\models\music;

use common\models\follower\Follower;
use common\models\music\Music;
use Yii;

class MusicFollow extends Music
{

    public function follow($id)
    {

        $model = Follower::find()->where(['post_id' => $id, 'user_id' => Yii::$app->user->id, 'post_type' => Follower::TYPE_MUSIC])->one();

        if (!$model){
            $model = new Follower();
            $model->post_id = $id;
            $model->post_type = Follower::TYPE_MUSIC;
            $model->save(false);

            return $model;
        }else{

            $model->delete();
            return true;

            throw new \yii\web\BadRequestHttpException(\Yii::t('app', 'This music before followed!'));
        }
    }
}