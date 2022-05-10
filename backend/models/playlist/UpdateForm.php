<?php

namespace backend\models\playlist;

use yii\base\Model;

/**
 * Update form
 */
class UpdateForm extends Model  {

    public $name;
    public $name_fa;
    public $image;
    public $status;
    public $status_fa;
    public $status_app;
    public $no;
    public $mood_id;
    public $limit;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'name_fa', 'status', 'status_fa', 'status_app', 'mood_id'], 'required'],
            [['no', 'limit', 'status', 'status_fa', 'status_app', 'mood_id'], 'integer'],
            [['name', 'name_fa', 'image'], 'string', 'max' => 255],
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

            return true;
        }
    }
}