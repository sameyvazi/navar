<?php

namespace common\models\playlist;

use common\models\music\Music;
use Yii;

/**
 * This is the model class for table "playlist_music".
 *
 * @property int $id
 * @property int $playlist_id
 * @property int $music_id
 * @property int $no
 */
class PlaylistMusic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'playlist_music';
    }

    public function fields()
    {
        $fields = parent::fields();

        if (isset($fields['music_id']))
        {
            $fields['music'] = function($model)
            {
                return $model->music;
            };
        }

        if (isset($fields['playlist_id']))
        {
            $fields['playlist'] = function($model)
            {
                return $model->playlist;
            };
        }

        unset($fields['music_id'], $fields['playlist_id']);

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['playlist_id', 'music_id', 'no'], 'required'],
            [['playlist_id', 'music_id', 'no'], 'integer'],
            ['playlist_id', 'limit'],
            ['playlist_id', 'exist', 'targetClass' => Playlist::class, 'targetAttribute' => 'id'],
            [['music_id'], 'unique', 'targetAttribute' => ['playlist_id', 'music_id']],
            //[['no'], 'unique', 'targetAttribute' => ['playlist_id', 'no']],

        ];
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'playlist_id' => Yii::t('app', 'Playlist ID'),
            'music_id' => Yii::t('app', 'Music ID'),
            'no' => Yii::t('app', 'No'),
        ];
    }

    public function updateNo(){

        $musics = PlaylistMusic::find()
            ->where(['playlist_id' => $this->playlist_id])
            ->andWhere(['not in', 'id', $this->id])
            ->all();

        foreach ($musics as $music){
            if ($music->no >= $this->no){
                $music->no ++;
                $music->save();
            }
        }

    }

    public function getMusic()
    {
        return $this->hasOne(Music::className(), ['id' => 'music_id']);
    }

    public function getPlaylist()
    {
        return $this->hasOne(Playlist::className(), ['id' => 'playlist_id']);
    }
}
