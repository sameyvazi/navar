<?php

namespace common\models\admin\form;

use common\models\admin\Admin;
use yii\base\Model;
use Yii;
use yii\web\ServerErrorHttpException;

class RegisterForm extends Model
{
    
    public $username;
    
    public $password;
    public $confirmPassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'confirmPassword', 'username'], 'required'],
            ['username', 'trim'],
            ['username', 'string', 'max' => 100],
            ['username', 'unique', 'targetClass' => \common\models\admin\Admin::class],
            ['password', 'string', 'min' => 6, 'max' => 100],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password'],
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'confirmPassword' => Yii::t('app', 'Confirm Password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return Boolean|User
     */
    public function register($validate = true)
    {
        if ($validate && !$this->validate())
        {
            return false;
        }
        $user = new Admin();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = Admin::STATUS_ACTIVE;
        if (!$user->save(false))
        {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $user;
    }
    
}
