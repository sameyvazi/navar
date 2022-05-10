<?php

namespace common\models\artist;

use common\models\follower\Follower;
use common\models\user\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\Html;

/**
 * This is the model class for table "artists".
 *
 * @property int $id
 * @property string $name
 * @property string $name_fa
 * @property int $activity
 * @property string $image
 * @property int $like
 * @property int $like_fa
 * @property int $like_app
 * @property int $status
 * @property int $status_fa
 * @property int $status_app
 * @property int $status_site
 * @property int $status_zizz
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 * @property string $key
 * @property string $key_fa
 */
class Artist extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;

    const TYPE_SINGER = 1;
    const TYPE_COMPOSER = 2;
    const TYPE_LYRIC = 3;
    const TYPE_REGULATOR = 4;
    const TYPE_MUSICIANS = 5;
    const TYPE_MONTAGE = 6;
    const TYPE_DIRECTOR = 7;
    const TYPE_MAIN_ARTIST = 8;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'artists';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_fa', 'activity', 'status', 'status_fa', 'status_app', 'status_site', 'user_id', 'created_at', 'updated_at', 'key', 'key_fa'], 'required'],
            [['activity', 'like', 'like_fa', 'like_app', 'status', 'status_fa', 'status_app', 'status_site', 'status_zizz', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'name_fa', 'image', 'key', 'key_fa'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'name_fa' => Yii::t('app', 'Name Fa'),
            'activity' => Yii::t('app', 'Activity'),
            'image' => Yii::t('app', 'Image'),
            'like' => Yii::t('app', 'Like'),
            'like_fa' => Yii::t('app', 'Like Fa'),
            'like_app' => Yii::t('app', 'Like App'),
            'status' => Yii::t('app', 'Status'),
            'status_fa' => Yii::t('app', 'Status Fa'),
            'status_app' => Yii::t('app', 'Status App'),
            'status_site' => Yii::t('app', 'Status Site'),
            'status_zizz' => Yii::t('app', 'Status Zizz'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'key' => Yii::t('app', 'Key'),
            'key_fa' => Yii::t('app', 'Key Fa'),
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        if (isset($fields['activity']))
        {
            $fields['activity'] = function($model)
            {
                return $model->getTypeText($model->activity);
            };
        }

        $fields['link'] = function($model)
        {
            return [
                'artist_id' => 'f1/artists/'.$model->id,
                'artist_key' => 'f1/artists/'.$model->key,
            ];
        };


        $fields['follow'] = function($model)
        {
            $follower = null;
            $authHeader = Yii::$app->request->headers->get('Authorization');
            if ($authHeader){
                preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches);
                $identity = User::findIdentityByAccessToken($matches[1]);
                $follower = Follower::find()->where(['post_id' => $model->id, 'user_id' => $identity ? $identity->id : false, 'post_type' => Follower::TYPE_ARTIST])->one();
            }

            return !is_null($follower) ? true : false;
        };

        $fields['follow_count'] = function($model)
        {
            return Follower::find()->where(['post_id' => $model->id, 'post_type' => Follower::TYPE_PLAYLIST])->count();
        };

        unset($fields['created_at'], $fields['updated_at'], $fields['user_id']);

        return $fields;
    }


    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
        ];
    }

    public static function getStatusList() {
        return [
            (string) self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            (string) self::STATUS_DISABLED => Yii::t('app', 'Disabled'),
        ];

    }

    public static function getStatusText($status) {
        switch ($status) {
            case self::STATUS_ACTIVE:
                return Yii::t('app', 'Active');
            case self::STATUS_DISABLED:
                return Yii::t('app', 'Disabled');
            default :
                return null;
        }
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

    public static function getTypeList() {
        return [
            (string) self::TYPE_SINGER => Yii::t('app', 'Singer'),
            (string) self::TYPE_COMPOSER => Yii::t('app', 'Composer'),
            (string) self::TYPE_LYRIC => Yii::t('app', 'Lyric'),
            (string) self::TYPE_REGULATOR => Yii::t('app', 'Regulator'),
            (string) self::TYPE_MUSICIANS => Yii::t('app', 'Musicians'),
            (string) self::TYPE_MONTAGE => Yii::t('app', 'Montage'),
            (string) self::TYPE_DIRECTOR => Yii::t('app', 'Director'),
            (string) self::TYPE_MAIN_ARTIST => Yii::t('app', 'Singer'),
        ];

    }

    public static function getTypeText($type) {
        switch ($type) {
            case self::TYPE_SINGER:
                return Yii::t('app', 'Singer');
            case self::TYPE_COMPOSER:
                return Yii::t('app', 'Composer');
            case self::TYPE_LYRIC:
                return Yii::t('app', 'Lyric');
            case self::TYPE_REGULATOR:
                return Yii::t('app', 'Regulator');
            case self::TYPE_MUSICIANS:
                return Yii::t('app', 'Musicians');
            case self::TYPE_MONTAGE:
                return Yii::t('app', 'Montage');
            case self::TYPE_DIRECTOR:
                return Yii::t('app', 'Director');
            case self::TYPE_MAIN_ARTIST:
                return Yii::t('app', 'Singer');
            default :
                return null;
        }
    }
}
