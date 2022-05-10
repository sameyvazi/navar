<?php

namespace api\modules\api\frontend\v1\models\auth;

use Yii;
use yii\base\Model;
use common\models\user\User;

class ActivateForm extends Model {
    
    public $id;
    public $code;
    public $fcm;

     /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'code'], 'required'],
            //['id', \yii\mongodb\validators\MongoIdValidator::class],
            ['fcm', 'string']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Activation code'),
            'security_code' => Yii::t('app', 'Security code'),
        ];
    }
    
    public function activate()
    {
//        if ($this->isFraud($this->id))
//        {
//            $this->addError('id', Yii::t('app', 'More attempt to activate account!'));
//            return false;
//        }

//        $cacheKey = User::getCacheKeyForActivate($this->id);
//        $cacheSecurity = Yii::$app->getCache()->get($cacheKey);

        $model = User::findOne([
            'id' => $this->id,
            'activation_code' => (int)$this->code,
            'status' => User::STATUS_ACTIVE,
        ]);

        if ($model && $model->activation_code == $this->code)
        {


//            $extraData = [];
//            if ($this->fcm)
//            {
//                $extraData = [
//                    '$addToSet' => [
//                        'devices' =>  [
//                            'fcm' => $this->fcm
//                        ]
//                    ]
//                ];
//            }

            if ($model->unsetActivationCodeField($this->fcm))
            {
                return $model;
            }
            
        }
        
        $this->addError('code', Yii::t('app', 'Activation code is not correct!'));
        
        return false;
    }
    
    public function getDataAfterSave(User $user)
    {
        Yii::$app->getCache()->delete(User::getCacheKeyForActivate($user->mobile));
        Yii::$app->getCache()->delete(User::getCacheKeyForActivateAttempt((string)$user->getPrimaryKey()));

        return [
            'token' => $user->getJWT(),
            'user' => User::find()->where(['id' => $user->id])->one(),
        ];
    }
    
    protected function isFraud($id)
    {
        $cacheKey = User::getCacheKeyForActivateAttempt($id);
        if (!($userActivateAttempt = Yii::$app->getCache()->get($cacheKey)))
        {
            $userActivateAttempt = 1;
            Yii::$app->getCache()->set($cacheKey, $userActivateAttempt, 1800);
        }
        else
        {
            $userActivateAttempt++;
            Yii::$app->getCache()->set($cacheKey, $userActivateAttempt, 1800);
        }

        if ($userActivateAttempt > 5)
        {
            return true;
        }
        
        return false;
    }
    
}