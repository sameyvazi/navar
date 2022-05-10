<?php

namespace backend\models\music;

use common\models\artist\Artist;
use common\models\music\Music;
use common\models\music\MusicArtist;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use function GuzzleHttp\Psr7\str;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\ServerErrorHttpException;


class AddMusicForm extends Model
{
    
    public $name;
    public $name_fa;
    public $key;
    public $key_fa;
    public $type;
    public $image;
    public $status;
    public $status_fa;
    public $status_app;
    public $status_site;
    public $artist_id;
    public $special;
    public $music_id;
    public $music_no;
    public $lyric;
    public $note;
    public $note_fa;
    public $note_app;
    public $created_at;
    public $title_en;
    public $title_fa;
    public $artist_name;
    public $artist_name_fa;

    public $singer_id = [];
    public $composer_id = [];
    public $lyric_id = [];
    public $regulator_id = [];
    public $musician_id = [];
    public $montage_id = [];
    public $director_id = [];

    public $artist;
    public $newKey;
    public $newKeyFa;
    public $genre;

    public $tag;

    public $music1080Upload;
    public $music1080Address;

    public $music720Upload;
    public $music720Address;

    public $music480Upload;
    public $music480Address;

    public $music320Upload;
    public $music320Address;

    public $music128Upload;
    public $music128Address;

    public $artistsIds = [];

    public $hd;
    public $hq;
    public $lq;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_fa', 'type', 'status', 'status_fa', 'status_app', 'status_site', 'key', 'artist_id'], 'required'],
            [['type', 'status', 'status_fa', 'status_app', 'status_site', 'music_id', 'music_no', 'genre', 'special', 'hd', 'hq', 'lq'], 'integer'],
            [['name', 'name_fa', 'image', 'key', 'key_fa', 'note_fa', 'note_app',
                'music1080Upload', 'music720Upload', 'music480Upload', 'music320Upload', 'music128Upload',
                'music1080Address', 'music720Address', 'music480Address', 'music320Address', 'music128Address', 'title_en', 'title_fa'], 'string', 'max' => 255],
            [['artist_id', 'singer_id', 'composer_id', 'lyric_id', 'regulator_id', 'musician_id', 'montage_id', 'director_id'], 'safe'],
            [['lyric', 'artist_name', 'artist_name_fa', 'note'], 'string'],

            ['artist_id', 'existArtist'],

            [['key'], 'uniqueKey'],
            [['key_fa'], 'uniqueKeyFa'],
            [['name'], 'uniqueName'],
            [['name_fa'], 'uniqueName'],
            [['tag', 'created_at'], 'safe'],
        ];
    }


    public function attributeLabels()
    {
        return [

        ];
    }

    public function existArtist($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->artist = Artist::find()->where(['id' => $this->artist_id])->one();

            if (!$this->artist) {
                $this->addError($attribute, 'The artist does not exist');
            }
        }
    }


    public function uniqueKey($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if ($this->type == Music::TYPE_MP3){
                $pre = '';
            }elseif ($this->type == Music::TYPE_VIDEO){
                $pre = Music::MUSIC_VIDEO_KEY;
            }elseif ($this->type == Music::TYPE_ALBUM){
                $pre = Music::MUSIC_ALBUM_KEY;
            }

            $dlNewKey = '';
            if ($this->singer_id){
                foreach ($this->singer_id as $item){
                    if (strlen($item) > 0){
                        $dlArtist = Artist::find()->where(['id' => $item])->one();
                        $dlNewKey .= '-'.$dlArtist['key'];
                    }
                }
            }

            $this->newKey = substr($this->artist->key.$dlNewKey.'-'.str_replace(' ', '-', trim($this->$attribute)).$pre, 0, 70);

            if (Music::find()->where(['key' => $this->newKey])->one()) {
                $this->addError($attribute, 'This key exist');
            }
        }
    }

    public function uniqueName($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if (Music::find()->where(['name' => $this->$attribute, 'artist_id' => $this->artist_id, 'type' => $this->type])->one()) {
                $this->addError($attribute, 'This name exist');
            }
        }
    }

    public function uniqueKeyFa($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if ($this->type == Music::TYPE_MP3){
                $pre = Music::MUSIC_MP3_KEY_FA;
            }elseif ($this->type == Music::TYPE_VIDEO){
                $pre = Music::MUSIC_VIDEO_KEY_FA;
            }elseif ($this->type == Music::TYPE_ALBUM){
                $pre = Music::MUSIC_ALBUM_KEY_FA;
            }

            $dlNewKey = '';
            if ($this->singer_id){
                foreach ($this->singer_id as $item){
                    if (strlen($item) > 0){
                        $dlArtist = Artist::find()->where(['id' => $item])->one();
                        $dlNewKey .= '-'.$dlArtist['key_fa'];
                    }
                }
            }

            $this->newKeyFa = substr($pre.$this->artist->key_fa.$dlNewKey.'-'.str_replace(' ', '-', trim($this->$attribute)), 0, 70);

            if (Music::find()->where(['key_fa' => $this->newKeyFa])->one()) {
//                $this->addError($attribute, 'This key exist');
            }
        }
    }
    
    public function add($validate = true)
    {
        if ($validate && !$this->validate())
        {
            return false;
        }

        $dlArtistEn = '';
        $dlArtistFa = '';
        $dlArtistKey = '';

        if ($this->singer_id){
            foreach ($this->singer_id as $item){
                if (strlen($item) > 0){
                    $dlArtist = Artist::find()->where(['id' => $item])->one();
                    $dlArtistEn .= ' & '.$dlArtist['name'];
                    $dlArtistFa .= ' و '.$dlArtist['name_fa'];
                    $dlArtistKey .= '-'.$dlArtist['key'];
                }
            }
        }

        if ($this->type == Music::TYPE_MP3){
            $pre = '';
        }elseif ($this->type == Music::TYPE_VIDEO){
            $pre = Music::MUSIC_VIDEO_KEY;
        }elseif ($this->type == Music::TYPE_ALBUM){
            $pre = Music::MUSIC_ALBUM_KEY;
        }

        $model = new Music();

        $this->name = strtolower($this->name);
        $this->key = strtolower($this->key);

        $model->name = $this->name;
        $model->name_fa = $this->name_fa;
        $model->key = strtolower($this->newKey);
        $model->key_fa = $this->newKeyFa;
        $model->key_pure = $this->artist->key.$dlArtistKey.'-'.str_replace(' ', '-', trim($this->name)).$pre;
        $model->type = $this->type;
        $model->status = $this->status;
        $model->status_fa = $this->status_fa;
        $model->status_app = $this->status_app;
        $model->status_site = $this->status_site;
        $model->like = (int)0;
        $model->like_fa = (int)0;
        $model->like_app = (int)0;
        $model->view = (int)0;
        $model->view_fa = (int)0;
        $model->view_app = (int)0;
        $model->play = (int)0;
        $model->play_fa = (int)0;
        $model->play_app = (int)0;
        $model->hd = Music::STATUS_DISABLED;
        $model->hq = Music::STATUS_DISABLED;
        $model->lq = Music::STATUS_DISABLED;



        if ($model->type == Music::TYPE_MP3){
            $titleFa = 'دانلود آهنگ جدید ';
            $titleEn = 'Download New Song By ';
        }elseif ($model->type == Music::TYPE_VIDEO){
            $titleFa = 'دانلود موزیک ویدیو جدید ';
            $titleEn = 'Download New Music Video By ';
        }else{
            $titleFa = 'دانلود آلبوم جدید ';
            $titleEn = 'Download New Album By ';
        }

        $model->title_en = strlen($this->title_en) > 3 ? $this->title_en : $titleEn;
        $model->title_fa = strlen($this->title_fa) > 3 ? $this->title_fa : $titleFa;

        $model->artist_name = strlen($this->artist_name) > 0 ? $this->artist_name : ucwords($this->artist->name.$dlArtistEn);
        $model->artist_name_fa = strlen($this->artist_name_fa) > 0 ? $this->artist_name_fa : ucwords($this->artist->name_fa.$dlArtistEn);

        $model->artist_id = $this->artist_id;
        $model->special = $this->special;
        $model->music_id = $this->music_id;
        $model->music_no = $this->music_no;
        $model->lyric = htmlentities($this->lyric);
        $model->note = htmlentities($this->note);
        $model->note_fa = $this->note_fa;
        $model->note_app = $this->note_app;

        $model-> time = '';
        $model->directory = $this->artist->key;

        date_default_timezone_set("Asia/Tehran");
        $model->created_at = strlen($this->created_at) > 0 ? strtotime($this->created_at) + 16200 : time();

        if ($model->music_id == null){
            $model->music_no = 0;
        }

        $musicNumber = '';
        if ($model->music_no > 0){
            $musicNumber = $model->music_no.' ';
        }

        $model->dl_link = ucwords($musicNumber.$this->artist->name.$dlArtistEn.Music::MUSIC_LINE.$this->name);

        if ($this->type == Music::TYPE_MP3){
            $imageExt = Music::EXT_IMG_MP3;
        }elseif ($this->type == Music::TYPE_ALBUM){
            $imageExt = Music::EXT_IMG_ALBUM;
        }else{
            $imageExt = Music::EXT_IMG_VIDEO;
        }

        $model->image = $this->artist->name.Music::MUSIC_LINE.$this->name.$imageExt;

        $model->genre = $this->genre;
        $model->genre_id = $this->genre;

        if (!$model->save(false))
        {

            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }else{


            if ($model->type == Music::TYPE_ALBUM){
                $this->createDirectory(\Yii::$app->params['uploadUrl'].$model->directory . '/mp3/'.$model->key_pure.'/', 0775, true);
            }

            $mainArtist [] = $this->artist_id;
            $this->artist($model->id, $mainArtist, Artist::TYPE_MAIN_ARTIST);
            $this->artist($model->id, $this->singer_id, Artist::TYPE_SINGER);
            $this->artist($model->id, $this->composer_id, Artist::TYPE_COMPOSER);
            $this->artist($model->id, $this->lyric_id, Artist::TYPE_LYRIC);
            $this->artist($model->id, $this->regulator_id, Artist::TYPE_REGULATOR);
            $this->artist($model->id, $this->musician_id, Artist::TYPE_MUSICIANS);
            $this->artist($model->id, $this->montage_id, Artist::TYPE_MONTAGE);
            $this->artist($model->id, $this->director_id, Artist::TYPE_DIRECTOR);


            /**
             * tag
             */

            $tags[] = str_replace(' ', '-', $this->name);
            $tags[] = str_replace(' ', '-', $this->name_fa);
            $tags [] = $this->key;
            $tags [] = $this->key_fa;

            if ($this->tag != ""){
                $tagCustom = explode("\n", $this->tag);

                foreach ($tagCustom as $t){
                    $tags[] = $t;
                }
            }



            /**
             * find tag artists
             */

            $tagRelations = TagRelation::find()->where(['post_id' => $this->artistsIds, 'type' => Tag::TYPE_ARTIST])->all();
            foreach ($tagRelations as $tagRelation){
                if ($tag = Tag::find()->where(['id' => $tagRelation->tag_id])->one()){

                    $tags[] = $tag->name;
                }
            }

            /**
             * create tags
             */

            if ($model->genre_id == Music::GENRE_PERSIAN || $model->genre_id == Music::GENRE_COMINGSOON || $model->genre_id == Music::GENRE_PODCAST){
                $createTags = \Yii::$app->tags->createTag($model, $this->artist);
            }else{
                $createTags = \Yii::$app->tags->createTagFarsi($model, $this->artist);
            }

            foreach ($createTags as $createTag){
                $tags[] = $createTag;
            }

            if ($this->type == Music::TYPE_MP3){
                $type = Tag::TYPE_MP3;
            }elseif ($this->type == Music::TYPE_VIDEO){
                $type = Tag::TYPE_VIDEO;
            }else{
                $type = Tag::TYPE_ALBUM;
            }

            \Yii::$app->tags->hashtag($tags, $type, $model->id);

        }

        return $model;
    }

    protected function artist($musicId, $artists = [], $activity)
    {

        foreach ($artists as $artist){
            if ($artist != "" && !MusicArtist::find()->where(['music_id' => $musicId, 'artist_id' => $artist, 'activity' => $activity])->one()){
                $musicArtist = new MusicArtist();
                $musicArtist->music_id = $musicId;
                $musicArtist->artist_id = $artist;
                $musicArtist->activity = $activity;
                $musicArtist->type = $this->type;
                $musicArtist->save();

                $this->artistsIds [] = $artist;
            }
        }
        return true;

    }

    public function createDirectory($address, $mode, $recursive){

        FileHelper::createDirectory($address, $mode, $recursive);
        return;

    }

}
