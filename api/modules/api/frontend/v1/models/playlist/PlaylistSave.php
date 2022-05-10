<?php

namespace api\modules\api\frontend\v1\models\playlist;

use common\models\mood\Mood;
use common\models\playlist\Playlist;
use common\models\playlist\PlaylistFollower;
use Yii;
use yii\data\ActiveDataProvider;

class PlaylistSave extends Playlist
{

    public $playlist_id;

    public function rules() {
        return [
            [['playlist_id'], 'required'],
            [['playlist_id'], 'integer'],
            [['playlist_id'], 'exist', 'targetAttribute'=> 'id'],
            [['playlist_id'], 'uniquePlaylist'],
            [['playlist_id'], 'isPublic'],
        ];
    }

    public function isPublic($attribute, $params)
    {
        if (!$this->hasErrors()) {

            $playlist = Playlist::find()->where(['id' => $this->$attribute, 'public' => Playlist::TYPE_PUBLIC])->one();

            if (!$playlist) {
                $this->addError($attribute, 'You dont have permission to add music to this playlist!');
            }
        }
    }

    public function uniquePlaylist($attribute, $params)
    {
        if (!$this->hasErrors()) {

            $playlist = PlaylistFollower::find()->where(['playlist_id' => $this->$attribute, 'user_id' => Yii::$app->user->id])->one();

            if ($playlist) {
                $this->addError($attribute, 'You follow this playlist before!');
            }
        }
    }

    public function attributeLabels() {
        return [
            'playlist_id' => Yii::t('app', 'Playlist'),
        ];
    }

    public function add(){

        $type = Yii::$app->request->headers->get('type');
        if (is_null($type)) {
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'Send the client type in the header!'));
        }

        $model = new PlaylistFollower();
        $model->playlist_id = $this->playlist_id;
        $model->user_id = Yii::$app->user->id;
        $model->no = 0;
        $model->save();



        return true;
    }

}