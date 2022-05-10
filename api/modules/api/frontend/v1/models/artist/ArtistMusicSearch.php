<?php

namespace api\modules\api\frontend\v1\models\artist;

use common\models\artist\Artist;
use common\models\music\Music;
use common\models\music\MusicArtist;
use yii\data\Pagination;
use yii\helpers\VarDumper;

class ArtistMusicSearch extends MusicArtist
{

    public $sort;
    public $musicIds;

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['artist_id', 'activity', 'type'], 'integer'],
            ['sort', 'safe']
        ];
    }

    public function search($params)
    {


        $this->type = '1,2,3';
        $this->load($params,'');

        $mp3s = null;
        $mp3Pages = null;
        $videos = null;
        $videoPages = null;
        $albums = null;
        $albumPages = null;
        $allMusic = null;
        $allMusicPages = null;

        if ($this->activity == 8 || $this->activity ==1){
            $this->activity = [1,8];
        }


        //find music ids
        $musicArtists = MusicArtist::find()->select(['music_id'])
            ->where(['artist_id' => $this->artist_id])
            ->andWhere(['activity' => isset($this->activity) ? $this->activity : [1,2,3,4,5,6,7,8,9]])
            ->all();

        foreach ($musicArtists as $item){
            $this->musicIds [] = $item->music_id;
        }

        //artist
        $artist = Artist::find()->where(['id' => $this->artist_id])->one();

        //mp3
        if (strpos($this->type, (string)Music::TYPE_MP3 ) !== false){
            $musics =  $this->searchMusic(Music::TYPE_MP3);

            if ($musics){
                $mp3s = $musics['items'];
                $mp3Pages = $musics['pages'];
            }

        }

        //video
        if (strpos($this->type, (string)Music::TYPE_VIDEO ) !== false){

            $musics =  $this->searchMusic(Music::TYPE_VIDEO);
            if ($musics){
                $videos = $musics['items'];
                $videoPages = $musics['pages'];
            }
        }

        //album
        if (strpos($this->type, (string)Music::TYPE_ALBUM ) !== false){

            $musics =  $this->searchMusic(Music::TYPE_ALBUM);
            if ($musics){
                $albums = $musics['items'];
                $albumPages = $musics['pages'];
            }

        }

        return [
            'artist' => $artist,
            'mp3s' => [
                'items' => $mp3s,
                'pages' => $mp3Pages
            ],
            'videos' => [
                'items' => $videos,
                'pages' => $videoPages
            ],
            'albums' => [
                'items' => $albums,
                'pages' => $albumPages
            ],
        ];


    }

    protected function searchMusic($type){

        $query = Music::find()
            ->where(['id' => $this->musicIds])
            ->andWhere(['type' => $type]);


        if($this->sort == 'view'){
            $view = \Yii::$app->helper->getTypeView(\Yii::$app->request->headers->get('type'));
            $query->orderBy([$view => SORT_DESC]);
        }

        if($this->sort == 'created_at'){
            $query->orderBy(['created_at' => SORT_DESC]);
        }

        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 15]);
        $music = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        if($countQuery->count() > 0){
            return [
                'items' => $music,
                'pages' => $pages
            ];
        }else{
            return false;
        }


    }

}