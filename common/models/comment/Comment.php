<?php

namespace common\models\comment;

use common\models\music\Music;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property int $post_id
 * @property int $type
 * @property string $author_name
 * @property string $author_email
 * @property string $author_ip
 * @property string $content
 * @property int $like
 * @property int $parent_comment_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Comment extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'type', 'parent_comment_id', 'status', 'created_at', 'updated_at'], 'required'],
            [['post_id', 'type', 'like', 'parent_comment_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['author_name', 'author_email', 'author_ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'post_id' => Yii::t('app', 'Post ID'),
            'type' => Yii::t('app', 'Type'),
            'author_name' => Yii::t('app', 'Author Name'),
            'author_email' => Yii::t('app', 'Author Email'),
            'author_ip' => Yii::t('app', 'Author Ip'),
            'content' => Yii::t('app', 'Content'),
            'like' => Yii::t('app', 'Like'),
            'parent_comment_id' => Yii::t('app', 'Parent Comment ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
                'updatedAtAttribute' => 'updated_at',
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

    public function getMusic()
    {
        return $this->hasOne(Music::className(), ['id' => 'post_id']);
    }
}
