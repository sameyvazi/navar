<?php

namespace api\modules\api\frontend\v1\models\search;

use common\models\log\LogSearch;
use common\models\music\Music;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use Yii;
use yii\data\Pagination;


class SearchMusic extends Music
{
    public function search($id)
    {
        $this->addLog($id);

        $param = str_replace(' ', '-', $id);
        $duration = 21600; // 6 hours


        $view = \Yii::$app->helper->getTypeView(Yii::$app->request->headers->get('type'));
        $status = \Yii::$app->helper->getTypeStatus(Yii::$app->request->headers->get('type'));

        $query = Music::find()
            //->select(['id', 'name', 'name_fa', 'image', 'type', 'artist_id', 'key_pure', 'artist_name', 'artist_name_fa'])
            ->orWhere(['like', 'name', $param.'%', false])
            ->orWhere(['like', 'name_fa', $param.'%', false])
            ->orWhere(['like', 'key', $param])
            ->orWhere(['like', 'key_fa', $param])
            ->andWhere([$status => Music::STATUS_ACTIVE])
            ->orderBy([$view => SORT_DESC]);

        $countQuery = clone $query;

        if($countQuery->count() == 0){
            $query = Music::find()
                //->select(['id', 'name', 'name_fa', 'image', 'type', 'artist_id', 'key_pure', 'artist_name', 'artist_name_fa'])
                ->andWhere(['like', 'lyric', $id])
                ->andWhere([$status => Music::STATUS_ACTIVE])
                ->orderBy([$view => SORT_DESC]);
        }

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return [
            'musics' => $models,
            'pages' => $pages
        ];


        ////////////////////////////////////////////////
        $this->addLog($id);


        //find tags
        $tags = '';
        $params = str_replace(' ', '-', $id);
        $duration = 100; //60*60*24*7;

        $tag = Tag::getDb()->cache(function () use ($params) {
            return Tag::find()->select(['id'])->where(['like', 'name', $params.'%', false])->all();
        }, $duration);

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

//        $query = Music::find()->where(['id' => $posts, $status => self::STATUS_ACTIVE])->orderBy(['created_at' => SORT_DESC]);
//
//        set_time_limit(20*60);
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'sort' => ['attributes' => ['id', 'created_at', 'like', 'like_fa', 'like_app', 'view', 'view_fa', 'view_app', 'play', 'play_fa', 'play_app', 'created_at']]
//        ]);
//
//        return $dataProvider;

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