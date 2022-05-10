<?php

namespace common\models\mood;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "moods".
 *
 * @property int $id
 * @property string $name
 * @property string $name_fa
 * @property string $key
 * @property string $image
 * @property int $no
 * @property int $status
 * @property int $status_fa
 * @property int $status_app
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 */
class Mood extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'moods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_fa', 'status', 'status_fa', 'status_app'], 'required'],
            [['name', 'name_fa'], 'unique'],
            [['no', 'status', 'status_fa', 'status_app', 'user_id', 'created_at', 'updated_at'], 'integer'],
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
            'key' => Yii::t('app', 'Key'),
            'image' => Yii::t('app', 'Image'),
            'no' => Yii::t('app', 'No'),
            'status' => Yii::t('app', 'Status'),
            'status_fa' => Yii::t('app', 'Status Fa'),
            'status_app' => Yii::t('app', 'Status App'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        unset($fields['created_at'], $fields['updated_at'], $fields['user_id'], $fields['status'], $fields['status_fa'], $fields['status_app']);

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
        return [
            '00' => '00',
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
            '05' => '05',
            '06' => '06',
            '07' => '07',
            '08' => '08',
            '09' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22',
            '23' => '23',
            '24' => '24',
            '25' => '25',
            '26' => '26',
            '27' => '27',
            '28' => '28',
            '29' => '29',
            '30' => '30'
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->image = uniqid().'.jpg';
            $this->key = strtolower(str_replace(' ', '-', ltrim(rtrim($this->name))));
            return true;
        }

        return true;

    }
}
