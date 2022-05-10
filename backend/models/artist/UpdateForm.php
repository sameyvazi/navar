<?php

namespace backend\models\artist;

use common\models\artist\Artist;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use Yii;
use yii\base\Model;

/**
 * Update form
 */
class UpdateForm extends Model  {

    public $name;
    public $name_fa;
    public $key;
    public $key_fa;
    public $activity;
    public $image;
    public $status;
    public $status_fa;
    public $status_app;
    public $status_site;
    public $like;
    public $like_fa;
    public $like_app;
    public $tag;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'name_fa', 'activity', 'status', 'status_fa', 'status_app', 'status_site','key', 'key_fa'], 'required'],
            [['activity', 'like', 'like_fa', 'like_app', 'status', 'status_fa', 'status_app', 'status_site'], 'integer'],
            [['name', 'name_fa', 'image'], 'string', 'max' => 255],
            ['tag', 'safe']
        ];
    }

    public function attributeLabels() {
        return [

        ];
    }

    public function save($model)
    {
        if (!$this->validate())
        {
            return false;
        }

        $this->image = $model->image;
        $model->attributes = $this->attributes;


        if($model->save(false)){

            $tags[] = str_replace(' ', '-', $this->name);
            $tags[] = str_replace(' ', '-', $this->name_fa);
            $tags [] = $this->key;
            $tags [] = $this->key_fa;
            if($this->activity == Artist::TYPE_SINGER){
                $tags[] = 'دانلود-فول-آلبوم-'.str_replace(' ', '-', $this->name_fa);
            }

            if ($this->tag != ""){
                $tagCustom = explode("\n", $this->tag);

                foreach ($tagCustom as $t){
                    $tags[] = $t;
                }
            }

            TagRelation::deleteAll(['post_id' => $model->id, 'type' => Tag::TYPE_ARTIST]);

            \Yii::$app->tags->hashtag($tags, Tag::TYPE_ARTIST, $model->id);

        }
    }
}