<?php

namespace api\modules\api\frontend\v1\models\artist;

use common\models\artist\Artist;
use common\models\music\Music;

class ArtistPage extends Artist
{

    public function search($id, $type)
    {

        $status = \Yii::$app->helper->getTypeStatus($type);
        $play = \Yii::$app->helper->getTypePlay($type);
        $view = \Yii::$app->helper->getTypeView($type);

        $duration = 604000; //1 week

        //latest release
        $latestRelease = Music::find()
        ->where(['artist_id' => $id, $status => Music::STATUS_ACTIVE])
        ->andWhere(['in', 'type', [Music::TYPE_MP3, Music::TYPE_ALBUM]])
        ->orderBy(['created_at' => SORT_DESC])
        ->andWhere(['between', 'created_at', time()-7776000, time()])
        ->one();


        //popular
        $popular = Music::getDb()->cache(function ($db) use ($id, $status, $view) {
            return Music::find()
                ->where(['artist_id' => $id, $status => Artist::STATUS_ACTIVE, 'type' => Music::TYPE_MP3])
                ->andWhere(['NOT IN', 'genre_id', Music::GENRE_COMINGSOON])
                ->orderBy([$view => SORT_DESC])
                ->limit(5)
                ->all();
        }, $duration);

        if ($type == 5){
            $popular = Music::getDb()->cache(function ($db) use ($id, $status, $view) {
                return Music::find()
                    ->where(['artist_id' => $id, $status => Artist::STATUS_ACTIVE, 'type' => Music::TYPE_VIDEO])
                    ->andWhere(['NOT IN', 'genre_id', Music::GENRE_COMINGSOON])
                    ->orderBy([$view => SORT_DESC])
                    ->limit(5)
                    ->all();
            }, $duration);
        }

        //album
        $albums = Music::getDb()->cache(function ($db) use ($id) {
            return Music::find()
                ->where(['type' => Music::TYPE_ALBUM, 'artist_id' => $id])
                ->andWhere(['NOT IN', 'genre_id', Music::GENRE_COMINGSOON])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        }, $duration);

        $alb = [];
        $i = 0;
        foreach ($albums as $album){

            $music = Music::find()->where(['music_id' => $album->id])->orderBy(['music_no' => SORT_ASC]);
            $alb[$i]['album'] = $album;
            $alb[$i]['music_count'] = $music->count();
            $alb[$i]['music'] = $music->all();

            $i++;
        }

        //single
        $singleTrack = Music::getDb()->cache(function ($db) use ($id, $status) {
            return Music::find()
                ->where(['artist_id' => $id, $status => Music::STATUS_ACTIVE, 'type' => Music::TYPE_MP3, 'music_id' => null])
                ->andWhere(['NOT IN', 'genre_id', Music::GENRE_COMINGSOON])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        }, $duration);

        //video
        $musicVideo = Music::getDb()->cache(function ($db) use ($id, $status) {
            return Music::find()
                ->where(['artist_id' => $id, $status => Music::STATUS_ACTIVE, 'type' => Music::TYPE_VIDEO])
                ->andWhere(['NOT IN', 'genre_id', Music::GENRE_COMINGSOON])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        }, $duration);

        return [
            'latest_release' => $latestRelease,
            'popular' => $popular,
            'albums' => $alb,
            'single_track' => $singleTrack,
            'music_video' => $musicVideo
        ];

    }

}