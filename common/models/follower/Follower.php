<?php

namespace common\models\follower;

use common\models\artist\Artist;
use common\models\music\Music;
use common\models\playlist\Playlist;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "followers".
 *
 * @property int $id
 * @property int $post_id
 * @property int $post_type
 * @property int $user_id
 * @property int $created_at
 */
class Follower extends \yii\db\ActiveRecord
{

    const TYPE_MUSIC = 1;
    const TYPE_ARTIST = 2;
    const TYPE_PLAYLIST = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'followers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'post_type', 'user_id', 'created_at'], 'required'],
            [['post_id', 'post_type', 'user_id', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'post_id' => Yii::t('app', 'Post ID'),
            'post_type' => Yii::t('app', 'Post Type'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        if (isset($fields['post_id']))
        {
            $fields['post_id'] = function($model)
            {
                if ($model->post_type == Follower::TYPE_MUSIC){

                    return $model->music;

                }elseif ($model->post_type == Follower::TYPE_ARTIST){

                    return $model->artist;

                }elseif ($model->post_type == Follower::TYPE_PLAYLIST){

                    return $model->playlist;
                }

            };
        }

        if (isset($fields['post_type']))
        {
            $fields['post_type'] = function($model)
            {
                return [
                    'key' => $model->post_type,
                    'value' =>  $model->getTypeText($model->post_type)
                ];
            };
        }

        unset($fields['created_at'], $fields['user_id']);

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
        ];
    }

    public static function getTypeList() {
        return [
            (string) self::TYPE_MUSIC => Yii::t('app', 'Music'),
            (string) self::TYPE_ARTIST => Yii::t('app', 'Artist'),
            (string) self::TYPE_PLAYLIST => Yii::t('app', 'Playlist'),
        ];

    }

    public static function getTypeText($status) {
        switch ($status) {
            case self::TYPE_MUSIC:
                return Yii::t('app', 'Music');
            case self::TYPE_ARTIST:
                return Yii::t('app', 'Artist');
            case self::TYPE_PLAYLIST:
                return Yii::t('app', 'Playlist');
            default :
                return null;
        }
    }

    public function getMusic()
    {
        return $this->hasOne(Music::className(), ['id' => 'post_id']);
    }

    public function getArtist()
    {
        return $this->hasOne(Artist::className(), ['id' => 'post_id']);
    }

    public function getPlaylist()
    {
        return $this->hasOne(Playlist::className(), ['id' => 'post_id']);
    }
}
