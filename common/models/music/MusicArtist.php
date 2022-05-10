<?php

namespace common\models\music;

use common\models\artist\Artist;
use Yii;

/**
 * This is the model class for table "music_artists".
 *
 * @property int $id
 * @property int $music_id
 * @property int $artist_id
 * @property int $activity
 * @property int $type
 */
class MusicArtist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'music_artists';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['music_id', 'artist_id', 'activity', 'type'], 'required'],
            [['music_id', 'artist_id', 'activity', 'type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'music_id' => Yii::t('app', 'Music ID'),
            'artist_id' => Yii::t('app', 'Artist ID'),
            'activity' => Yii::t('app', 'Activity'),
            'type' => Yii::t('app', 'Type'),
        ];
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
//
//        if (isset($fields['artist_id']))
//        {
//            $fields['artist'] = function($model)
//            {
//                return $model->artist;
//            };
//        }

        if (isset($fields['activity']))
        {
            $fields['activity'] = function($model)
            {
                return [
                    'key' => $model->activity,
                    'name' => Artist::getTypeText($model->activity)
                ];
            };
        }

        unset($fields['music_id'], $fields['artist_id'], $fields['user_id']);

        return $fields;
    }

    public function getArtist()
    {
        return $this->hasOne(Artist::className(), ['id' => 'artist_id']);
    }

    public function getMusic()
    {
        return $this->hasOne(Music::className(), ['id' => 'music_id']);
    }
}
