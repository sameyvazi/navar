<?php

namespace common\models\playlist;

use common\models\mood\Mood;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "playlists".
 *
 * @property int $id
 * @property string $name
 * @property string $name_fa
 * @property int $mood_id
 * @property string $image
 * @property int $no
 * @property int $limit
 * @property int $status
 * @property int $status_fa
 * @property int $status_app
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $public
 */
class Playlist extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;

    const TYPE_PUBLIC = 1;
    const TYPE_PRIVATE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'playlists';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_fa', 'mood_id', 'status', 'status_fa', 'status_app'], 'required'],
            [['mood_id', 'no', 'limit', 'status', 'status_fa', 'status_app', 'user_id', 'created_at', 'updated_at', 'public'], 'integer'],
            [['name', 'name_fa', 'image'], 'string', 'max' => 255],
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
            'mood_id' => Yii::t('app', 'Mood ID'),
            'image' => Yii::t('app', 'Image'),
            'no' => Yii::t('app', 'No'),
            'limit' => Yii::t('app', 'Limit'),
            'status' => Yii::t('app', 'Status'),
            'status_fa' => Yii::t('app', 'Status Fa'),
            'status_app' => Yii::t('app', 'Status App'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'public' => Yii::t('app', 'Public'),
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        if (isset($fields['mood_id']))
        {
            $fields['mood'] = function($model)
            {
                return $model->mood;
            };
        }

        $fields['link'] = function($model)
        {
            return[
                'playlist_id' => 'f1/playlists/'.$model->id,
                ];
        };


        unset($fields['created_at'], $fields['updated_at'], $fields['user_id'], $fields['status'], $fields['status_fa'], $fields['status_app'], $fields['mood_id']);

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

    public static function getNumberList() {

        for ($i=00;$i<=50;$i++){
            $n [$i] = $i;
        }
        return $n;
    }

    public static function getNumberListRound() {

        for ($i=5;$i<=100;$i+=5){
            $n [$i] = $i;
        }
        return $n;
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

    public function beforeSave($insert)
    {
        if(Yii::$app->request->url != Yii::$app->request->baseUrl.'/f1/playlists'){
            if (parent::beforeSave($insert)) {
                $this->image = uniqid().'.jpg';
                return true;
            }
        }


        return true;

    }

    public function getMood()
    {
        return $this->hasOne(Mood::className(), ['id' => 'mood_id']);
    }
}
