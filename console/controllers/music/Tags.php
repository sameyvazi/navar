<?php

namespace console\controllers\music;

use common\models\artist\Artist as Artists;
use common\models\music\Music;
use common\models\music\MusicArtist;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use yii\helpers\Console;
use yii\base\Action;
use Yii;

class Tags extends Action {

    public function run() {

        $startOp = $this->controller->prompt('Are you sure start this function? ["yes" or "no"]', [
            'required' => true,
        ]);

        if($startOp !== "yes")
            die;

        Yii::$app->language = 'en';
        $this->controller->stdout("Transfer of Tag started...\n", Console::FG_YELLOW);


//        $artists = Artists::find()->all();
//
//        foreach ($artists as $artist){
//
//            $tags = [];
//            $tagsName = [];
//
//            $tags[] = str_replace(' ', '-', $artist->name);
//            $tags[] = str_replace(' ', '-', $artist->name_fa);
//            $tags[] = $artist->key;
//            $tags[] = $artist->key_fa;
//            $tagsName[] = explode(' ', $artist->name);
//            $tagsName[] = explode(' ', $artist->name_fa);
//
//            foreach ($tagsName as $value){
//                foreach ($value as $item){
//                    $tags[] = $item;
//                }
//            }
//
//            foreach ($tags as $tag){
//
//                $t = ltrim(rtrim($tag));
//                $tagModel = Tag::find()->where(['name' => $t])->one();
//                if (!$tagModel){
//                    $tagModel = new Tag();
//                    $tagModel->name = $t;
//                    $tagModel->save();
//                }
//
//                if (!TagRelation::find()->where(['post_id' => $artist->id, 'tag_id' => $tagModel->id, 'type' => Tag::TYPE_ARTIST])->one()){
//                    $tagRelationModel = new TagRelation();
//                    $tagRelationModel->post_id = $artist->id;
//                    $tagRelationModel->tag_id = $tagModel->id;
//                    $tagRelationModel->type = Tag::TYPE_ARTIST;
//                    $tagRelationModel->save();
//                }
//            }
//        }


        $musics = Music::find()
            ->where(['>', 'id', 4195])
            ->select(['id', 'name', 'name_fa', 'key', 'key_fa', 'type', 'lyric'])
            ->all();

        foreach ($musics as $music){

            $tags = [];
            $tagsName = [];

            $tags[] = str_replace(' ', '-', $music->name);
            $tags[] = str_replace(' ', '-', $music->name_fa);
            $tagsName[] = explode(' ', $music->name);
            $tagsName[] = explode(' ', $music->name_fa);

            foreach ($tagsName as $value){
                foreach ($value as $item){
                    $tags[] = $item;
                }
            }

            if ($music->lyric != ""){


                $paragraph = explode("\n", $music->lyric);

                foreach ($paragraph as $para){

                    if(strlen($para) > 1){

                        $lyrics = explode(' ', $para);
                        foreach ($lyrics as $lyric){
                            $tags[] = trim($lyric);
                        }

                    }
                }
            }

            $artistsIds = [];
            $artists = MusicArtist::find()->where(['music_id' => $music->id])->all();

            foreach ($artists as $artist){
                $artistsIds [] = $artist->artist_id;
            }

            $tagRelations = TagRelation::find()->where(['post_id' => $artistsIds, 'type' => Tag::TYPE_ARTIST])->all();
            foreach ($tagRelations as $tagRelation){
                if ($tag = Tag::find()->where(['id' => $tagRelation->tag_id])->one()){

                    $tags[] = $tag->name;
                }
            }

            /**
             * create tags
             */

            $mainArtist = MusicArtist::find()->where(['music_id' => $music->id, 'activity' => Artists::TYPE_MAIN_ARTIST])->one();
            $art = Artists::find()->where(['id' => $mainArtist->artist_id])->one();

            $createTags = \Yii::$app->tags->createTag($music, $art);
            foreach ($createTags as $createTag){
                $tags[] = $createTag;
            }

            \Yii::$app->tags->hashtag($tags, $music->type, $music->id);

            echo $music->id.'-';

        }

        $this->controller->stdout("The transfer of the Tag is over.\n", Console::FG_GREEN);

    }

}
