<?php

namespace api\modules\api\frontend\v1\models\search;

use common\models\log\LogSearch;
use common\models\music\Music;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use Yii;
use yii\data\Pagination;


class SearchTag extends Music
{
    public function search($id)
    {
        $param = str_replace(' ', '-', $id);

        $all = ['inavar', 'ای-نوار', 'musicplus', 'موزیک-پلاس', 'navar', 'نوار', 'نوار-موزیک'];
        $allMusic = ['دانلود-آهنگ-جدید', 'آهنگ-جدید', 'دانلود-آهنگ-جدید-ایرانی', 'download-new-song', 'new-song'];
        $allVideo = ['دانلود-موزیک-ویدیو-جدید', 'دانلود-موزیک-ویدیو-جدید-خارجی','download-new-music-video'];
        $allAlbum = ['دانلود-آلبوم-جدید', 'دانلود-آلبوم-جدید-خارجی','download-new-album'];

        $type = false;
        if(is_numeric(array_search($param, $all))){
            $type = [1,2,3];
        }elseif (is_numeric(array_search($param, $allMusic))){
            $type = static::TYPE_MP3;
        }elseif (is_numeric(array_search($param, $allVideo))){
            $type = static::TYPE_VIDEO;
        }elseif (is_numeric(array_search($param, $allAlbum))){
            $type = static::TYPE_ALBUM;
        }

        if($type){
            $status = \Yii::$app->helper->getTypeStatus(Yii::$app->request->headers->get('type'));
            $query = Music::find()->where(['type' => $type, $status => self::STATUS_ACTIVE])->orderBy(['created_at' => SORT_DESC]);
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $models = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

            return [
                'musics' => $models,
                'pages' => $pages
            ];
        }


        $duration = 1; //60*60*24*7

        $tag = Tag::getDb()->cache(function () use ($param) {
            return Tag::find()->select(['id'])->where(['name' => $param])->all();
        }, $duration);

        $tags = [];
        if ($tag){
            foreach ($tag as $t){
                $tags[] = $t->id;
            }
        }

        //find music
        $query = TagRelation::getDb()->cache(function () use ($tags) {
            return TagRelation::find()->select(['post_id'])->where(['tag_id' => $tags, 'type' => [2,3,4] ])->distinct()->all();
        }, $duration);


        $posts = '';
        foreach ($query as $item){
            $posts [] = $item['post_id'];
        }

        $status = \Yii::$app->helper->getTypeStatus(Yii::$app->request->headers->get('type'));
        $query = Music::find()->where(['id' => $posts, $status => self::STATUS_ACTIVE])->orderBy(['created_at' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return [
            'musics' => $models,
            'pages' => $pages
        ];

    }

}