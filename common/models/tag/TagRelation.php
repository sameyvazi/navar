<?php

namespace common\models\tag;

use common\models\artist\Artist;
use Yii;
use common\models\music\Music;

/**
 * This is the model class for table "tag_relations".
 *
 * @property int $id
 * @property int $post_id
 * @property int $tag_id
 * @property int $type
 */
class TagRelation extends \yii\db\ActiveRecord
{
    public $value;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag_relations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'tag_id', 'type'], 'required'],
            [['post_id', 'tag_id', 'type'], 'integer'],
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
            'tag_id' => Yii::t('app', 'Tag ID'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        if (isset($fields['post_id']))
        {
            $fields['post'] = function($model)
            {
                if ($model->type == Tag::TYPE_MP3 || $model->type == Tag::TYPE_VIDEO || $model->type == Tag::TYPE_ALBUM ){

                    return $model->music;

                }elseif ($model->type == Tag::TYPE_ARTIST){

                    return $model->artist;
                }

            };
        }

        if (isset($fields['tag_id']))
        {
            $fields['tag'] = function($model)
            {
                return $model->tags;
            };
        }


        unset($fields['id'], $fields['tag_id'], $fields['post_id']);

        return $fields;
    }

    public function getTags()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }

    public function getMusic()
    {
        return $this->hasOne(Music::className(), ['id' => 'post_id']);
    }

    public function getArtist()
    {
        return $this->hasOne(Artist::className(), ['id' => 'post_id']);
    }
}
