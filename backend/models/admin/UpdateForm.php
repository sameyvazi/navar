<?php

namespace backend\models\admin;

use Yii;
use yii\base\Model;
use common\models\admin\Admin;

/**
 * Update form
 */
class UpdateForm extends Model {

    public $password;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['password', 'string', 'min' => 6, 'max' => 100],
        ];
    }

    public function attributeLabels() {
        return [
            'password' => Yii::t('app', 'Password'),
        ];
    }

    public function update(Admin $admin) {
        if ($this->validate()) {
            
            if (!empty($this->password))
            {
                $admin->changePassword($this->password);
            }
            
            $admin->attributes = $this->attributes;

            return $admin->save();
            
        } else {
            return false;
        }
    }
        
}
