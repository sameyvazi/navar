<?php

namespace api\modules\api\frontend\v1\models\auth;

use Yii;
use yii\base\Model;
use common\models\user\User;

class LoginForm extends Model {
    
    public $mobile;
    public $user;
    public $email;
    public $password;
        
     /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'validateUser'],
            ['password', 'validatePassword']
        ];
    }
    
    public function attributeLabels() {
        return [
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
        ];
    }
    
    /**
     * Validates the mobile.
     * This method serves as the inline validation for mobile.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateMobile($attribute, $params) {
        if (!$this->hasErrors()) {
            if (!Yii::$app->helper->validateMobile($this->{$attribute})) {
                $this->addError($attribute, Yii::t('app', 'Mobile is not valid and must be with this format: 0936...'));
            }
        }
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->user || !Yii::$app->getSecurity()->validatePassword($this->$attribute, $this->user->password)) {
            $this->addError($attribute, \Yii::t('app', 'wrong input'));
        }
    }


    public function validateUser($attribute, $params) {
        if (!$this->hasErrors()) {
            
//            if ($this->isFraud($this->email))
//            {
//                $this->addError('email', Yii::t('app', 'More attempt to login!'));
//                return false;
//            }
        
            $this->user = User::findOne([
                'email' => $this->email,
                'status' => User::STATUS_ACTIVE
            ]);
            if (!$this->user) {
                $this->addError($attribute, Yii::t('app', 'User with this email is not exist'));
            }
        }
    }
    
    public function login() {
        
        if (!$this->validate())
        {
            return false;
        }
        
//        $this->user->activation_code = rand(10000, 99999);
//        $this->user->save(false);
        
        return $this->user;
    }
    
    public function getDataAfterSave(User $user)
    {
        $id = (string)$user->getPrimaryKey();
        $cacheKey = User::getCacheKeyForActivate($id);
        $securityCode = uniqid(Yii::$app->getSecurity()->generateRandomString(rand(30, 50)), true);
        Yii::$app->getCache()->set($cacheKey, $securityCode, 600);
        Yii::$app->getCache()->delete(User::getCacheKeyForActivateAttempt($id));
        return [
            'id' => (string)$user->getPrimaryKey(),
            'security_code' => $securityCode
        ];
    }
    
    protected function isFraud($mobile)
    {
        $cacheKey = "user_login_attempt{$mobile}";
        if (!($userLoginAttempt = Yii::$app->getCache()->get($cacheKey)))
        {
            $userLoginAttempt = 1;
            Yii::$app->getCache()->set($cacheKey, $userLoginAttempt, 1800);
        }
        else
        {
            $userLoginAttempt++;
            Yii::$app->getCache()->set($cacheKey, $userLoginAttempt, 1800);
        }

        if ($userLoginAttempt > 10)
        {
            return true;
        }
        
        return false;
    }
    
}