<?php

namespace api\modules\api\frontend\v1\models\user;

use Yii;
use yii\base\Model;

class UpdateForm extends Model {
    
    public $name;
    public $lastname;
    public $gender;
    public $birthday;
        
     /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'lastname', 'gender'], 'required'],
            [['name', 'lastname'], 'string', 'max' => 40],
            ['gender', 'in', 'range' => [0, 1]],
            ['birthday', 'validateDate'],
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
    
    public function validateDate($attribute, $params) {

        if (!$this->hasErrors()) {
            if (!Yii::$app->dateTimeAction->validateDate($this->{$attribute})) {
                $this->addError($attribute, Yii::t('app', 'Date is not valid and must be with this format: 1367-06-01'));
            }
        }
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
        if ($this->birthday)
        {
            $model->birthday = Yii::$app->dateTimeAction->getMongoDate(Yii::$app->dateTimeAction->getTimestampedDate($this->birthday));
        }
        
        return $model->save() ? $model : false;

    }
}