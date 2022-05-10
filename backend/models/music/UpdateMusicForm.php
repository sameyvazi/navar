<?php

namespace backend\models\music;

use common\models\artist\Artist;
use common\models\music\Music;
use common\models\music\MusicArtist;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use yii\base\Model;
use yii\helpers\FileHelper;

/**
 * Update form
 */
class UpdateMusicForm extends Model {

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
    public $music_format;
    public $lyric;
    public $note;
    public $note_fa;
    public $note_app;
    public $created_at;
    public $title_en;
    public $title_fa;
    public $artist_name;
    public $artist_name_fa;

    public $like;
    public $like_fa;
    public $like_app;

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
    public $genre_id;

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

    public $key_pure;
    public $dl_link;

    public $hd;
    public $hq;
    public $lq;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'name_fa', 'type', 'status', 'status_fa', 'status_app', 'status_site', 'key', 'key_fa', 'artist_id'], 'required'],
            [['type', 'status', 'status_fa', 'status_app', 'status_site', 'music_id', 'music_no', 'like', 'like_fa', 'like_app', 'special', 'genre_id', 'hd', 'hq', 'lq'], 'integer'],
            [['name', 'name_fa', 'image', 'key', 'key_fa', 'note_fa', 'note_app', 'key_pure', 'dl_link',
                'music1080Upload', 'music720Upload', 'music480Upload', 'music320Upload', 'music128Upload',
                'music1080Address', 'music720Address', 'music480Address', 'music320Address', 'music128Address', 'title_en', 'title_fa'], 'string', 'max' => 255],
            [['artist_id', 'singer_id', 'composer_id', 'lyric_id', 'regulator_id', 'musician_id', 'montage_id', 'director_id'], 'safe'],
            [['lyric', 'artist_name', 'artist_name_fa', 'note'], 'string'],
            ['artist_id', 'existArtist'],
            [['key'], 'uniqueKey'],
            [['key_fa'], 'uniqueKeyFa'],
            [['tag', 'created_at'], 'safe']
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

            if (Music::find()->where(['key' => $this->$attribute])->count() > 1) {
                $this->addError($attribute, 'This key exist');
            }
        }
    }

    public function uniqueKeyFa($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if (Music::find()->where(['key_fa' => $this->$attribute])->count() > 1) {
                $this->addError($attribute, 'This key exist');
            }
        }
    }

    public function attributeLabels() {
        return [

        ];
    }

    public function save($model)
    {
        if (!$this->validate())
        {
            return false;
        }

        $this->image = $model->image;
        $this->like = $model->like;
        $this->like_fa = $model->like_fa;
        $this->like_app = $model->like_app;

        $model->attributes = $this->attributes;
        if ($model->music_id == null){
            $model->music_no = 0;
        }
        //$model->created_at = strlen($this->created_at) > 0 ? strtotime($this->created_at) + 16200 : time();
        $model->created_at = strlen($this->created_at) > 0 ? strtotime($this->created_at) : time();

        if ($model->save(false)){

            MusicArtist::deleteAll(['music_id' => $model->id]);

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

            if ($this->type == Music::TYPE_MP3){
                $type = Tag::TYPE_MP3;
            }elseif ($this->type == Music::TYPE_VIDEO){
                $type = Tag::TYPE_VIDEO;
            }else{
                $type = Tag::TYPE_ALBUM;
            }

            TagRelation::deleteAll(['post_id' => $model->id, 'type' => $type]);

            \Yii::$app->tags->hashtag($tags, $type, $model->id);

            return true;
        }

        return false;
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