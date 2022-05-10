<?php

namespace backend\models\special;

use Yii;
use yii\base\Model;
use common\models\admin\Admin;

/**
 * Update form
 */
class UpdateForm extends Model {

    public $post_id;
    public $type;
    public $position;
    public $music_id;
    public $playlist_id;
    public $artist_id;
    public $no;
    public $post_type;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type', 'position', 'no', 'post_type'], 'required'],
            [['post_id', 'playlist_id', 'music_id', 'artist_id'], 'safe'],
        ];
    }

    public function attributeLabels() {
        return [
            'type' => Yii::t('app', 'Type'),
            'position' => Yii::t('app', 'Position'),
            'music_id' => Yii::t('app', 'Music ID'),
            'no' => Yii::t('app', 'No'),
        ];
    }

    public function save($model)
    {
        if (!$this->validate())
        {
            return false;
        }

        $model->attributes = $this->attributes;

        return $model->save(false);
    }
        
}
