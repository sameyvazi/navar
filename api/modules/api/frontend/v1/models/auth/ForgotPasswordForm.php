<?php

namespace api\modules\api\frontend\v1\models\auth;

use Yii;
use yii\base\Model;
use common\models\user\User;

class ForgotPasswordForm extends Model {
    
    public $email;
    public $user;

     /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            ['email', 'activeUser'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        
        return [
            'email' => Yii::t('app', 'Email'),
        ];
    }

    public function activeUser($attribute, $params) {
        if (!$this->hasErrors()) {

            $this->user = User::find()->where(['email' => $this->$attribute])->one();

            if ($this->user->activation_code != User::STATUS_ACTIVE) {
                $this->addError($attribute, Yii::t('app', 'Email is not activate.call to Support.'));
            }
        }
    }
    
    public function forgot()
    {

        $user = User::find()->where(['email' => $this->email])->one();
        $user->reset_password = (int)rand(10000, 99999);
        $user->save();


        return $user;
    }
    
}