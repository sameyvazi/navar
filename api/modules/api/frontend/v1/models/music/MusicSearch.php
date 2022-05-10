<?php

namespace api\modules\api\frontend\v1\models\music;

use common\models\music\Music;
use Yii;
use yii\data\ActiveDataProvider;

class MusicSearch extends Music
{
    public $date_range;
    public $model;

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['type', 'special', 'artist_id'], 'integer'],
            [['name', 'name_fa', 'date_range', 'model'], 'safe']
        ];
    }

    public function search($params)
    {

        $status = \Yii::$app->helper->getTypeStatus(Yii::$app->request->headers->get('type'));

        $query = static::find()->where([$status => Music::STATUS_ACTIVE, 'music_no' => 0]);

//        $query = static::find()->where(['and',
//            $status => Music::STATUS_ACTIVE,
//            ['=', 'type', Music::TYPE_MP3],
//            ['music_id' => null]
//        ])->orWhere(['and',
//            $status => Music::STATUS_ACTIVE,
//            ['type' => Music::TYPE_VIDEO],
//        ])->orWhere(['and',
//            $status => Music::STATUS_ACTIVE,
//            ['type' => Music::TYPE_ALBUM],
//        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(50)],
            'sort' => ['attributes' => ['id', 'created_at', 'like', 'like_fa', 'like_app', 'view', 'view_fa', 'view_app', 'play', 'play_fa', 'play_app', 'created_at']]
        ]);

        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        $this->load($params,'');

        if (isset($this->type)){
            $type = explode(',', $this->type);
            foreach ($type as $item=>$value){
                $t[] = (int)$value;
            }
        }else{
            $t = [1,2,3];
        }




        $query
            ->andWhere(['in', 'type' , $t])
            ->andFilterWhere(['artist_id' => $this->artist_id])
            ->andFilterWhere(['special' => $this->special])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_fa', $this->name_fa]);

        //return $query;

        if ($this->date_range != ''){

            if ($this->date_range == 'w'){
                $range = time() - (3600*24*7);
            }elseif ($this->date_range == 'm'){
                $range = time() - (3600*24*30);
            }elseif ($this->date_range == 'y'){
                $range = time() - (3600*24*365);
            }
            $query->andFilterWhere(['between', 'created_at', $range , time()]);
        }

        if ($this->model != ''){

            if ($this->model == 'ready'){
                $query->andFilterWhere(['NOT IN', 'genre_id', Music::GENRE_COMINGSOON]);
            }elseif ($this->model == 'podcast'){
                $query->andFilterWhere(['=', 'genre_id', Music::GENRE_PODCAST]);
            }elseif ($this->model == 'persian'){
                $query->andFilterWhere(['=', 'genre_id', Music::GENRE_PERSIAN]);
            }elseif ($this->model == 'foreign'){
                $query->andFilterWhere(['=', 'genre_id', Music::GENRE_FOREIGN]);
            }elseif ($this->model == 'turkish'){
                $query->andFilterWhere(['=', 'genre_id', Music::GENRE_TURKISH]);
            }elseif ($this->model == 'arabic'){
                $query->andFilterWhere(['=', 'genre_id', Music::GENRE_ARABIC]);
            }elseif ($this->model == 'korean'){
                $query->andFilterWhere(['=', 'genre_id', Music::GENRE_KOREAN]);
            }

        }

        return $dataProvider;

    }

}