<?php

namespace backend\models\admin;

use Yii;
use yii\base\Model;
use common\models\admin\Admin;

/**
 * Login form
 */
class LoginForm extends Model {

    public $username;
    public $password;
    public $rememberMe = true;
    private $admin = false;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels() {
        return [
            'username' => Yii::t('app', 'Username'),
            'rememberMe' => Yii::t('app', 'Remember me'),
            'password' => Yii::t('app', 'Password'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $admin = $this->getAdmin();
            if (!$admin || !$admin->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect username or password.'));
            }
        }
    }

    /**
     *
     * @return boolean whether the admin is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            return $this->getAdmin()->login($this->rememberMe);
        } else {
            return false;
        }
    }
    
    /**
     * Finds admin by [[username]]
     *
     * @return Admin|null
     */
    public function getAdmin() {
        if ($this->admin === false) {
            $this->admin = Admin::findByUsername($this->username);
        }

        return $this->admin;
    }

}
