<?php

namespace backend\models\admin;

use Yii;
use yii\base\Model;
use common\models\admin\Admin;

/**
 * ChangePasswordForm form
 */
class ChangePasswordForm extends Model {

    public $old_password;
    public $password;
    public $confirmPassword;
    
    
    /**
     *
     * @var Admin
     */
    private $admin = false;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['old_password', 'password', 'confirmPassword'], 'required'],
            ['old_password', 'string', 'min' => 6, 'max' => 100],
            ['password', 'string', 'min' => 6, 'max' => 100],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password'],
            ['old_password', 'validatePassword'],
        ];
    }

    public function attributeLabels() {
        return [
            'old_password' => Yii::t('app', 'Old Password'),
            'password' => Yii::t('app', 'Password'),
            'confirmPassword' => Yii::t('app', 'Confirm Password'),
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
            if (!$admin || !$admin->validatePassword($this->old_password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect old password.'));
            }
        }
    }

    public function changePassword() {
        if ($this->validate()) {
            
            $admin = $this->getAdmin();
            
            if (!empty($this->password))
            {
                $admin->changePassword($this->password);
            }
            
            return $admin->save();
            
        } else {
            return false;
        }
    }
    
    public function getAdmin()
    {
        if ($this->admin === false) {
            $this->admin = Admin::findOne(Yii::$app->getUser()->id);
        }

        return $this->admin;
    }
        
}
