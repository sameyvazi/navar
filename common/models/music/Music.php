<?php

namespace common\models\music;

use common\models\artist\Artist;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\Html;

/**
 * This is the model class for table "musics".
 *
 * @property int $id
 * @property string $key
 * @property string $key_fa
 * @property string $key_pure
 * @property int $type
 * @property string $name
 * @property string $name_fa
 * @property int $artist_id
 * @property int $special
 * @property int $music_id
 * @property int $music_no
 * @property string $lyric
 * @property string $note
 * @property string $note_fa
 * @property string $note_app
 * @property int $like
 * @property int $like_fa
 * @property int $like_app
 * @property int $view
 * @property int $view_fa
 * @property int $view_app
 * @property int $play
 * @property int $play_fa
 * @property int $play_app
 * @property string $time
 * @property string $directory
 * @property string $dl_link
 * @property string $image
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 * @property int $status_fa
 * @property int $status_app
 * @property int $status_site
 * @property int $status_zizz
 * @property string $title_en
 * @property string $title_fa
 * @property string $artist_name
 * @property string $artist_name_fa
 * @property int $genre_id
 * @property int $hd
 * @property int $hq
 * @property int $lq
 */
class Music extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;

    const TYPE_MP3 = 1;
    const TYPE_VIDEO = 2;
    const TYPE_ALBUM = 3;

    const GENRE_PERSIAN = 1;
    const GENRE_FOREIGN = 2;
    const GENRE_TURKISH = 3;
    const GENRE_ARABIC = 4;
    const GENRE_KOREAN = 5;
    const GENRE_COMINGSOON = 6;
    const GENRE_PODCAST = 7;


    const MUSIC_LINE = ' - ';
    const MUSIC_MP3_KEY = '-music';
    const MUSIC_MP3_KEY_FA = 'دانلود-آهنگ-';
    const MUSIC_VIDEO_KEY = '-video';
    const MUSIC_VIDEO_KEY_FA = 'دانلود موزیک ویدئو ';
    const MUSIC_ALBUM_KEY = '-album';
    const MUSIC_ALBUM_KEY_FA = 'دانلود آلبوم ';

    const TYPE_INAVAR = 1;
    const TYPE_MUSICPLUS = 2;
    const TYPE_APP = 3;
    const TYPE_SITE = 4;
    const TYPE_ZIZZ = 5;

    public $genre;

    const EXT_IMG_MP3 = '.jpg';
    const EXT_IMG_ALBUM = '_.jpg';
    const EXT_IMG_VIDEO = '__.jpg';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'musics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'key_fa', 'type', 'name', 'name_fa', 'user_id', 'created_at', 'updated_at', 'status', 'status_fa', 'status_app', 'status_site'], 'required'],
            [['type', 'artist_id', 'special', 'music_id', 'music_no', 'like', 'like_fa', 'like_app', 'view', 'view_fa', 'view_app', 'play', 'play_fa', 'play_app', 'user_id', 'created_at', 'updated_at', 'status', 'status_fa', 'status_app', 'status_site', 'status_zizz', 'genre_id', 'hd', 'hq', 'lq'], 'integer'],
            [['lyric', 'note', 'note_fa', 'note_app', 'key_pure', 'artist_name', 'artist_name_fa'], 'string'],
            [['key', 'key_fa', 'name', 'name_fa', 'time', 'directory', 'dl_link', 'image', 'title_en', 'title_fa'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'key_fa' => Yii::t('app', 'Key Fa'),
            'key_pure' => Yii::t('app', 'Key'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'name_fa' => Yii::t('app', 'Name Fa'),
            'artist_id' => Yii::t('app', 'Artist ID'),
            'special' => Yii::t('app', 'Special'),
            'music_id' => Yii::t('app', 'Music ID'),
            'music_no' => Yii::t('app', 'Music No'),
            'lyric' => Yii::t('app', 'Lyric'),
            'note' => Yii::t('app', 'Note'),
            'note_fa' => Yii::t('app', 'Note Fa'),
            'note_app' => Yii::t('app', 'Note App'),
            'like' => Yii::t('app', 'Like'),
            'like_fa' => Yii::t('app', 'Like Fa'),
            'like_app' => Yii::t('app', 'Like App'),
            'view' => Yii::t('app', 'View'),
            'view_fa' => Yii::t('app', 'View Fa'),
            'view_app' => Yii::t('app', 'View App'),
            'play' => Yii::t('app', 'Play'),
            'play_fa' => Yii::t('app', 'Play Fa'),
            'play_app' => Yii::t('app', 'Play App'),
            'time' => Yii::t('app', 'Time'),
            'directory' => Yii::t('app', 'Directory'),
            'dl_link' => Yii::t('app', 'Dl Link'),
            'image' => Yii::t('app', 'Image'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
            'status_fa' => Yii::t('app', 'Status Fa'),
            'status_app' => Yii::t('app', 'Status App'),
            'status_site' => Yii::t('app', 'Status Site'),
            'status_zizz' => Yii::t('app', 'Status Zizz'),
            'title_en' => Yii::t('app', 'Title En'),
            'title_fa' => Yii::t('app', 'Title Fa'),
            'artist_name' => Yii::t('app', 'Artist Name'),
            'artist_name_fa' => Yii::t('app', 'Artist Name Fa'),
            'genre_id' => Yii::t('app', 'Genre'),
            'hd' => Yii::t('app', 'HD'),
            'hq' => Yii::t('app', 'HQ'),
            'lq' => Yii::t('app', 'LQ'),
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

        if (isset($fields['artist_id']))
        {
            $fields['artist'] = function($model)
            {
                return $model->artist;
            };
        }

        if (isset($fields['dl_link']))
        {
            $fields['link'] = function($model)
            {
                return $this->makeAddress($model);
            };
        }

        unset($fields['updated_at'], $fields['user_id'], $fields['status'], $fields['status_fa'], $fields['status_app'], $fields['status_site']);

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::class,
                //'createdAtAttribute' => 'created_at',
                'createdAtAttribute' => false,
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
            (string) self::TYPE_MP3 => Yii::t('app', 'Mp3'),
            (string) self::TYPE_VIDEO => Yii::t('app', 'Video'),
            (string) self::TYPE_ALBUM => Yii::t('app', 'Album'),
        ];

    }

    public static function getTypeText($status) {
        switch ($status) {
            case self::TYPE_MP3:
                return 'Mp3';
            case self::TYPE_VIDEO:
                return 'Video';
            case self::TYPE_ALBUM:
                return 'Album';
            default :
                return null;
        }
    }

    public static function getGenreList() {
        return [
            (string) self::GENRE_PERSIAN => Yii::t('app', 'Persian'),
            (string) self::GENRE_FOREIGN => Yii::t('app', 'Foreign'),
            (string) self::GENRE_TURKISH => Yii::t('app', 'Turkish'),
            (string) self::GENRE_ARABIC => Yii::t('app', 'Arabic'),
            (string) self::GENRE_KOREAN => Yii::t('app', 'Korean'),
            (string) self::GENRE_PODCAST => Yii::t('app', 'Podcast'),
            (string) self::GENRE_COMINGSOON => Yii::t('app', 'Coming soon'),
        ];

    }

    public static function getGenreText($status) {
        switch ($status) {
            case self::TYPE_MP3:
                return 'Mp3';
            case self::TYPE_VIDEO:
                return 'Video';
            case self::TYPE_ALBUM:
                return 'Album';
            default :
                return null;
        }
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
            '30' => '30',
        ];

    }

    public function getArtist()
    {
        return $this->hasOne(Artist::className(), ['id' => 'artist_id']);
    }

    protected function makeAddress($model){

        if (Yii::$app->request->headers->get('type') == Music::TYPE_APP){
            $storage = Yii::$app->params['storageServerUrl2'];
        }else{
            $storage = Yii::$app->params['storageServerUrl'];
        }
        if ($model->type == Music::TYPE_MP3){

            $albumKey = '';
            if ($model->music_id != null && $music = Music::find()->where(['id' => $model->music_id])->one()){

                if($music->type == Music::TYPE_ALBUM && $music->id > 6700){
                    $albumKey = $music->key_pure.'/';
                }
            }

            $music320 = $storage.$model->artist->key.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [320].mp3');
            $music128 = $storage.$model->artist->key.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [128].mp3');
            $music96 = $storage.$model->artist->key.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [96].mp3');

            return [
                'music_id' => 'f1/musics/'.$model->id,
                'music_key' => 'f1/musics/'.$model->key,
                '320' => ($model->hd == Music::STATUS_ACTIVE) ? $music320 : null,
                '128' => ($model->hq == Music::STATUS_ACTIVE) ? $music128 : null,
                '96' => ($model->lq == Music::STATUS_ACTIVE) ? $music96 : null,
            ];
        }elseif ($model->type == Music::TYPE_VIDEO){

            $video1080 = $storage.$model->artist->key.'/video/'.rawurlencode($model->dl_link. ' 1080p [iNavar.com].mp4');
            $video720 = $storage.$model->artist->key.'/video/'.rawurlencode($model->dl_link. ' 720p [iNavar.com].mp4');
            $video480 = $storage.$model->artist->key.'/video/'.rawurlencode($model->dl_link. ' 480p [iNavar.com].mp4');

            return [
                'music_id' => 'f1/musics/'.$model->id,
                'music_key' => 'f1/musics/'.$model->key,
                '1080' => ($model->hd == Music::STATUS_ACTIVE) ? $video1080 : null,
                '720' => ($model->hq == Music::STATUS_ACTIVE) ? $video720 : null,
                '480' => ($model->lq == Music::STATUS_ACTIVE) ? $video480 : null,
            ];
        }elseif ($model->type = Music::TYPE_ALBUM){

            $albumKey = '';

            if($model->type == Music::TYPE_ALBUM && $model->id > 6700){
                $albumKey = $model->key_pure.'/';
            }

            $zip320 = $storage.$model->artist->key.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [320].zip');
            $zip128 = $storage.$model->artist->key.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [128].zip');

            return[
                'music_id' => 'f1/musics/'.$model->id,
                'music_key' => 'f1/musics/'.$model->key,
                '320' => ($model->hd == Music::STATUS_ACTIVE) ? $zip320 : null,
                '128' => ($model->hq == Music::STATUS_ACTIVE) ? $zip128 : null
            ];
        }
    }



}
