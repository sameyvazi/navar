<?php

namespace api\modules\api\frontend\v1\models\playlist;

use common\models\comment\Comment;
use common\models\mood\Mood;
use common\models\playlist\Playlist;
use common\models\playlist\PlaylistMusic;
use Yii;
use yii\base\Model;

class PlaylistAddMusicForm extends Model {
    
    public $playlist_id;
    public $music_id;
    public $no;


    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['playlist_id', 'music_id'], 'required'],
            [['playlist_id'], 'auth'],
            [['playlist_id', 'music_id', 'no'], 'integer'],
            ['playlist_id', 'limit'],
            ['playlist_id', 'findNo'],
            [['music_id'], 'unique', 'targetAttribute' => ['playlist_id', 'music_id'], 'targetClass'=> PlaylistMusic::class],
            [['no'], 'unique', 'targetAttribute' => ['playlist_id', 'no'], 'targetClass'=> PlaylistMusic::class],
        ];
    }

    public function auth($attribute, $params)
    {
        if (!$this->hasErrors()) {

            $playlist = Playlist::find()->where(['id' => $this->$attribute, 'user_id' => Yii::$app->user->id, 'public' => Playlist::TYPE_PRIVATE])->one();

            if (!$playlist) {
                $this->addError($attribute, 'You dont have permission to add music to this playlist!');
            }
        }
    }

    public function limit($attribute, $params)
    {
        if (!$this->hasErrors()) {

            $playlist = Playlist::find()->where(['id' => $this->$attribute])->one();

            $playlistMusicCount = PlaylistMusic::find()->where(['playlist_id' => $this->$attribute])->count();

            if ($playlistMusicCount >= $playlist->limit) {
                $this->addError($attribute, 'Playlist limit!');
            }
        }
    }

    public function findNo($attribute, $params)
    {
        if (!$this->hasErrors()) {

            $playlist = Playlist::find()->where(['id' => $this->$attribute])->one();

            for ($i = 1; $i <= $playlist->limit; $i++){
                $playlistMusic = PlaylistMusic::find()->where(['playlist_id' => $this->$attribute, 'no' => $i])->one();

                if ($playlistMusic == null){
                    $this->no = $i;
                    break;
                }
            }

            if (!$this->no){
                $this->addError($attribute, 'Playlist limit!');
            }
        }
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'playlist_id' => Yii::t('app', 'Playlist ID'),
            'music_id' => Yii::t('app', 'Music ID'),
            'no' => Yii::t('app', 'No'),
        ];
    }

    public function add(){

        $type = Yii::$app->request->headers->get('type');
        if (is_null($type)) {
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'Send the client type in the header!'));
        }



        $model = new PlaylistMusic();
        $model->playlist_id = $this->playlist_id;
        $model->music_id = $this->music_id;
        $model->no = $this->no;
        $model->save();

        return true;
    }
}