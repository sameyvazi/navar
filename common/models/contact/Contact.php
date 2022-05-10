<?php

namespace common\models\contact;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "contacts".
 *
 * @property int $id
 * @property int $type
 * @property string $author_name
 * @property string $author_email
 * @property string $content
 * @property int $status
 * @property int $created_at
 * @property string $subject
 */
class Contact extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;

    const TYPE_INAVAR = 1;
    const TYPE_MUSICPLUS = 2;
    const TYPE_NAVAR_APP = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'created_at'], 'required'],
            [['type', 'status', 'created_at'], 'integer'],
            [['content', 'subject'], 'string'],
            [['author_name', 'author_email', 'subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'author_name' => Yii::t('app', 'Author Name'),
            'author_email' => Yii::t('app', 'Author Email'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'subject' => Yii::t('app', 'Subject'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
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
            (string) self::TYPE_INAVAR => Yii::t('app', 'Inavar'),
            (string) self::TYPE_MUSICPLUS => Yii::t('app', 'Musicplus'),
            (string) self::TYPE_NAVAR_APP => Yii::t('app', 'Navar app'),
        ];

    }

    public static function getTypeText($status) {
        switch ($status) {
            case self::TYPE_INAVAR:
                return Yii::t('app', 'Inavar');
            case self::TYPE_MUSICPLUS:
                return Yii::t('app', 'Musicplus');
            case self::TYPE_NAVAR_APP:
                return Yii::t('app', 'Navar app');
            default :
                return null;
        }
    }
}
