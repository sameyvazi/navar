<?php

namespace api\modules\api\frontend\v1\models\auth;

use Yii;
use yii\base\Model;
use common\models\user\User;

class ResetPasswordForm extends Model {
    
    public $email;
    public $code;
    public $password;
    public $user;

     /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email', 'code', 'password'], 'required'],
            ['password', 'string', 'min' => 6],
            ['email', 'email'],
            ['code', 'number'],
            ['email', 'isUser'],

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

    public function isUser($attribute, $params) {
        if (!$this->hasErrors()) {


            $this->user = User::find()->where(['email' => $this->email, 'reset_password' => $this->code])->one();

            if (!$this->user) {
                $this->addError($attribute, Yii::t('app', 'Wrong Input'));
            }
        }
    }
    
    public function reset()
    {

        $this->user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $this->user->reset_password = null;
        $this->user->save();

        return $this->user;
    }
    
}