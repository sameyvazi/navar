<?php

namespace backend\models\playlistMusic;

use common\models\playlist\Playlist;
use common\models\playlist\PlaylistMusic;
use yii\base\Model;

/**
 * Update form
 */
class UpdateForm extends Model  {


    public $playlist_id;
    public $music_id;
    public $no;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['playlist_id', 'music_id', 'no'], 'required'],
            [['playlist_id', 'music_id', 'no'], 'integer'],
            ['playlist_id', 'limit'],
        ];
    }

    public function limit($attribute, $params)
    {
        if (!$this->hasErrors()) {

            $playlist = Playlist::find()->where(['id' => $this->$attribute])->one();

            $playlistMusicCount = PlaylistMusic::find()->where(['playlist_id' => $this->$attribute])->count();

            if ($playlistMusicCount > $playlist->limit) {
                $this->addError($attribute, 'Playlist limit!');
            }
        }
    }

    public function save($model)
    {
        if (!$this->validate())
        {
            return false;
        }

        $model->attributes = $this->attributes;

        if($model->save(false)){

            return true;
        }
    }
}