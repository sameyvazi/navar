<?php

namespace common\models\like;

use Yii;

/**
 * This is the model class for table "likes".
 *
 * @property int $id
 * @property int $type
 * @property int $post_id
 * @property string $author_ip
 * @property int $user_id
 * @property int $created_at
 * @property int $post_type
 */
class Like extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'likes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'post_id', 'created_at'], 'required'],
            [['type', 'post_id', 'user_id', 'created_at', 'post_type'], 'integer'],
            [['author_ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'post_id' => Yii::t('app', 'Post ID'),
            'author_ip' => Yii::t('app', 'Author Ip'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'post_type' => Yii::t('app', 'Post Type'),
        ];
    }
}
