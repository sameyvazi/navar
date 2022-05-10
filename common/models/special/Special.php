<?php

namespace common\models\special;

use common\models\artist\Artist;
use common\models\music\Music;
use common\models\playlist\Playlist;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "specials".
 *
 * @property int $id
 * @property int $type
 * @property int $position
 * @property int $post_id
 * @property int $no
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $post_type
 */
class Special extends \yii\db\ActiveRecord
{
    public $music_id;
    public $playlist_id;
    public $artist_id;

    const TYPE_INAVAR = 1;
    const TYPE_MUSICPLUS = 2;
    const TYPE_NAVAR_APP = 3;
    const TYPE_ZIZZ_APP = 5;

    const POST_TYPE_MUSIC = 1;
    const POST_TYPE_PLAYLIST = 2;
    const POST_TYPE_ARTIST = 3;

    const POS_HOME = 1;
    const POS_HOME_2 = 2;
    const POS_HOME_APP_1 = 3;
    const POS_HOME_APP_2 = 4;
    const POS_HOME_ZIZZ_APP = 5;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'specials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'position', 'no'], 'required'],
            [['type', 'position', 'post_id', 'no', 'user_id', 'created_at', 'updated_at', 'post_type', 'music_id', 'playlist_id', 'artist_id'], 'integer'],
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
            'position' => Yii::t('app', 'Position'),
            'post_id' => Yii::t('app', 'Post ID'),
            'no' => Yii::t('app', 'No'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'post_type' => Yii::t('app', 'Post Type'),
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        if (isset($fields['type']))
        {
            $fields['type'] = function($model)
            {
                return [
                    'key' => $model->type,
                    'value' =>  $model->getTypeText($model->type)
                ];
            };
        }

        if (isset($fields['position']))
        {
            $fields['position'] = function($model)
            {
                return [
                    'key' => $model->type,
                    'value' =>  $model->getPositionText($model->position)
                ];
            };
        }

        if (isset($fields['post_type']))
        {
            $fields['post_type'] = function($model)
            {
                return [
                    'key' => $model->type,
                    'value' =>  $model->getPostTypeText($model->post_type)
                ];
            };
        }

        if (isset($fields['post_id']))
        {
            $fields['post'] = function($model)
            {
                return $this->makeAddress($model);
            };
        }

        unset(
            $fields['created_at'],
            $fields['user_id'],
            $fields['updated_at'],
            $fields['post_id'],
            $fields['type'],
            $fields['position'],
            $fields['post_type']
        );

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

    public static function getPostTypeList() {
        return [
            (string) self::POST_TYPE_MUSIC => Yii::t('app', 'Music'),
            (string) self::POST_TYPE_PLAYLIST => Yii::t('app', 'Playlist'),
            (string) self::POST_TYPE_ARTIST => Yii::t('app', 'Artist'),
        ];
    }

    public static function getPostTypeText($status) {
        switch ($status) {
            case self::POST_TYPE_MUSIC:
                return Yii::t('app', 'Music');
            case self::POST_TYPE_PLAYLIST:
                return Yii::t('app', 'Playlist');
            case self::POST_TYPE_ARTIST:
                return Yii::t('app', 'Artist');
            default :
                return null;
        }
    }

    public static function getTypeList() {
        return [
            (string) self::TYPE_INAVAR => Yii::t('app', 'Inavar'),
            (string) self::TYPE_MUSICPLUS => Yii::t('app', 'Musicplus'),
            (string) self::TYPE_NAVAR_APP => Yii::t('app', 'Navar app'),
            (string) self::TYPE_ZIZZ_APP => Yii::t('app', 'Zizz app'),
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
            case self::TYPE_ZIZZ_APP:
                return Yii::t('app', 'Zizz app');
            default :
                return null;
        }
    }

    public static function getPositionList() {
        return [
            (string) self::POS_HOME => Yii::t('app', 'Home'),
            (string) self::POS_HOME_2 => Yii::t('app', 'Home 2'),
            (string) self::POS_HOME_APP_1 => Yii::t('app', 'Home App 1'),
            (string) self::POS_HOME_APP_2 => Yii::t('app', 'Home App 2'),
            (string) self::POS_HOME_ZIZZ_APP => Yii::t('app', 'Zizz App'),
        ];
    }

    public static function getPositionText($status) {
        switch ($status) {
            case self::POS_HOME:
                return Yii::t('app', 'Home');
            case self::POS_HOME_2:
                return Yii::t('app', 'Home 2');
            case self::POS_HOME_APP_1:
                return Yii::t('app', 'Home App 1');
            case self::POS_HOME_APP_2:
                return Yii::t('app', 'Home App 2');
            case self::POS_HOME_ZIZZ_APP:
                return Yii::t('app', 'Zizz App');
            default :
                return null;
        }
    }

    public static function getNumberList() {
        return [
            '1' => '01',
            '2' => '02',
            '3' => '03',
            '4' => '04',
            '5' => '05',
            '6' => '06',
            '7' => '07',
            '8' => '08',
            '9' => '09',
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
            '20' => '20'
        ];

    }

    public function updateNo(){

        $specials = Special::find()
            ->where(['type' => $this->type, 'position' => $this->position, 'post_type' => $this->post_type])
            ->andWhere(['not in', 'id', $this->id])
            ->all();

        foreach ($specials as $special){
            if ($special->no >= $this->no){
                $special->no ++;
                $special->save();
            }
        }

    }

    protected function makeAddress($model){

        if ($model->post_type == static::POST_TYPE_MUSIC){

            $post = Music::find()->where(['id' => $model->post_id])->one();

            return $post;

        }elseif ($model->post_type == static::POST_TYPE_PLAYLIST){

            $post = Playlist::find()->where(['id' => $model->post_id])->one();

            return $post;
        }elseif ($model->post_type == static::POST_TYPE_ARTIST){

            $post = Artist::find()->where(['id' => $model->post_id])->one();

            return $post;
        }
    }

}
