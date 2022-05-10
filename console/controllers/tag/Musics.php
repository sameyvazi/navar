<?php

namespace console\controllers\tag;

use common\models\artist\Artist;
use common\models\music\Music;
use common\models\music\MusicArtist;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use yii\helpers\Console;
use yii\base\Action;
use Yii;

class Musics extends Action {

    public function run() {
        
        Yii::$app->language = 'en';
        $this->controller->stdout("Tag of Musics started...\n", Console::FG_YELLOW);


        $musics = Music::find()->select(['id', 'name', 'name_fa', 'key', 'key_fa', 'type', 'artist_id'])->all();

        foreach($musics as $model){

            $tags = [];

            $tags[] = str_replace(' ', '-', $model->name);
            $tags[] = str_replace(' ', '-', $model->name_fa);
            $tags [] = $model->key;
            $tags [] = $model->key_fa;

            /**
             * find tag artists
             */

            $artistIds = [];
            $musicArtist = MusicArtist::find()->where(['music_id' => $model->id])->all();

            foreach ($musicArtist as $item){
                $artistIds[] = $item->artist_id;
            }

            $tagRelations = TagRelation::find()->where(['post_id' => $artistIds, 'type' => Tag::TYPE_ARTIST])->all();

            foreach ($tagRelations as $tagRelation){
                if ($tag = Tag::find()->where(['id' => $tagRelation->tag_id])->one()){
                    $tags[] = $tag->name;
                }
            }

            /**
             * create tags
             */


            $thisArtist = Artist::find()->where(['id' => $model->artist_id])->one();

            $createTags = \Yii::$app->tags->createTag($model, $thisArtist);
            foreach ($createTags as $createTag){
                $tags[] = $createTag;
            }

            if ($model->type == Music::TYPE_MP3){
                $type = Tag::TYPE_MP3;
            }elseif ($model->type == Music::TYPE_VIDEO){
                $type = Tag::TYPE_VIDEO;
            }else{
                $type = Tag::TYPE_ALBUM;
            }

            \Yii::$app->tags->hashtag($tags, $type, $model->id);

            echo $model->id.'-';
        }

        $this->controller->stdout("The Tag of the Musics is over.\n", Console::FG_GREEN);
        
    }

}
