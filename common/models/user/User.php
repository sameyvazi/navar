<?php
namespace common\models\user;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\bootstrap\Html;
use yii\web\UploadedFile;

/**
 * User model
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $mobile
 * @property string $name
 * @property string $lastname
 * @property int $gender
 * @property string $birthday
 * @property string $activation_code
 * @property array $devices
 * @property integer $status
 * @property string $avatar
 * @property int $created_at
 * @property string $password
 * @property string $reset_password
 * @property string $premium_date
 * @property string $invite_code
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    
    use \damirka\JWT\UserTrait;
    use \common\models\ActivityTrait;
    
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;
    
    public $guardedForActivity = [
        'id',
        'created_at'
    ];
    
//    public function init() {
//        parent::init();
//        $this->attachActivity();
//    }
    
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'users';
    }

//    public function rules()
//    {
//        return [[['mobile'], 'required'],
//            [['gender', 'status', 'created_at'], 'integer'],
//            [['mobile', 'email', 'username', 'name', 'lastname', 'birthday', 'devices', 'activation_code', 'avatar'], 'string', 'max' => 255],
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }
    
    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return [
            'id',
            'mobile',
            'email',
            'username',
            'name',
            'lastname',
            'gender',
            'birthday',
            'devices',
            'activation_code',
            'status',
            'avatar',
            'created_at',
            'password',
            'reset_password',
            'premium_date',
            'invite_code',
        ];
    }
    
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'name' => Yii::t('app', 'Name'),
            'lastname' => Yii::t('app', 'Lastname'),
            'gender' => Yii::t('app', 'Gender'),
            'birthday' => Yii::t('app', 'Birthday'),
            'email' => Yii::t('app', 'Email'),
            'mobile' => Yii::t('app', 'Mobile'),
            'activation_code' => Yii::t('app', 'Activation code'),
            'devices' => Yii::t('app', 'Devices'),
            'created_at' => Yii::t('app', 'Created at'),
            'status' => Yii::t('app', 'Status'),
            'avatar' => Yii::t('app', 'Avatar'),
            'password' => Yii::t('app', 'Password'),
            'reset_password' => Yii::t('app', 'Reset Password'),
            'premium_date' => Yii::t('app', 'Premium Date'),
            'invite_code' => Yii::t('app', 'Invite Code'),
        ];
    }
    
//    public function beforeSave($insert) {
//        if (parent::beforeSave($insert))
//        {
//            if ($insert)
//            {
//                $this->activation_code = (int)rand(10000, 99999);
//            }
//            return true;
//        }
//
//        return false;
//    }
    
    public function fields()
    {
        $fields = parent::fields();
        
        $fields['id'] = function($model)
        {
            return (string)$model->getPrimaryKey();
        };
            
        if (isset($fields['avatar']) && !empty($fields['avatar']))
        {
            $fields['avatar'] = function($model)
            {
                return $model->getAvatarUrl($model->avatar);
            };
        }
                        
        unset($fields['_id'], $fields['activation_code'], $fields['status'], $fields['created_at'], $fields['password'], $fields['reset_password']);

        return $fields;
    }
    
    public function unsetActivationCodeField($fcm)
    {
//        return static::updateAll(array_merge($extraParams, [
//            '$unset' => ['activation_code' => true]
//        ]), [
//            '_id' => $this->getPrimaryKey(),
//            'activation_code' => ['$exists' => true]
//        ], ['upsert' => false]);

//        var_dump($fcm);die;
        return User::updateAll(['devices' => $fcm, 'activation_code' => true], ['id' => $this->id]);
    }
    
    protected static function getHeaderToken()
    {
        return [
            'exp' => time() + 63072000 // Two years
        ];
    }

    protected static function getSecretKey()
    {
        return Yii::$app->params['apiFrontendSecretkeyJwt'];
    }
        
    /**
     * @inheritdoc
     */
    public static function findIdentity($id, $extaParams = [])
    {
        return static::findOne(array_merge(['id' => $id, 'status' => self::STATUS_ACTIVE], $extaParams));
    }

    public function getAuthKey() {
        return false;
    }

    public function getId() {
        return (string)$this->getPrimaryKey();
    }

    public function validateAuthKey($authKey) {
        return false;
    }

    public static function findByJTI($id)
    {
        return static::findIdentity($id);
    }
    
    public static function getStatusList() {
        return [
            (string) self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            (string) self::STATUS_DISABLED => Yii::t('app', 'Disabled'),
        ];
        
    }
    
     public static function getStatusText($status) {
        $statuses = static::getStatusList();
        return isset($statuses[$status]) ? $statuses[$status] : null;
    }
    
    public function getStatusImage($status) {
        switch ($status) {
            case self::STATUS_ACTIVE:
                return '<i class="fa fa-check text-success" title="' . Yii::t('app', 'Active') . '"></i>';
            case self::STATUS_DISABLED:
                return '<i class="fa fa-ban text-danger" title="' . Yii::t('app', 'Disabled') . '"></i>';
            default :
                return null;
        }
    }
    
    public function sendActivationSms()
    {
        $content = Yii::t('app', "Welcome to Navar.");
        $content .= "\n" . Yii::t('app', 'For activate use this code: {code}', [
            'code' => $this->activation_code,
        ]);
        return Yii::$app->sms->send($this->mobile, $content);
    }

    public function sendActivationEmail()
    {
        $content = Yii::t('app', "Welcome to Navar.");
        $content .= "\n" . Yii::t('app', 'For activate use this code: {code}', [
                'code' => $this->activation_code,
            ]);

        return Yii::$app->email->send('layouts/html', $content, $this->email, 'navar', '');
    }

    public function sendForgotPasswordEmail()
    {
        $content = Yii::t('app', "Navar Forgot Password.");
        $content .= "\n" . Yii::t('app', 'For reset password use this code: {code}', [
                'code' => $this->reset_password,
            ]);

        return Yii::$app->email->send('layouts/html', $content, $this->email, 'navar', '');
    }

    public function getStatusButton($status, $url, $permission, $pjaxGridName, $messageContainer) {
        if ($permission !== '' && !Yii::$app->getUser()->can($permission))
        {
            return $this->getStatusImage($status);
        }
        switch ($status) {
            case self::STATUS_ACTIVE:
                return Html::a('<i class="fa fa-check text-success" title="' . Yii::t('app', 'Active') . '"></i>', '#', [
                    'title' => Yii::t('app', 'Disable'),
                    "onclick" => "return Navar.disableGridButton('{$url}', '{$pjaxGridName}', '{$messageContainer}' );",
                ]);
            case self::STATUS_DISABLED:
                return Html::a('<i class="fa fa-ban text-danger" title="' . Yii::t('app', 'Disabled') . '"></i>', '#', [
                    'title' => Yii::t('app', 'Enable'),
                    "onclick" => "return Navar.disableGridButton('{$url}', '{$pjaxGridName}', '{$messageContainer}' );",
                ]);
            default :
                return null;
        }
    }
    
    public static function getCacheKeyForActivate($id) {
        return "Login_Activation{$id}";
    }
    
    public static function getCacheKeyForActivateAttempt($id) {
        return "user_login_activate_attempt{$id}";
    }
    
    public function uploadAvatar(UploadedFile $avatar)
    {
        $this->removeAvatar();
        $folderNumber = rand(1, 10);
        $this->avatar = "{$folderNumber}/" . uniqid((string)$this->getPrimaryKey(), true) . ".{$avatar->extension}";
        $imagePath = Yii::getAlias("@common/storage/users/{$folderNumber}");
        if (!is_dir($imagePath))
        {
            mkdir($imagePath, 0755, true);
        }
        return $avatar->saveAs(Yii::getAlias("@common/storage/users/{$this->avatar}"));
    }
    
    public function removeAvatar()
    {
        if (empty($this->avatar))
        {
            return false;
        }
        $path = Yii::getAlias("@common/storage/users/{$this->avatar}");
        if (file_exists($path))
        {
            return @unlink($path);
        }
        
        return false;
    }
    
    public function getAvatarUrl($avatar, $width = 400)
    {
        return Yii::$app->imageHelper->getImage("@common/storage/users/{$avatar}", $width, 0);
    }
    
    public function deleteAvatar()
    {
        if ($this->removeAvatar())
        {
            return static::updateAll([
                '$unset' => [
                    'avatar' => true,
                ]
            ], ['id' => $this->getPrimaryKey()], ['multi' => false, 'upsert' => false]);
        }
        
        return false;
    }
    
}
