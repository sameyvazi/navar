<?php

namespace api\modules\api\frontend\v1\models\auth;

use Yii;
use yii\base\Model;
use common\models\user\User;

class RegisterForm extends Model {

    public $email;
    public $password;
    public $fcm;
    public $invite_code;
    public $name;

     /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email', 'password'], 'required'],
            [['fcm', 'invite_code', 'name'], 'string'],
            //['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            //['mobile', 'validateMobile'],
            ['password', 'string', 'min' => 6]
        ];
    }
    
    public function attributeLabels() {
        return [
            'mobile' => Yii::t('app', 'Mobile'),
            //'username' => Yii::t('app', 'Username'),
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
    
    public function register() {
        
        if (!$this->validate())
        {
            return false;
        }
        
//        if ($this->isFraud($this->email))
//        {
//            $this->addError('email', Yii::t('app', 'More attempt to register!'));
//            return false;
//        }
        
//        $updateAttribute = [
//            'mobile' => $this->mobile,
//            'activation_code' => (int)rand(10000, 99999),
//        ];
        
//        User::updateAll([
//            '$set' => $updateAttribute,
//            '$setOnInsert' => [
//                "created_at" => Yii::$app->dateTimeAction->getMongoDate(),
//                'status' => User::STATUS_ACTIVE,
//                'username' => $this->username
//            ]
//        ], ['mobile' => $this->mobile], ['upsert' => true, 'multi' => false]);


        $this->email = str_replace('@gmail.com', '', $this->email);

        $user = User::find()->where([
            'email' => $this->email,
        ])->one();


        if ($user && Yii::$app->getSecurity()->validatePassword($this->password, $user->password)){

            return $user;

        }elseif(!$user){

            $user = new User();
            $user->email = $this->email;
            $user->name = isset($this->name) ? $this->name : '';
            $user->premium_date = time();
            $user->status = User::STATUS_ACTIVE;
            $user->devices = $this->fcm;
            $user->activation_code = $this->password;
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $user->save(false);

            //random code
            $length = rand(2,3);
            $chars = array_merge(range(0,9), range('a', 'z'));
            shuffle($chars);

            $user->invite_code = $user->id.implode(array_slice($chars, 0,$length));
            $user->save();

            if ($this->invite_code){
                $this->getInviteCheck($this->invite_code);
            }
            return $user;
        }

        return false;
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

    public function getInviteCheck($id)
    {
        $user = User::find()->where(['invite_code' => $id])->one();

        if ($user->premium_date <= time()){

            $user->premium_date = (int)time() + (int)Yii::$app->params['premiumDate'];
        }else{

            $user->premium_date = (int)$user->premium_date + (int)Yii::$app->params['premiumDate'];
        }

        $user->save();

        return true;
    }
    
    protected function isFraud($mobile)
    {
        $cacheKey = "user_register_attempt{$mobile}";
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