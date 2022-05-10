<?php

namespace api\modules\api\frontend\v1\models\search;

use common\models\artist\Artist;
use common\models\log\LogSearch;
use common\models\music\Music;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;


class SearchArtist extends Artist
{
    public function search($id)
    {

        $this->addLog($id);


        ini_set('memory_limit', '-1');
        //find tags
        $tags = '';
        $params = str_replace(' ', '-', $id);
        $duration = 60*60*24*7;


        $tag = Tag::getDb()->cache(function () use ($params) {
            return Tag::find()->select(['id'])->where(['like', 'name', $params.'%', false])->all();
        }, $duration);


        //var_dump(count($tag));die;


        if ($tag){
            foreach ($tag as $t){
                $tags[] = $t->id;
            }
        }

        //find artist
        $query = TagRelation::getDb()->cache(function () use ($tags) {
            return TagRelation::find()->select(['post_id'])->where(['tag_id' => $tags, 'type' => 1 ])->distinct()->limit(10)->all();
        }, $duration);

        $posts = '';
        foreach ($query as $item){
            $posts [] = $item['post_id'];
        }

        $like = \Yii::$app->helper->getTypeLike(Yii::$app->request->headers->get('type'));
        //$query = static::find()->where(['id' => $posts])->orderBy([$like => SORT_DESC]);



        $query = static::getDb()->cache(function () use ($posts) {
            return static::find()->where(['id' => $posts])->limit(10)->all();
        }, $duration);

        return $query;

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return [
            'artists' => $models,
            'pages' => $pages
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