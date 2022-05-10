<?php

namespace backend\models\user;

use Yii;
use yii\base\Model;

/**
 * Update form
 */
class UpdateForm extends Model  {

    public $name;
    public $lastname;
    public $gender;
    public $birthday;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'lastname'],'string'],
            ['gender', 'in', 'range' => [0, 1]],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => Yii::t('app', 'Name'),
            'lastname' => Yii::t('app', 'Lastname'),
            'gender' => Yii::t('app', 'Gender'),
            'birthday' => Yii::t('app', 'Birthday'),
        ];
    }
    
    public function save($model)
    {

        if (!$this->validate())
        {
            return false;
        }

        $model->name = $this->name;
        $model->lastname = $this->lastname;
        $model->gender = $this->gender;

        return $model->save(false);
    }
}