<?php

namespace common\models\playlist;

use Yii;

/**
 * This is the model class for table "playlist_followers".
 *
 * @property int $id
 * @property int $user_id
 * @property int $playlist_id
 * @property int $no
 */
class PlaylistFollower extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'playlist_followers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'playlist_id'], 'required'],
            [['user_id', 'playlist_id', 'no'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'playlist_id' => Yii::t('app', 'Playlist ID'),
            'no' => Yii::t('app', 'No'),
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        if (isset($fields['playlist_id']))
        {
            $fields['playlist'] = function($model)
            {
                return $model->playlist;
            };
        }

        unset($fields['playlist_id']);

        return $fields;
    }

    public function getPlaylist()
    {
        return $this->hasOne(Playlist::className(), ['id' => 'playlist_id']);
    }
}
