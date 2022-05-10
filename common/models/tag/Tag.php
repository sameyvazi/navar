<?php

namespace common\models\tag;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tags".
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 */
class Tag extends \yii\db\ActiveRecord
{
    const TYPE_ARTIST = 1;
    const TYPE_MP3 = 2;
    const TYPE_VIDEO = 3;
    const TYPE_ALBUM = 4;
    const TYPE_MUSIC = 5;
    const TYPE_PLAYLIST = 6;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
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
        ];
    }

    public static function getTypeList() {
        return [
            (string) self::TYPE_ARTIST => Yii::t('app', 'Artist'),
            (string) self::TYPE_MP3 => Yii::t('app', 'Mp3'),
            (string) self::TYPE_VIDEO => Yii::t('app', 'Video'),
            (string) self::TYPE_ALBUM => Yii::t('app', 'Album'),
            (string) self::TYPE_MUSIC => Yii::t('app', 'All Music'),
            (string) self::TYPE_PLAYLIST => Yii::t('app', 'Playlist'),
        ];

    }
}
