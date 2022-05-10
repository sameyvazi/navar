<?php

namespace api\modules\api\frontend\v1\models\search;

use common\models\artist\Artist;
use common\models\log\LogSearch;
use common\models\music\Music;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use Yii;
use yii\data\Pagination;

class Search extends TagRelation
{

    public $s;

    public function rules()
    {
        return [
            [['artist_id', 'activity', 'type'], 'integer'],
            ['s', 'safe']
        ];
    }

    public function search($params)
    {

        $this->load($params,'');

        $id = $this->s;
        $this->addLog($id);


        $param = str_replace(' ', '-', $id);

        $view = \Yii::$app->helper->getTypeView(Yii::$app->request->headers->get('type'));
        $status = \Yii::$app->helper->getTypeStatus(Yii::$app->request->headers->get('type'));
        $like = \Yii::$app->helper->getTypeLike(Yii::$app->request->headers->get('type'));


        //artist
        if (strpos($this->type, (string)Tag::TYPE_ARTIST ) !== false){

            $artist = $this->searchArtist($param, $status, $like);
            $artists = $artist['items'];
            $artistPages = $artist['pages'];

        }else{
            $artists = null;
            $artistPages = null;
        }


        //music
        if (strpos($this->type, (string)Tag::TYPE_MUSIC ) !== false){

            $music = $this->searchMusic($param, $status, $view, $id, [Music::TYPE_MP3, Music::TYPE_VIDEO, Music::TYPE_ALBUM] );
            $musics = $music['items'];
            $musicPages = $music['pages'];

        }else{
            $musics = null;
            $musicPages = null;
        }


        //mp3
        if (strpos($this->type, (string)Tag::TYPE_MP3 ) !== false){

            $music = $this->searchMusic($param, $status, $view, $id, Music::TYPE_MP3);
            $mp3s = $music['items'];
            $mp3Pages = $music['pages'];

        }else{
            $mp3s = null;
            $mp3Pages = null;
        }

        //albums
        if (strpos($this->type, (string)Tag::TYPE_ALBUM ) !== false){

            $music = $this->searchMusic($param, $status, $view, $id, Music::TYPE_ALBUM);
            $albums = $music['items'];
            $albumPages = $music['pages'];

        }else{
            $albums = null;
            $albumPages = null;
        }

        //videos
        if (strpos($this->type, (string)Tag::TYPE_VIDEO ) !== false){

            $music = $this->searchMusic($param, $status, $view, $id, Music::TYPE_VIDEO);
            $videos = $music['items'];
            $videoPages = $music['pages'];

        }else{
            $videos = null;
            $videoPages = null;
        }


        return [
            'artists' => [
                'items' => $artists,
                'pages' => $artistPages
            ],
            'musics' => [
                'items' => $musics,
                'pages' => $musicPages
            ],
            'mp3s' => [
                'items' => $mp3s,
                'pages' => $mp3Pages
            ],
            'albums' => [
                'items' => $albums,
                'pages' => $albumPages
            ],
            'videos' => [
                'items' => $videos,
                'pages' => $videoPages
            ],
        ];





    }

    protected function searchMusic($param, $status, $view, $id, $type){
        $query = Music::find()
            //->select(['id', 'name', 'name_fa', 'image', 'type', 'artist_id', 'key_pure', 'artist_name', 'artist_name_fa'])
            ->orWhere(['like', 'name', $param.'%', false])
            ->orWhere(['like', 'name_fa', $param.'%', false])
            ->orWhere(['like', 'key', $param])
            ->orWhere(['like', 'key_fa', $param])
            ->andWhere([$status => Music::STATUS_ACTIVE])
            ->andWhere(['type' => $type])
            ->andWhere(['NOT IN', 'genre_id', Music::GENRE_COMINGSOON])
            ->orderBy([$view => SORT_DESC]);

        $countQuery = clone $query;

        if(is_array($type)){

            if($countQuery->count() == 0 && in_array(Music::TYPE_MP3, $type)){
                $query = Music::find()
                    //->select(['id', 'name', 'name_fa', 'image', 'type', 'artist_id', 'key_pure', 'artist_name', 'artist_name_fa'])
                    ->andWhere(['like', 'lyric', $id])
                    ->andWhere([$status => Music::STATUS_ACTIVE])
                    ->andWhere(['NOT IN', 'genre_id', Music::GENRE_COMINGSOON])
                    ->orderBy([$view => SORT_DESC]);
            }
        }


        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 5]);
        $music = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return [
            'items' => $music,
            'pages' => $pages
        ];
    }

    protected function searchArtist($param, $status, $like){
        $query = Artist::find()
            //->select(['id', 'name', 'name_fa', 'image', 'type', 'artist_id', 'key_pure', 'artist_name', 'artist_name_fa'])
            ->orWhere(['like', 'key', '%'.$param.'%', false])
            ->orWhere(['like', 'key_fa', '%'.$param.'%', false])
            ->andWhere([$status => Music::STATUS_ACTIVE])
            ->orderBy([$like => SORT_DESC]);

        $countQuery = clone $query;
        $artistPages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 3]);
        $artists = $query->offset($artistPages->offset)
            ->limit($artistPages->limit)
            ->all();

        return [
            'items' => $artists,
            'pages' => $artistPages
        ];
    }

    protected function addLog($query){

        $model = new LogSearch();
        $model->query = $query;
        $model->created_at = time();
        $model->status = LogSearch::STATUS_ACTIVE;
        $model->type = Yii::$app->request->headers->get('type');
        $model->save();

        return true;
    }

}